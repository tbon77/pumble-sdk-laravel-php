<?php

namespace Tedakis\PumbleSDK;

use Tedakis\PumbleSDK\DTO\Message;
use Tedakis\PumbleSDK\DTO\MessageCollection;
use Tedakis\PumbleSDK\DTO\Channel;
use Tedakis\PumbleSDK\DTO\User;
use Tedakis\PumbleSDK\Exceptions\PumbleException;
use Illuminate\Support\Collection;

class PumbleService
{
    private PumbleClient $client;

    public function __construct(string $apiKey)
    {
        $this->client = new PumbleClient($apiKey);
    }

    /**
     * Get messages from a channel with optional pagination
     *
     * @param string $channel Channel name or ID
     * @param string|null $cursor Cursor for pagination
     * @param int|null $limit Number of messages to retrieve
     * @return MessageCollection
     * @throws PumbleException
     */
    public function getMessages(string $channel, ?string $cursor = null, ?int $limit = null): MessageCollection
    {
        $response = $this->client->listMessages($channel, $cursor, $limit);
        return MessageCollection::fromArray($response);
    }

    /**
     * Get all messages from a channel (handles pagination automatically)
     *
     * @param string $channel Channel name or ID
     * @param int $limit Messages per page
     * @return Collection<Message>
     * @throws PumbleException
     */
    public function getAllMessages(string $channel, int $limit = 100): Collection
    {
        $allMessages = collect();
        $cursor = null;

        do {
            $collection = $this->getMessages($channel, $cursor, $limit);
            $allMessages = $allMessages->concat($collection->getMessages());
            $cursor = $collection->getCursor();
        } while ($collection->hasMore());

        return $allMessages;
    }

    /**
     * Get channels in the workspace
     *
     * @return Collection<Channel>
     * @throws PumbleException
     */
    public function getChannels(): Collection
    {
        $response = $this->client->listChannels();
        $channels = $response['channels'] ?? $response['data'] ?? $response;

        return collect($channels)->map(fn($channel) => Channel::fromArray($channel));
    }

    /**
     * Get users in the workspace
     *
     * @return Collection<User>
     * @throws PumbleException
     */
    public function getUsers(): Collection
    {
        $response = $this->client->listUsers();
        $users = $response['users'] ?? $response['data'] ?? $response;

        return collect($users)->map(fn($user) => User::fromArray($user));
    }

    /**
     * Send a message to a channel
     *
     * @param string $channel Channel name or ID
     * @param string $text Message content
     * @param bool $asBot Send as bot (true) or personal account (false)
     * @return array
     * @throws PumbleException
     */
    public function sendMessage(string $channel, string $text, bool $asBot = true): array
    {
        return $this->client->sendMessage($channel, $text, $asBot);
    }

    /**
     * Reply to a message
     *
     * @param string $channel Channel name or ID
     * @param string $messageId Message ID to reply to
     * @param string $text Reply content
     * @param bool $asBot Send as bot (true) or personal account (false)
     * @return array
     * @throws PumbleException
     */
    public function replyToMessage(string $channel, string $messageId, string $text, bool $asBot = true): array
    {
        return $this->client->sendReply($channel, $messageId, $text, $asBot);
    }

    /**
     * Add a reaction to a message
     *
     * @param string $channel Channel name or ID
     * @param string $messageId Message ID
     * @param string $emoji Emoji name
     * @return array
     * @throws PumbleException
     */
    public function addReaction(string $channel, string $messageId, string $emoji): array
    {
        return $this->client->addReaction($channel, $messageId, $emoji);
    }

    /**
     * Delete a message
     *
     * @param string $channel Channel name or ID
     * @param string $messageId Message ID
     * @return array
     * @throws PumbleException
     */
    public function deleteMessage(string $channel, string $messageId): array
    {
        return $this->client->deleteMessage($channel, $messageId);
    }

    /**
     * Create a new channel
     *
     * @param string $name Channel name
     * @param bool $isPrivate Whether the channel is private
     * @return array
     * @throws PumbleException
     */
    public function createChannel(string $name, bool $isPrivate = false): array
    {
        return $this->client->createChannel($name, $isPrivate);
    }

    /**
     * Search messages by text content
     *
     * @param string $channel Channel name or ID
     * @param string $searchTerm Text to search for
     * @return Collection<Message>
     * @throws PumbleException
     */
    public function searchMessages(string $channel, string $searchTerm): Collection
    {
        $allMessages = $this->getAllMessages($channel);

        return $allMessages->filter(function (Message $message) use ($searchTerm) {
            return str_contains(strtolower($message->text), strtolower($searchTerm));
        });
    }

    /**
     * Get messages from a specific user in a channel
     *
     * @param string $channel Channel name or ID
     * @param string $userId User ID
     * @return Collection<Message>
     * @throws PumbleException
     */
    public function getMessagesByUser(string $channel, string $userId): Collection
    {
        $allMessages = $this->getAllMessages($channel);

        return $allMessages->filter(function (Message $message) use ($userId) {
            return $message->userId === $userId;
        });
    }
}
