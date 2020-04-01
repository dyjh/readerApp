<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Response;

class TeaException extends Exception implements Responsable
{
    /**
     * @var int
     */
    protected $statusCode;

    public function __construct(int $errorCode, string $message = null, int $statusCode = 200)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message ?? trans("error.{$errorCode}"), $errorCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function toResponse($request)
    {
        return Response::json([
            'status'   => $this->getCode(),
            'message' => $this->getMessage(),
        ], $this->getStatusCode());
    }
}
