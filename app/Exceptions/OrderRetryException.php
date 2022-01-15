<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class OrderRetryException extends Exception
{
    public function __construct(string $message = 'Retry No Permitted Please wait.')
    {
        $this->message = $message;
    }

    public function render(): RedirectResponse
    {
        return redirect()->route('orders.index')->with('message', $this->message);
    }
}
