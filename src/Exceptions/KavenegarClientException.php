<?php

namespace Kavenegar\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Throwable;

class KavenegarClientException extends Exception
{
    public function __construct(ClientException $clientException, Throwable $previous = null)
    {
        $message = json_decode($clientException->getResponse()->getBody())->return->message;
        $code = $clientException->getResponse()->getStatusCode();
        parent::__construct($message, $code, $previous);
    }
}
