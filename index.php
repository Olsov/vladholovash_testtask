<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Document;
use App\Providers\Validator\TenantRuleProvider;
use App\Validator\DocumentValidator;
use App\Validator\Rules\MaxDocumentSizeRule;
use App\Validator\Rules\ProhibitedWordsRule;
use App\Validator\Rules\RequiredMetadataRule;


$tenantRuleProvider = new TenantRuleProvider();

$tenantRuleProvider->setRulesForTenant(
    100,
    [
        new MaxDocumentSizeRule(100),
        new RequiredMetadataRule(['author', 'department']),
    ]
);

$tenantRuleProvider->setRulesForTenant(
    200,
    [
        new MaxDocumentSizeRule(500),
        new ProhibitedWordsRule(['forbidden', 'secret']),
    ]
);

$validator = new DocumentValidator();
$document = new Document(
    id: 1,
    tenantId: 100,
    content: 'This is a test document.',
    metadata: [
        'author' => 'John',
        'department' => 'Finance',
    ],
);

$rules = $tenantRuleProvider->getRulesForTenant(
    $document->getTenantId()
);

$result = $validator->validate($document, $rules);

if ($result->isValid()) {
    echo "Document {$document->getId()} is valid." . PHP_EOL;
} else {
    echo "Document {$document->getId()} failed validation:" . PHP_EOL;

    foreach ($result->getErrors() as $error) {
        echo "- {$error}" . PHP_EOL;
    }
}

$invalidDocument = new Document(
    id: 2,
    tenantId: 100,
    content: str_repeat('A', 200),
    metadata: [
        'author' => 'John',
    ],
);

$rules = $tenantRuleProvider->getRulesForTenant(
    $invalidDocument->getTenantId()
);

$result = $validator->validate($invalidDocument, $rules);

if (!$result->isValid()) {
    foreach ($result->getErrors() as $error) {
        echo $error . PHP_EOL;
    }
}