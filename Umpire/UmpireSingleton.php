<?php

namespace Umpire;

use Discord\Discord;
use Dotenv\Dotenv;

class UmpireSingleton extends Discord {
    private static Discord $instance;

    /**
     * @return Discord
     */
    public static function get(): Discord {
        if (! isset(self::$instance)) {
            self::new();
        }

        return self::$instance;
    }

    private static function new(): void {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
        $dotenv->load();
        $dotenv->required('UMPIRE_TOKEN')->notEmpty();

        $discord = new Discord([
            'token' => $_ENV['UMPIRE_TOKEN'],
        ]);

        self::$instance = $discord;
    }
}