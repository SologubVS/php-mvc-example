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
     * @param int $code Exception code.
     * @param \Throwable|null $previous Previous exception.
     */
    public function __construct(int $statusCode, string $message = '', int $code = 0, ?Throwable $previous = null)
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
