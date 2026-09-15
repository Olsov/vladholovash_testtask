<?php

namespace Tests;

use App\Models\Document;
use App\Validator\DocumentValidator;
use App\Validator\Rules\MaxDocumentSizeRule;
use App\Validator\Rules\ProhibitedWordsRule;
use App\Validator\Rules\RequiredMetadataRule;
use PHPUnit\Framework\TestCase;

class DocumentValidatorTest extends TestCase
{
    public function testValidDocumentPassesAllRules(): void
    {
        $document = new Document(
            id: 1,
            tenantId: 1,
            content: 'Normal document content.',
            metadata: [
                'author' => 'John',
                'department' => 'Finance',
            ],
        );

        $rules = [
            new MaxDocumentSizeRule(100),
            new RequiredMetadataRule(['author', 'department']),
            new ProhibitedWordsRule(['forbidden']),
        ];

        $validator = new DocumentValidator();

        $result = $validator->validate($document, $rules);

        self::assertTrue($result->isValid());
        self::assertSame([], $result->getErrors());
    }

    public function testDocumentFailsWhenSizeIsTooLarge(): void
    {
        $document = new Document(
            id: 1,
            tenantId: 1,
            content: str_repeat('A', 101),
        );

        $validator = new DocumentValidator();

        $result = $validator->validate(
            $document,
            [new MaxDocumentSizeRule(100)]
        );

        self::assertFalse($result->isValid());
        self::assertCount(1, $result->getErrors());
    }

    public function testDocumentFailsWhenRequiredMetadataIsMissing(): void
    {
        $document = new Document(
            id: 1,
            tenantId: 1,
            content: 'Test',
            metadata: [
                'author' => 'John',
            ],
        );

        $validator = new DocumentValidator();

        $result = $validator->validate(
            $document,
            [
                new RequiredMetadataRule([
                    'author',
                    'department',
                ]),
            ]
        );

        self::assertFalse($result->isValid());

        self::assertSame(
            ['Required metadata fields are missing: department.'],
            $result->getErrors()
        );
    }

    public function testDocumentFailsWhenProhibitedWordIsFound(): void
    {
        $document = new Document(
            id: 1,
            tenantId: 1,
            content: 'This document contains a FORBIDDEN word.',
        );

        $validator = new DocumentValidator();

        $result = $validator->validate(
            $document,
            [
                new ProhibitedWordsRule(['forbidden']),
            ]
        );

        self::assertFalse($result->isValid());
        self::assertCount(1, $result->getErrors());
    }

    public function testMultipleValidationErrorsAreCollected(): void
    {
        $document = new Document(
            id: 1,
            tenantId: 1,
            content: str_repeat('forbidden ', 20),
            metadata: [],
        );

        $validator = new DocumentValidator();

        $result = $validator->validate(
            $document,
            [
                new MaxDocumentSizeRule(10),
                new RequiredMetadataRule(['author', 'department']),
                new ProhibitedWordsRule(['forbidden']),
            ]
        );

        self::assertFalse($result->isValid());
        self::assertCount(3, $result->getErrors());
    }

    public function testDocumentPassesWhenNoRulesAreConfigured(): void
    {
        $document = new Document(
            id: 1,
            tenantId: 999,
            content: 'Test document',
        );

        $validator = new DocumentValidator();

        $result = $validator->validate($document, []);

        self::assertTrue($result->isValid());
        self::assertSame([], $result->getErrors());
    }
}