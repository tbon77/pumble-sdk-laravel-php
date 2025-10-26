<?php

namespace Tedakis\PumbleSDK\DTO;

use Illuminate\Support\Collection;

class MessageCollection
{
    private Collection $messages;

    public function __construct(
        public readonly ?string $cursor = null,
        public readonly ?bool $hasMore = null,
        array $messages = []
    ) {
        $this->messages = collect($messages)->map(fn($msg) =>
            $msg instanceof Message ? $msg : Message::fromArray($msg)
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            cursor: $data['cursor'] ?? $data['nextCursor'] ?? null,
            hasMore: $data['hasMore'] ?? $data['has_more'] ?? null,
            messages: $data['messages'] ?? $data['data'] ?? []
        );
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function hasMore(): bool
    {
        return $this->hasMore ?? false;
    }

    public function getCursor(): ?string
    {
        return $this->cursor;
    }

    public function toArray(): array
    {
        return [
            'cursor' => $this->cursor,
            'hasMore' => $this->hasMore,
            'messages' => $this->messages->map(fn(Message $msg) => $msg->toArray())->toArray(),
        ];
    }
}
