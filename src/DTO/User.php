<?php

namespace Tedakis\PumbleSDK\DTO;

class User
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $avatarUrl = null,
        public readonly ?bool $isBot = null,
        public readonly ?string $status = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            name: $data['name'] ?? '',
            email: $data['email'] ?? '',
            avatarUrl: $data['avatarUrl'] ?? $data['avatar_url'] ?? null,
            isBot: $data['isBot'] ?? $data['is_bot'] ?? null,
            status: $data['status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatarUrl' => $this->avatarUrl,
            'isBot' => $this->isBot,
            'status' => $this->status,
        ];
    }
}
