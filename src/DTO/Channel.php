<?php

namespace Tedakis\PumbleSDK\DTO;

class Channel
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly bool $isPrivate,
        public readonly ?string $description = null,
        public readonly ?string $createdAt = null,
        public readonly ?array $members = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            name: $data['name'] ?? '',
            isPrivate: $data['isPrivate'] ?? $data['is_private'] ?? false,
            description: $data['description'] ?? null,
            createdAt: $data['createdAt'] ?? $data['created_at'] ?? null,
            members: $data['members'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'isPrivate' => $this->isPrivate,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'members' => $this->members,
        ];
    }
}
