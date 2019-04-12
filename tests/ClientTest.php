<?php


namespace Kavenegar\Tests;


use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as Guzzle;
use Kavenegar\Client;
use stdClass;


class ClientTest extends TestCase
{
    /**
     * @test self check
     * @coversNothing
     *
     * @return void
     */
    public function self_check()
    {
        $this->assertTrue(true);
    }

    /**
     * @test if request method works appropriately with successful response
     * @covers \Kaphp venegar\Client
     *
     * @return void
     * @throws \Kavenegar\Exceptions\KavenegarClientException
     */
    public function request_gets_valid_response()
    {
        $response = file_get_contents(__DIR__.'/responses/sample_response.json');

        $mock = new MockHandler([
            new Response(200, [], $response)
        ]);
        $handler = HandlerStack::create($mock);
        $guzzle = new Guzzle(['base_uri' => 'http://example.com', 'handler' => $handler]);

        $client = new Client('xxxxxxxxxx', $guzzle);
        $result = $client->request('dummy/method.json', []);

        $this->assertIsArray($result);

    }

    /**
     *
     */
}
