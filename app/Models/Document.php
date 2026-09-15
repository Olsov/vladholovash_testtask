<?php
namespace App\Models;

class Document
{
    public function __construct(
        private readonly int $id,
        private readonly int $tenantId,
        private readonly string $content,
        private readonly array $metadata = [],
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTenantId(): int
    {
        return $this->tenantId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }
}