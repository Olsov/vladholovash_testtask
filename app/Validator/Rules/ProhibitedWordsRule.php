<?php
namespace App\Validator\Rules;

use App\Models\Document;

class ProhibitedWordsRule extends BaseValidationRule
{
    /**
     * @param string[] $prohibitedWords - words that are not allowed in the document
     */
    public function __construct(
        private  array $prohibitedWords,
    ) {
    }
    /**
     * @param Document $document - document to validate
     * @return string|null*/

    public function validate(Document $document): ?string
    {
        $content = mb_strtolower($document->getContent());
        $foundWords = [];

        foreach ($this->prohibitedWords as $word) {
            if (mb_strpos($content, mb_strtolower($word)) !== false)
                $foundWords[] = $word;
        }

        if ( !count($foundWords) )
            return null;

        return
            sprintf(
                'Document contains prohibited words: "%s".',
                implode('", "', $foundWords)
            );
    }
}