<?php
namespace App\Validator;

use App\Interfaces\Validator\ValidationRule;
use App\Models\Document;

class DocumentValidator
{
    /**
     * @param ValidationRule[] $rules
     * @return ValidationResult - result of validation
     */
    public function validate(Document $document, array $rules): ValidationResult
    {
        $errors = [];

        foreach ($rules as $rule) {
            $error = $rule->validate($document);
            if ($error !== null)
                $errors[] = $error;
        }

        if ( count($errors) )
            return ValidationResult::failure($errors);

        return ValidationResult::success();
    }
}