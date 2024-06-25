<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

// loads our .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// require our .env variables to be set
$dotenv
    ->required(['UMPIRE_TOKEN', 'UMPIRE_GUILD_ID', 'UMPIRE_CHANNEL_ID'])
    ->notEmpty();

// .env variables for our bot
$token = $_ENV['UMPIRE_TOKEN'];
$serverId = $_ENV['UMPIRE_GUILD_ID'];
$channelId = $_ENV['UMPIRE_CHANNEL_ID'];

$discord = new Discord([
    'token' => $token,
    'intents' => Intents::getDefaultIntents(),
]);

$discord->on('ready', function () use ($discord, $serverId, $channelId) {
    $server = $discord->guilds->get('id', $serverId);

    $discord->on(Event::MESSAGE_CREATE, function (
        Message $message,
        Discord $discord,
    ) {
        $content = $message->content;
        $phrases = [ // feature: topic analysis instead of phrases
            'baseball', 'phils', 'phillies',
            'harper', 'trea', 'ranger',
            'bohm', 'reserves', 'wheeler',
            'sanchez', 'strahm', 'marsh',
            'doomer', 'philly', 'starters',
            'starter', 'triple play', 'pablo lopez',
            'peds', 'mlb', 'catcher',
            'pitcher', 'bat', 'walker',
            'gave up a run', 'bum', 'taijuan',
            'yank'
        ];

        if (\Discord\contains(strtolower($content), $phrases)) {
            $message->startThread(
                '⚾ ' . $message->content,
                60,
                'Those goblins were talking about baseball!',
            );

            // creates a date 30s into the future and times out author
            // $timeoutDuration = \Carbon\Carbon::now()->addSeconds(30);
            // $message->member->timeoutMember($timeoutDuration);
        }
    });
});

$discord->run();