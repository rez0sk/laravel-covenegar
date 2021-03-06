<?php

namespace Kavenegar;

use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class Kavenegar
{
    const METHOD_SEND = 'sms/send.json';
    const METHOD_SEND_ARRAY = 'sms/sendarray.json';
    const METHOD_LOOKUP = 'verify/lookup.json';

    /**
     * Http Client.
     *
     * @var Client
     */
    private $client;

    /**
     * Kavenegar constructor.
     *
     * @param string|null $api_key
     * @param Client|null $client
     */
    public function __construct(
        string $api_key = null,
        $client = null
    ) {
        $this->client = $client ?? new Client($api_key);
    }

    /**
     * Send Messages.
     *
     * @param $receptor
     * @param string $message
     * @param array  $options
     *
     * @throws Exception
     *
     * @return array
     */
    public function send($receptor, string $message, array $options = [])
    {
        if (is_string($receptor)) {
            $result =
                $this->client->request(self::METHOD_SEND, [
                    'receptor' => $receptor,
                    'message'  => $message,
                    'sender'   => Arr::get($options, 'sender'),
                    'date'     => Arr::get($options, 'date'), //TODO support Carbon
                    'type'     => Arr::get($options, 'type'),
                    'localid'  => Arr::get($options, 'local_id'),

                ]);
        } elseif (is_array($receptor)) {
            if (!Arr::has($options, 'sender')) {
                throw new Exception('Sender option (array) is required for sending message to multiple receptors.', 400);
            }
            $result =
                $this->client->request(self::METHOD_SEND_ARRAY, [
                    'receptor'       => $receptor,
                    'message'        => $message,
                    'sender'         => $options['sender'],
                    'date'           => Arr::get($options, 'date'),
                    'type'           => Arr::get($options, 'type'),
                    'LocalMessageid' => Arr::get($options, 'local_id'),
                ]);
        } else {
            throw new InvalidArgumentException('Invalid data type provided as receptor. It should be of type string or array.', 400);
        }
        return $result;
    }

    /**
     * Send lookup message.
     *
     * @param string $template
     * @param string $receptor
     * @param array  $tokens
     *
     * @throws Exceptions\KavenegarClientException
     *
     * @return array
     */
    public function lookup(string $template, string $receptor, array $tokens)
    {
        if (count($tokens) > 3 || count($tokens) < 1) {
            throw new InvalidArgumentException('Invalid number of tokens provided. Expected at least 1 and at most 3 tokens.');
        }
        $result = $this->client
            ->request(self::METHOD_LOOKUP, [
                'receptor' => $receptor,
                'template' => $template,
                'token1'   => $tokens[0],
                'token2'   => Arr::get($tokens, 1),
                'token3'   => Arr::get($tokens, 2),
            ]);

        return $result;
    }
}
