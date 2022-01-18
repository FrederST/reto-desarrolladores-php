<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class ImportProductException extends Exception
{
    public function __construct(string $message = 'Error Import Products')
    {
        $this->message = $message;
    }

    public function render(): RedirectResponse
    {
        return redirect()->route('products.index')->with('message', $this->message);
    }
}
