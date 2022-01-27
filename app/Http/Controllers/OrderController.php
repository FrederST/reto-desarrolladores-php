<?php

namespace App\Http\Controllers;

use App\Actions\Order\CheckOrderAction;
use App\Actions\Order\RetryPaymentAction;
use App\Actions\Order\StorageAction;
use App\Events\OrderCreatedOrUpdated;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Resources\OrderResource;
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

    public function __construct()
    {
        $this->middleware('ensure_order_is_from_current_user', ['only' => ['show', 'retryPayment']]);
    }

    public function index(IndexViewModel $indexViewModel): Response
    {
        $orders = Order::filter(request()->input('filter', []))->paginate();
        return Inertia::render('Order/Index', $indexViewModel->collection(OrderResource::collection($orders)));
    }

    public function create(): Response
    {
        return Inertia::render('Order/CreateOrEdit', (new CreateViewModel())->toArray());
    }

    public function store(StoreRequest $request, StorageAction $storageAction): HttpResponse
    {
        $order = $storageAction->execute($request->validated(), new Order());
        OrderCreatedOrUpdated::dispatch($order);
        return Inertia::location($order->payment_process_url);
    }

    public function show(Order $order, CheckOrderAction $checkOrderAction): Response
    {
        $upOrder = $checkOrderAction->execute($order)->with('orderItems.product')->find($order->id);
        return Inertia::render('Order/Info', ['order' => new OrderResource($upOrder)]);
    }

    public function retryPayment(Order $order, RetryPaymentAction $retryPaymentAction): RedirectResponse
    {
        $dbOrder = $retryPaymentAction->execute($order);
        OrderCreatedOrUpdated::dispatch($dbOrder, 'Order Retry');
        return Redirect::to($order->payment_process_url);
    }

    public function all(): Response
    {
        return Inertia::render('Order/Index', ['orders' => OrderResource::collection(Order::paginate())]);
    }
}
