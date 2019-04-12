<?php


namespace Kavenegar\Tests;


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
     *
     * @param array $options
     * @throws \Exception
     * @testWith ["09123456789", "Hello!"]
     *              [["0123", "1234", "4567"], "Hello!", {"sender": "123"} ]
     *
     */
    public function send_message($receptor, string $message, array $options = [])
    {
        $this->client
            ->shouldReceive('request')
            ->with('sms/sendarray.json', Mockery::subset([
                'receptor' => $receptor,
                'message' => $message
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
     *
     */
    public function exception_on_send_array()
    {
        $this->expectException(\Exception::class);
        $this->send_message(['123', '1234'], 'Hello!');
    }

    /**
     * @test If it throws exception when wrong type provided as receptor
     * @covers \Kavenegar\Kavenegar::send
     *
     * Given: Multi-receptor send request issued.
     * Then: Exception thrown.
     *
     */
    public function exception_on_invalid_receptor()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->send_message(123, 'Hello!');
    }
}