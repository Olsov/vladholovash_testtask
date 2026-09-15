<?php
namespace App\Validator\Rules;
use App\Interfaces\Validator\ValidationRule;

use App\Models\Document;
abstract class BaseValidationRule implements  ValidationRule
{

    abstract public function validate(Document $document): ?string;
}