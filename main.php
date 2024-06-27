<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Umpire\Umpire;

$umpire = new Umpire();
$client = $umpire->getInstance();

$client->on('ready', function (Discord $client) use ($umpire) {
    $server = $client->guilds->get('id', '476164427968675850');

    if ($server) {
        $umpire->setGuild($server);
    }

    $client->on(Event::MESSAGE_CREATE, function (Message $message) use ($umpire) {
        if (\Discord\contains($message->content, $umpire->getTopics())) {
            $umpire->startTopicThread($message);
        }
    });
});
