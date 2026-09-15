<?php
namespace App\Validator\Rules;

use App\Models\Document;

class RequiredMetadataRule extends  BaseValidationRule
{
    /**
     * @param string[] $requiredFields - fields that are required in the metadata
     */
    public function __construct(
        private readonly array $requiredFields,
    ) {
    }
    /**
     * @param Document $document - document to validate
     * @return string|null
    */

    public function validate(Document $document): ?string
    {
        $metadata = $document->getMetadata();

        $missingFields = [];

        foreach ($this->requiredFields as $field) {
            if (
                !array_key_exists($field, $metadata) ||
                $metadata[$field] === null ||
                $metadata[$field] === ''
            ) {
                $missingFields[] = $field;
            }
        }

        if ( !count($missingFields) )
            return null;

        return sprintf(
            'Required metadata fields are missing: %s.',
            implode(', ', $missingFields)
        );
    }
}