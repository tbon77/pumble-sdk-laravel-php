# Pumble SDK for PHP/Laravel

A modern PHP SDK for the Pumble API with first-class Laravel support. Read channel messages, send messages, manage channels, and more.

[![Latest Version](https://img.shields.io/packagist/v/tedakis/pumble-sdk-php.svg)](https://packagist.org/packages/tedakis/pumble-sdk-php)
[![License](https://img.shields.io/packagist/l/tedakis/pumble-sdk-php.svg)](https://packagist.org/packages/tedakis/pumble-sdk-php)
[![PHP Version](https://img.shields.io/packagist/php-v/tedakis/pumble-sdk-php.svg)](https://packagist.org/packages/tedakis/pumble-sdk-php)

## Features

- Read channel messages with pagination support
- Send messages and replies
- Manage channels (create, list)
- User management (list users)
- Add reactions to messages
- Search and filter messages
- Type-safe DTOs (Data Transfer Objects)
- Laravel service provider and facade
- Comprehensive error handling

## Requirements

- PHP 8.1 or higher
- Laravel 10.x or 11.x

## Installation

Install the package via Composer:

```bash
composer require tedakis/pumble-sdk-php
```

### Laravel Auto-Discovery

The package will automatically register its service provider and facade.

### Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=pumble-config
```

This will create a `config/pumble.php` file.

## Configuration

### Get Your API Key

1. Open Pumble
2. Run the command: `/api-keys generate`
3. Copy the API key from the ephemeral message

### Add to .env

```env
PUMBLE_API_KEY=your-api-key-here
```

## Usage

### Using the Facade (Laravel)

```php
use Tedakis\PumbleSDK\Facades\Pumble;

// Get messages from a channel
$messages = Pumble::getAllMessages('general');

foreach ($messages as $message) {
    echo "{$message->userId}: {$message->text}\n";
}

// Send a message
Pumble::sendMessage('general', 'Hello from Laravel!');

// Search messages
$results = Pumble::searchMessages('general', 'important');

// List all channels
$channels = Pumble::getChannels();
```

### Using Dependency Injection (Laravel)

```php
use Tedakis\PumbleSDK\PumbleService;

class MessageController extends Controller
{
    public function __construct(
        private PumbleService $pumble
    ) {}

    public function index(string $channel)
    {
        $messages = $this->pumble->getAllMessages($channel);

        return view('messages.index', compact('messages'));
    }
}
## API Reference

### Reading Messages

#### Get Messages with Pagination

```php
// Get first page
$collection = Pumble::getMessages('general', limit: 50);

foreach ($collection->getMessages() as $message) {
    // Process message
}

// Get next page if available
if ($collection->hasMore()) {
    $nextPage = Pumble::getMessages('general', cursor: $collection->getCursor());
}
```

#### Get All Messages (Auto-pagination)

```php
// Automatically handles pagination
$allMessages = Pumble::getAllMessages('general');
```

#### Search Messages

```php
$results = Pumble::searchMessages('general', 'search term');
```

#### Get Messages by User

```php
$userMessages = Pumble::getMessagesByUser('general', 'user-id-123');
```

### Channels

```php
// List all channels
$channels = Pumble::getChannels();

// Create a new channel
Pumble::createChannel('new-channel', isPrivate: false);
```

### Users

```php
// List all users
$users = Pumble::getUsers();

foreach ($users as $user) {
    echo "{$user->name} ({$user->email})\n";
}
```

### Sending Messages

```php
// Send as bot (default)
Pumble::sendMessage('general', 'Hello!');

// Send as your personal account
Pumble::sendMessage('general', 'Hello!', asBot: false);

// Reply to a message
Pumble::replyToMessage('general', 'message-id', 'This is a reply');
```

### Reactions

```php
// Add a reaction
Pumble::addReaction('general', 'message-id', 'thumbsup');
Pumble::addReaction('general', 'message-id', 'heart');
```

### Delete Messages

```php
Pumble::deleteMessage('general', 'message-id');
```

## Data Transfer Objects

### Message

```php
$message->id           // Message ID
$message->text         // Message content
$message->channelId    // Channel ID
$message->userId       // User ID
$message->createdAt    // Creation timestamp
$message->updatedAt    // Update timestamp (nullable)
$message->reactions    // Array of reactions (nullable)
$message->threadId     // Thread ID (nullable)
$message->mentions     // Mentioned users (nullable)
$message->attachments  // Message attachments (nullable)
```

### Channel

```php
$channel->id          // Channel ID
$channel->name        // Channel name
$channel->isPrivate   // Is private channel
$channel->description // Description (nullable)
$channel->createdAt   // Creation timestamp (nullable)
$channel->members     // Member IDs (nullable)
```

### User

```php
$user->id         // User ID
$user->name       // Display name
$user->email      // Email address
$user->avatarUrl  // Avatar URL (nullable)
$user->isBot      // Is bot account (nullable)
$user->status     // User status (nullable)
```

## Advanced Examples

### Export Messages to JSON

```php
$messages = Pumble::getAllMessages('general');

$exported = $messages->map(fn($m) => [
    'id' => $m->id,
    'text' => $m->text,
    'user' => $m->userId,
    'timestamp' => $m->createdAt,
])->toArray();

file_put_contents('messages.json', json_encode($exported, JSON_PRETTY_PRINT));
```

### Find Most Active Users

```php
$messages = Pumble::getAllMessages('general');

$activity = $messages->groupBy('userId')
    ->map(fn($msgs) => $msgs->count())
    ->sortDesc();

foreach ($activity as $userId => $count) {
    echo "User {$userId}: {$count} messages\n";
}
```

### Message Statistics

```php
$messages = Pumble::getAllMessages('general');

$stats = [
    'total' => $messages->count(),
    'with_reactions' => $messages->filter(fn($m) => !empty($m->reactions))->count(),
    'in_threads' => $messages->filter(fn($m) => $m->threadId !== null)->count(),
];
```

## Error Handling

```php
use Tedakis\PumbleSDK\Exceptions\PumbleException;

try {
    $messages = Pumble::getMessages('general');
} catch (PumbleException $e) {
    Log::error('Pumble API Error', [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
    ]);
}
```

## Testing

```bash
composer test
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.