<?php

namespace Umpire;
use Dotenv\Dotenv;

class Bot
{
    public String $token;
    public String $serverId;
    public String $channelId;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
        $dotenv->load();
        $dotenv->required(['UMPIRE_TOKEN', 'UMPIRE_GUILD_ID', 'UMPIRE_CHANNEL_ID'])->notEmpty();

        $this->token = $_ENV['UMPIRE_TOKEN'];
        $this->serverId = $_ENV['UMPIRE_GUILD_ID'];
        $this->channelId = $_ENV['UMPIRE_CHANNEL_ID'];
    }
}