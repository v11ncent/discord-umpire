<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\WebSockets\VoiceStateUpdate;
use Discord\WebSockets\Event;
use Umpire\Umpire;

$umpire = new Umpire();
$client = $umpire->getInstance();

$client->on('ready', function (Discord $client) use ($umpire) {
    $server = $client->guilds->get('id', '476164427968675850');

    if ($server) {
        $umpire->setGuild($server);
    }

    $client->on(Event::VOICE_STATE_UPDATE, function (VoiceStateUpdate $voice) use ($umpire) {
        $user = $voice->member->username;
        $channel = $voice->channel;

        if (\Discord\contains($user, ['eva'])) {
            $umpire->playSoundOnEntrance('foghorn', $channel);
            echo PHP_EOL . 'PLAYED FOGHORN.' . PHP_EOL;
        }
    });
});

$client->run();
