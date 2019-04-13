<?php

namespace Kavenegar\Tests;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Kavenegar\Client;
use Kavenegar\Kavenegar;
use Mockery;

class KavenegarTest extends TestCase
{
    private $client;

    /**
     * Setup test and mock dependencies.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Mockery::mock(Client::class);
    }

    /**
     * @test sending message.
     * @covers \Kavenegar\Kavenegar::send
     *
     * @param $receptor
     * @param string $message
     * @param array  $options
     *
     * @throws \Exception
     * @testWith ["09123456789", "Hello!"]
     *              [["0123", "1234", "4567"], "Hello!", {"sender": "123"} ]
     */
    public function send_message($receptor, string $message, array $options = [])
    {
        $this->client
            ->expects()
            ->request(Mockery::anyOf('sms/send.json', 'sms/sendarray.json'), Mockery::subset([
                'receptor' => $receptor,
                'message'  => $message,
            ]))
            ->andReturns(['test']);
        $kavenegar = new Kavenegar('xxxxx', $this->client);
        $result = $kavenegar->send($receptor, $message, $options);
        $this->assertIsArray($result);
    }

    /**
     * @test If it throws exception when no sender array is provided
     * @covers \Kavenegar\Kavenegar::send
     *
     * Given: Multi-receptor send request issued.
     * Then: Exception thrown.
     */
    public function exception_on_send_array()
    {
        $this->expectException(\Exception::class);
        $kavenegar = new Kavenegar('xxxxx');
        $result = $kavenegar->send(['123', '456'], 'Sorry!');
        $this->assertIsArray($result);
    }

    /**
     * @test If it throws exception when wrong type provided as receptor
     * @covers \Kavenegar\Kavenegar::send
     *
     * Given: Multi-receptor send request issued.
     * Then: Exception thrown.
     */
    public function exception_on_invalid_receptor()
    {
        $this->expectException(\InvalidArgumentException::class);
        $kavenegar = new Kavenegar('xxxxx');
        $result = $kavenegar->send(123, 'Sorry!');
    }

    /**
     * @test if it can send lookup messages
     * @covers \Kavenegar\Kavenegar::lookup
     *
     * @param string $template
     * @param string $receptor
     * @param array  $tokens
     *
     * @testWith ["template", "0963258741", ["password"]]
     *
     * @throws \Kavenegar\Exceptions\KavenegarClientException
     */
    public function lookup(string $template, string $receptor, array $tokens)
    {
        $this->client
            ->shouldReceive('request')
            ->with('verify/lookup.json', Mockery::subset([
                'template' => $template,
                'receptor' => $receptor,
                'token1'   => Arr::get($tokens, 0),
                'token2'   => Arr::get($tokens, 1),
                'token3'   => Arr::get($tokens, 2),
            ]))
            ->andReturns(['test']);
        $kavenegar = new Kavenegar('xxxxx', $this->client);
        $kavenegar->lookup($template, $receptor, $tokens);
    }

    /**
     * @test If it throws exception when no token or more than three tokens provided.
     * @covers \Kavenegar\Kavenegar::lookup
     *
     * @param array $tokens
     * @testWith [ [] ]
     *              [["t1", "t2", "t3", "t4"]]
     *
     * @throws \Kavenegar\Exceptions\KavenegarClientException
     */
    public function lookup_wrong_num_of_tokens(array $tokens)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->lookup('template', '123456789', $tokens);
    }
}
