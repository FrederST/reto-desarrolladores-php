<?php

namespace App\Http\Controllers;

use App\Actions\Customer\CreateAction;
use App\Actions\Customer\UpdateAction;
use App\Http\Requests\CustomerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public const CUSTOMER_INDEX = 'customer.index';

    public function index(): Response
    {
        $customers = User::role('customer')->paginate(6);
        return Inertia::render('Customer/Index', ['customers' => $customers]);
    }

    public function store(CustomerRequest $request, CreateAction $createAction): RedirectResponse
    {
        $createAction->create($request->all());
        return Redirect::route(self::CUSTOMER_INDEX);
    }

    public function update(CustomerRequest $request, string $id, User $user, UpdateAction $updateAction): RedirectResponse
    {
        $updateAction->update(User::find($id), $request->all());
        return Redirect::route(self::CUSTOMER_INDEX);
    }

    public function destroy(User $user, string $id): RedirectResponse
    {
        User::find($id)->update(['banned_at' => now()]);
        return Redirect::route(self::CUSTOMER_INDEX);
    }
}
