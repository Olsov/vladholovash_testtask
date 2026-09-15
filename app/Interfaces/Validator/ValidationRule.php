<?php
namespace App\Interfaces\Validator;

use App\Models\Document;

interface ValidationRule
{
    /**
     * returns errors or null if validate
     */
    public function validate(Document $document): ?string;
}