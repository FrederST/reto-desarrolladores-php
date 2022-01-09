<?php

namespace App\Http\Controllers;

use App\Actions\Order\CheckOrderAction;
use App\Actions\Order\StorageAction;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use App\ViewModels\Order\CreateViewModel;
use App\ViewModels\Order\IndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public const SHOPPING_CART_INDEX = 'shoppingCartItems.index';

    public function index(): Response
    {
        return Inertia::render('Order/Index', (new IndexViewModel())->toArray());
    }

    public function create(): Response
    {
        return Inertia::render('Order/CreateOrEdit', (new CreateViewModel())->toArray());
    }

    public function store(StoreRequest $request, StorageAction $storageAction): RedirectResponse
    {
        $order = $storageAction->execute($request->validated(), new Order());
        return Redirect::route($order->payment_process_url);
    }

    public function checkOrder(Order $order, CheckOrderAction $checkOrderAction): Response
    {
        dd($order);
        $upOrder = $checkOrderAction->execute($order);
        return Inertia::render('Order/CreateOrEdit', ['order' => $upOrder]);
    }
}
