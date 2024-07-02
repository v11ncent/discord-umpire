<?php

namespace Umpire;

use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Discord\Voice\VoiceClient;
use Discord\WebSockets\Intents;
use Dotenv\Dotenv;

class Umpire
{
    private const string SOUND_DIRECTORY = __DIR__ . '\\sounds\\';
    private Guild $guild;
    private Channel $channel;
    private Discord $instance;
    private array $topics;
    private string $token;

    /**
     * @param string|null $token The Discord bot token.
     * @param array|null $topics An array of topics to watch.
     *
     * @return void
     *
     * @throws IntentException thrown when an invalid intent is given.
     */
    public function __construct(
        ?string $token = null,
        ?array $topics = ['baseball'],
    ) {
        if ($token == null) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
            $dotenv->load();
            $dotenv->required(['UMPIRE_TOKEN'])->notEmpty();
            $this->setToken($_ENV['UMPIRE_TOKEN']);
        } else {
            $this->setToken($token);
        }

        $this->setTopics($topics);

        $this->instance = new Discord([
            'token' => $this->token,
            'intents' => Intents::getAllIntents()
        ]);
    }

    /**
     * Sets an array of topics.
     *
     * @param array $topics An array of banned topics.
     *
     * @return void
     */
    public function setTopics(array $topics): void
    {
        $this->topics = $topics;
    }

    /**
     * Gets an array of banned topics.
     * @return array
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * Sets the `token` that the bot runs with.
     *
     * @param string $token
     *
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Gets a Discord Application `token` that the bot runs with.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Sets a `Guild` the bot is assigned to.
     *
     * @param Guild $guild
     *
     * @return void
     */
    public function setGuild(Guild $guild): void
    {
        $this->guild = $guild;
    }

    /**
     * Gets the `Guild` the bot is assigned to.
     *
     * @return Guild
     */
    public function getGuild(): Guild
    {
        return $this->guild;
    }

    /**
     * Sets a `Channel` the bot is assigned to.
     *
     * @param Channel $channel
     *
     * @return void
     */
    public function setChannel(Channel $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return Channel The `Channel` the bot is in.
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    /**
     * @return Discord instance.
     */
    public function getInstance(): Discord
    {
        return $this->instance;
    }

    /**
     * Starts a new thread based off a topic.
     *
     * @param Message $message A Message sent in a channel.
     * @param int|null $auto_archive_duration Number of minutes of inactivity before the thread archives.
     *
     * @return void
     */
    public static function startTopicThread(
        Message $message,
        ?int $auto_archive_duration = 60,
    ): void {
        $content = $message->content;
        $message->startThread($content, $auto_archive_duration);
    }

    /**
     * Plays a sound in a `Channel`.
     *
     * @param string $sound The sound to be played.
     * @param Channel $channel The channel to play the sound in.
     */
    public function playSoundOnEntrance(string $sound, Channel $channel): void
    {
        $file = self::SOUND_DIRECTORY . $sound . '.wav';

        // Exception is already handled in `playFile()`; no need to wrap in try-catch
        $this->instance
            ->joinVoiceChannel($channel)
            ->then(function (VoiceClient $client) use ($file) {
                $client->playFile($file)->then(function () use ($client) {
                    $client->close();
                });
            });
    }
}
