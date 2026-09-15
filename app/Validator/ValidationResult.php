<?php
namespace App\Validator;

use App\Interfaces\Validator\ValidationRule;

class ValidationResult
{
    /**
     * @param bool $isValid
     * @param string[] $errors
     */
    public function __construct(
        private readonly bool $isValid,
        private readonly array $errors = [],
    ) {
    }
    /**
     * @return bool - return true if validation is valid
    */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return string[] - get errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    /**
     * @return  ValidationRule - return validation with success
    */
    public static function success(): self
    {
        return new self(true);
    }

    /**
     * @param string[] $errors
     * @return ValidationResult - return validation with errors
     */
    public static function failure(array $errors): self
    {
        return new self(false, $errors);
    }
}