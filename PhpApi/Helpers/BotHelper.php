<?php

namespace PhpApi\Helpers;

trait BotHelper
{

	/**
     * parse relevant message data from payload
     *
     * @param array $payload
     * @return object
     */
    public static function getMessage(array $payload): object
    {
        return (object)[
            'id'         => $payload['message']['message_id'],
            'chat_id'    => $payload['message']['chat']['id'],
            'text'       => $payload['message']['text'] ?? null,
            'is_command' => self::isCommand($payload),
            'user' => (object)[
                'id'        => $payload['message']['from']['id'],
                'username'  => $payload['message']['from']['username'],
                'firstname' => $payload['message']['from']['first_name'],
                'lastname'  => $payload['message']['from']['last_name'],
                'is_bot'    => $payload['message']['from']['is_bot'],
            ]
        ];
    }

    /**
     * checks if current event is a command
     *
     * @param array $payload
     * @return bool
     */
    public static function isCommand(array $payload): bool
    {
        foreach ($payload['message']['entities'] as $entity) {
            if ($entity['type'] == 'bot_command') {
                return true;
            }
        }

        return false;
    }

    /**
     * checks if current event is a link
     *
     * @param array $payload
     * @return bool
     */
    public static function hasLink(array $payload): bool
    {
        foreach ($payload['message']['entities'] as $entity) {
            if ($entity['type'] == 'url') {
                return true;
            }
        }

        return false;
    }

    /**
     * get command and its corresponding arguments
     *
     * @param array $payload
     * @param string $command
     * @return array|null
     */
    public static function getCommand(array $payload, string $command): ?array
    {
        $text = $payload['message']['text'];
        $command = str_starts_with($command, '/') ? $command : ('/' . $command);
        $result = [
            'command' => explode(' ', $text)[0],
            'argument' => ''
        ];

        /* check if command exists */
        if ($result['command'] !== $command)
            return null;

        $result['argument'] = trim(str_replace($command, '', $text));
        return $result;
    }

    /**
     * get link from message
     *
     * @param array $payload
     * @return string|null
     */
    public static function getLink(array $payload): ?string
    {
        foreach ($payload['message']['entities'] as $entity) {
            if ($entity['type'] == 'url') {
                return substr($payload['message']['text'], $entity['offset'], $entity['length']);
            }
        }

        return null;
    }

}