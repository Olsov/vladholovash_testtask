<?php
namespace App\Validator\Rules;

use App\Models\Document;

class MaxDocumentSizeRule extends BaseValidationRule
{
    /**
     * @param int $maxBytes - maximum allowed size in bytes
     * */
    public function __construct(
        private readonly int $maxBytes,
    ) {
    }
    /**
     * @param Document $document
     * @return string|null
     * */
    public function validate(Document $document): ?string
    {
        $size = strlen($document->getContent());

        if ($size <= $this->maxBytes)
            return null;

        return sprintf(
            'Document size (%d bytes) exceeds maximum allowed size of %d bytes.',
            $size,
            $this->maxBytes
        );
    }
}