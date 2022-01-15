<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class OrderRetryException extends Exception
{
    public function render(): RedirectResponse
    {
        return redirect()->route('orders.index');
    }
}
