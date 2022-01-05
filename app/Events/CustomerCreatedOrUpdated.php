<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerCreatedOrUpdated
{
    use Dispatchable;
    use SerializesModels;

    private User $customer;
    private string $message;

    public function __construct(User $customer, string $message = 'Customer/User Crated')
    {
        $this->customer = $customer;
        $this->message = $message;
    }

    public function customer(): User
    {
        return $this->customer;
    }

    public function message(): string
    {
        return $this->message;
    }
}
