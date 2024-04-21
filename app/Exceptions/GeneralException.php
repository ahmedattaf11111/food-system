<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class GeneralException extends Exception
{
    protected $message;
    private $status;
    public function __construct($message, $status = 400)
    {
        $this->message = $message;
        $this->status = $status;
    }
    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        return response($this->message);
    }
}
