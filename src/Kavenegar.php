<?php


namespace Kavenegar;


use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class Kavenegar
{

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
    )
    {
        $this->client = $client ?? new Client($api_key);
    }

    /**
     * Send Messages.
     *
     * @param $receptor
     * @param string $message
     * @param array $options
     *
     * @return \stdClass
     *
     * @throws Exception
     */
    public function send($receptor, string $message, array $options = [])
    {
        if (is_string($receptor))
            $result =
                $this->client->request('sms/sendarray.json', [
                    'receptor' => $receptor,
                    'message' => $message,
                    'sender' => Arr::get($options, 'sender'),
                    'date' => Arr::get($options, 'date'), //TODO support Carbon
                    'type' => Arr::get($options, 'type'),
                    'localid' => Arr::get($options, 'local_id'),

                ]);
        elseif (is_array($receptor)) {
            if (!Arr::has($options, 'sender'))
                throw new Exception('Sender option (array) is required for sending message to multiple receptors.', 400);

            $result =
                $this->client->request('sms/sendarray.json', [
                    'receptor' => $receptor,
                    'message' => $message,
                    'sender' => $options['sender'],
                    'date' => Arr::get($options, 'date'),
                    'type' => Arr::get($options, 'type'),
                    'LocalMessageid' => Arr::get($options, 'local_id'),
                ]);

        }

        else
            throw new InvalidArgumentException('Invalid data type provided as receptor. It should be of type string or array.', 400);

        return $result;
    }



}
