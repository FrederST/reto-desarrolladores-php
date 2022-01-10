<?php

namespace App\Http\Controllers;

use App\Actions\Customer\StorageAction;
use App\Actions\Customer\UpdateAction;
use App\Events\CustomerCreatedOrUpdated;
use App\Http\Requests\Customer\StoreRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public const CUSTOMER_INDEX = 'customers.index';

    public function index(): Response
    {
        return Inertia::render('Customer/Index', ['customers' =>  User::role('customer')->paginate(6)]);
    }

    public function store(StoreRequest $request, StorageAction $createAction): RedirectResponse
    {
        $customer = $createAction->execute($request->validated(), new User);
        CustomerCreatedOrUpdated::dispatch($customer);
        return Redirect::route(self::CUSTOMER_INDEX);
    }

    public function update(UpdateRequest $request, User $customer, UpdateAction $updateAction): RedirectResponse
    {
        $customer = $updateAction->execute($request->validated(), $customer);
        CustomerCreatedOrUpdated::dispatch($customer, 'Customer/User Updated');
        return Redirect::route(self::CUSTOMER_INDEX);
    }

    public function destroy(User $customer): RedirectResponse
    {
        $customer->delete();
        return Redirect::route(self::CUSTOMER_INDEX);
    }

    public function disable(User $customer): RedirectResponse
    {
        $customer->update(['banned_at' => now()]);
        return Redirect::route(self::CUSTOMER_INDEX);
    }
}
