<?php

namespace App\Http\Controllers;

use App\Actions\Customer\UpdateAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    const CUSTOMER_INDEX = 'customer.index';

    public function index(): Response
    {
        $customers = User::role('customer')->paginate(6);
        return Inertia::render('Customer/Index', ['customers' => $customers]);
    }

    public function create(): Response
    {
        return Inertia::render('Customer/CreateOrEdit');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = User::create($request->all());
        $user->assignRole('customer');
        return Redirect::route(self::CUSTOMER_INDEX);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Customer/CreateOrEdit', ['customer' => $user]);
    }

    public function update(Request $request, string $id, User $user, UpdateAction $updateAction): RedirectResponse
    {
        $updateAction->update(User::find($id), $request->all());
        return Redirect::route(self::CUSTOMER_INDEX);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return Redirect::route(self::CUSTOMER_INDEX);
    }
}
