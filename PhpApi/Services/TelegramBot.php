<?php

namespace PhpApi\Services;

use GuzzleHttp\Client;
use PhpApi\Core\Functions\Logger;
use PhpApi\Helpers\BotHelper;
use GuzzleHttp\Exception\GuzzleException;

/**
 * module for controlling telegram bot
 */
class TelegramBot
{

    use BotHelper;

    /** @var string $baseUrl */
    protected string $baseUrl = 'https://api.telegram.org/bot{token}/';

    /** @var string $token */
    protected string $token = '';

    /** @var string $chatId */
    protected string $chatId = '';

    /** @var string $mode */
    protected string $mode = 'html';

    /** @var ?Client $api */
    protected ?Client $api = null;

    /**
     * @var array $options options to send with the message
     */
    protected array $options = [];

    /** @var array $endpoints */
    protected array $endpoints = [
        'updates' => 'getUpdates',
        'message' => 'sendMessage',
        'photo' => 'sendPhoto',
        'video' => 'sendVideo',
        'document' => 'sendDocument',
        'delete' => 'deleteMessage',
        'action' => 'sendChatAction',
    ];

    /**
     * default constructor
     */
    function __construct()
    {
        $this->api = new Client();
    }

    /**
     * send request to API
     *
     * @param string $action
     * @param array $data
     * @return array|null
     */
    protected function __send(string $action, array $data): ?array
    {
        try {
            $endpoint = $this->baseUrl . $this->endpoints[$action];
            $endpoint = str_replace('{token}', $this->token, $endpoint);
            $response = $this->api->request('POST', $endpoint, [
                'json' => ['chat_id' => $this->chatId, ...$data, ...$this->options]
            ]);

            if ($response->getStatusCode() !== 200)
                return null;

            $body = json_decode($response->getBody(), true);
            return $body['ok'] ? $body : null;
        } catch (GuzzleException $e) {
            Logger::exception($e, ['data' => $data]);
        }

        return null;
    }

    /**
     * set bot token
     *
     * @param string $token bot token to be used in requests
     * @return TelegramBot
     */
    public function setToken(string $token): TelegramBot
    {
        $this->token = $token;

        return $this;
    }

    /**
     * set message parse mode
     *
     * @param string $mode mode to use with message
     * @return TelegramBot
     */
    public function setParseMode(string $mode = 'text'): TelegramBot
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * set recipient chat id
     *
     * @param string $chatId recipient chat id
     * @return TelegramBot
     */
    public function setChatId(string $chatId): TelegramBot
    {
        $this->chatId = $chatId;

        return $this;
    }

    /**
     * extra options to send with the message
     *
     * @param array $options
     * @return TelegramBot
     */
    public function withOptions(array $options): TelegramBot
    {
        $this->options = $options;

        return $this;
    }

    /**
     * send an action
     *
     * @param string $action action to send
     * @return TelegramBot
     */
    public function sendAction(string $action): TelegramBot
    {
        $this->__send('action', [
            'chat_id' => $this->chatId,
            'action' => $action
        ]);

        return $this;
    }

    /**
     * send a text message
     *
     * @param string $text text message to send
     * @param bool $withAction send action
     * @return bool returns true on success, otherwise false
     */
    public function sendMessage(string $text, bool $withAction = false): bool
    {
        if ($withAction)
            $this->sendAction('typing');

        $data = $this->__send('message', [
            'text' => $text,
            'parse_mode' => $this->mode,
        ]);
        return $data && $data['ok'] == true;
    }

    /**
     * send a photo message with caption
     *
     * @param string $imageUrl the image url to send
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @return bool returns true on success, otherwise false
     */
    public function sendPhoto(string $imageUrl, string $caption = null, bool $withAction = false): bool
    {
        if ($withAction)
            $this->sendAction('upload_photo');

        $data = $this->__send('photo', [
            'photo' => $imageUrl,
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);
        return $data && $data['ok'] == true;
    }

    /**
     * send a video message with caption
     *
     * @param string $videoUrl the video url to send
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @return bool returns true on success, otherwise false
     */
    public function sendVideo(string $videoUrl, string $caption = null, bool $withAction = false): bool
    {
        if ($withAction)
            $this->sendAction('upload_video');

        $data = $this->__send('video', [
            'video' => $videoUrl,
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);
        return $data && $data['ok'] == true;
    }

    /**
     * send a document message with caption
     *
     * @param string $fileUrl
     * @param string|null $caption caption to send with image
     * @param bool $withAction send action indicator
     * @return bool returns true on success, otherwise false
     */
    public function sendDocument(string $fileUrl, string $caption = null, bool $withAction = false): bool
    {
        if ($withAction)
            $this->sendAction('upload_document');

        $data = $this->__send('document', [
            'document' => $fileUrl,
            'caption' => $caption ?? '',
            'parse_mode' => $this->mode
        ]);
        return $data && $data['ok'] == true;
    }

    /**
     * delete a message
     *
     * @param string $messageId id of message to delete
     * @return bool
     */
    public function deleteMessage(string $messageId): bool
    {
        $data = $this->__send('delete', [
            'message_id' => $messageId
        ]);
        return $data && $data['ok'] == true;
    }

}