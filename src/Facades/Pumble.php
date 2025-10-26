<?php

namespace Tedakis\PumbleSDK\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Tedakis\PumbleSDK\DTO\MessageCollection getMessages(string $channel, ?string $cursor = null, ?int $limit = null)
 * @method static \Illuminate\Support\Collection getAllMessages(string $channel, int $limit = 100)
 * @method static \Illuminate\Support\Collection getChannels()
 * @method static \Illuminate\Support\Collection getUsers()
 * @method static array sendMessage(string $channel, string $text, bool $asBot = true)
 * @method static array replyToMessage(string $channel, string $messageId, string $text, bool $asBot = true)
 * @method static array addReaction(string $channel, string $messageId, string $emoji)
 * @method static array deleteMessage(string $channel, string $messageId)
 * @method static array createChannel(string $name, bool $isPrivate = false)
 * @method static \Illuminate\Support\Collection searchMessages(string $channel, string $searchTerm)
 * @method static \Illuminate\Support\Collection getMessagesByUser(string $channel, string $userId)
 *
 * @see \Tedakis\PumbleSDK\PumbleService
 */
class Pumble extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pumble';
    }
}
