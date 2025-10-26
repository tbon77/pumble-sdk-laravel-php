<?php

namespace Tedakis\PumbleSDK;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Tedakis\PumbleSDK\Exceptions\PumbleException;

class PumbleClient
{
    private const BASE_URL = 'https://pumble-api-keys.addons.marketplace.cake.com';

    private string $apiKey;
    private PendingRequest $client;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = Http::withHeaders([
            'Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->baseUrl(self::BASE_URL);
    }

    /**
     * List messages from a specific channel
     *
     * @param string $channel Channel name or ID
     * @param string|null $cursor Cursor for pagination
     * @param int|null $limit Number of messages to retrieve
     * @return array
     * @throws PumbleException
     */
    public function listMessages(string $channel, ?string $cursor = null, ?int $limit = null): array
    {
        $params = ['channel' => $channel];

        if ($cursor !== null) {
            $params['cursor'] = $cursor;
        }

        if ($limit !== null) {
            $params['limit'] = $limit;
        }

        $response = $this->client->get('/listMessages', $params);

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to list messages: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
    }

    /**
     * List all channels in the workspace
     *
     * @return array
     * @throws PumbleException
     */
    public function listChannels(): array
    {
        $response = $this->client->get('/listChannels');

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to list channels: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
    }

    /**
     * List all users in the workspace
     *
     * @return array
     * @throws PumbleException
     */
    public function listUsers(): array
    {
        $response = $this->client->get('/listUsers');

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to list users: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
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
        $response = $this->client->post('/sendMessage', [
            'channel' => $channel,
            'text' => $text,
            'asBot' => $asBot,
        ]);

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to send message: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
    }

    /**
     * Reply to a specific message
     *
     * @param string $channel Channel name or ID
     * @param string $messageId ID of the message to reply to
     * @param string $text Reply content
     * @param bool $asBot Send as bot (true) or personal account (false)
     * @return array
     * @throws PumbleException
     */
    public function sendReply(string $channel, string $messageId, string $text, bool $asBot = true): array
    {
        $response = $this->client->post('/sendReply', [
            'channel' => $channel,
            'messageId' => $messageId,
            'text' => $text,
            'asBot' => $asBot,
        ]);

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to send reply: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
    }

    /**
     * Add a reaction to a message
     *
     * @param string $channel Channel name or ID
     * @param string $messageId ID of the message to react to
     * @param string $emoji Emoji to add (e.g., "thumbsup", "heart")
     * @return array
     * @throws PumbleException
     */
    public function addReaction(string $channel, string $messageId, string $emoji): array
    {
        $response = $this->client->post('/addReaction', [
            'channel' => $channel,
            'messageId' => $messageId,
            'emoji' => $emoji,
        ]);

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to add reaction: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
    }

    /**
     * Delete a message
     *
     * @param string $channel Channel name or ID
     * @param string $messageId ID of the message to delete
     * @return array
     * @throws PumbleException
     */
    public function deleteMessage(string $channel, string $messageId): array
    {
        $response = $this->client->delete('/deleteMessage', [
            'channel' => $channel,
            'messageId' => $messageId,
        ]);

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to delete message: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
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
        $response = $this->client->post('/createChannel', [
            'name' => $name,
            'isPrivate' => $isPrivate,
        ]);

        if ($response->failed()) {
            throw new PumbleException(
                "Failed to create channel: {$response->body()}",
                $response->status()
            );
        }

        return $response->json();
    }
}
