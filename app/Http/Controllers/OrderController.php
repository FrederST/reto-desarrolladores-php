<?php

namespace App\Http\Controllers;

use App\Actions\Order\CheckOrderAction;
use App\Actions\Order\RetryPaymentAction;
use App\Actions\Order\StorageAction;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use App\ViewModels\Order\CreateViewModel;
use App\ViewModels\Order\IndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
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

    public function store(StoreRequest $request, StorageAction $storageAction): HttpResponse
    {
        $order = $storageAction->execute($request->validated(), new Order());
        return Inertia::location($order->payment_process_url);
    }

    public function show(Order $order, CheckOrderAction $checkOrderAction): Response
    {
        $upOrder = $checkOrderAction->execute($order)->with('orderItems.product')->first();
        return Inertia::render('Order/Info', ['order' => $upOrder]);
    }

    public function retryPayment(Order $order, RetryPaymentAction $retryPaymentAction): RedirectResponse
    {
        $upOrder = $retryPaymentAction->execute($order);
        return Redirect::to($upOrder->payment_process_url);
    }

    public function all(): Response
    {
        return Inertia::render('Order/Index', ['orders' => Order::paginate()]);
    }
}
