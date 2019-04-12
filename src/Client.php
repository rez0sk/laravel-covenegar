<?php


namespace Kavenegar;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Kavenegar\Exceptions\KavenegarClientException;

class Client
{
    /**
     * Http client.
     *
     * @var HttpClient
     */
    private $http;

    /**
     * Client constructor.
     *
     * @param string|null $api_token
     * @param HttpClient|null $httpClient
     */
    public function __construct(
        string $api_token = null,
        HttpClient $httpClient = null
    )
    {
        $this->http = $httpClient ?? new HttpClient([ 'base_uri' => 'https://api.kavenegar.com/v1/' . $api_token ]);
    }


    /**
     * Make requests.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return array
     *
     * @throws KavenegarClientException
     */
    public function request(string $method, array $parameters)
    {
        try {
            $response =
                $this->http->post($method, [
                    'form_params' => $parameters
                ]);

            $result = json_decode($response->getBody());
            return $result->entries;
        } catch (ClientException $exception) {
            throw new KavenegarClientException($exception);
        } //TODO handle other types of exception

    }


}
