<?php

declare(strict_types=1);

namespace Hunternnm\LaravelRoadrunner\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DumpException extends Exception
{
    /**
     * DumpException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = Response::HTTP_INTERNAL_SERVER_ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        return response($this->getMessage());
    }
}
