<?php

namespace Core\Exception;

use RuntimeException;
use Throwable;

class HttpException extends RuntimeException
{
    /**
     * HTTP status code.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Create a new HTTP exception.
     *
     * @param int $statusCode HTTP status code.
     * @param string $message Exception message to throw.
     * @param \Throwable|null $previous Previous exception.
     * @param int $code Exception code.
     */
    public function __construct(int $statusCode, string $message = '', ?Throwable $previous = null, int $code = 0)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get HTTP exception status code.
     *
     * @return int HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
