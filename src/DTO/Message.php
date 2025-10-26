<?php

namespace Tedakis\PumbleSDK\DTO;

class Message
{
    public function __construct(
        public readonly string $id,
        public readonly string $text,
        public readonly string $channelId,
        public readonly string $userId,
        public readonly string $createdAt,
        public readonly ?string $updatedAt = null,
        public readonly ?array $reactions = null,
        public readonly ?string $threadId = null,
        public readonly ?array $mentions = null,
        public readonly ?array $attachments = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            text: $data['text'] ?? '',
            channelId: $data['channelId'] ?? $data['channel_id'] ?? '',
            userId: $data['userId'] ?? $data['user_id'] ?? '',
            createdAt: $data['createdAt'] ?? $data['created_at'] ?? '',
            updatedAt: $data['updatedAt'] ?? $data['updated_at'] ?? null,
            reactions: $data['reactions'] ?? null,
            threadId: $data['threadId'] ?? $data['thread_id'] ?? null,
            mentions: $data['mentions'] ?? null,
            attachments: $data['attachments'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'channelId' => $this->channelId,
            'userId' => $this->userId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'reactions' => $this->reactions,
            'threadId' => $this->threadId,
            'mentions' => $this->mentions,
            'attachments' => $this->attachments,
        ];
    }
}
