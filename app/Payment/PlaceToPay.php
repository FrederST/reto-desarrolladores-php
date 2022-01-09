<?php

namespace App\Payment;

use App\Constants\OrderStatus;
use App\Helpers\CurrencyHelper;
use App\Models\Order;
use App\Payment\Contracts\PaymentContract;
use Illuminate\Support\Facades\Http;

class PlaceToPay implements PaymentContract
{

    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function makePayment(Order $order): string
    {
        $request = $this->createAuth() + $this->createRequest($order);
        $response =  Http::post($this->config['api_url'] . 'api/session', $request);
        if ($response->ok() && $this->validateSessionStatus($response->json())) {
            $data = $response->json();
            $order->payment_process_id = $data['requestId'];
            $order->payment_process_url = $data['processUrl'];
            $order->save();
            return $data['processUrl'];
        }
    }

    public function checkStatus(Order $order): void
    {
        $statusInfo = $this->getStatus($order);
        $currenStatus = $statusInfo['status']['status'];

        if ($currenStatus == 'APPROVED') {
            $order->status = OrderStatus::STATUS_APPROVED;
        } elseif ($currenStatus == 'REJECTED') {
            $order->status = OrderStatus::STATUS_REJECTED;
        }

        $order->save();
    }

    public function getStatus(Order $order): array
    {
        $response = Http::get($this->config['api_url'] . 'api/session/' . $order->payment_process_id);
        if ($response->ok()) {
            return $response->json();
        }
    }


    private function createAuth(): array
    {
        $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        $seed = date('c');
        return [
            'auth' => [
                'login' => $this->config['login'],
                'tranKey'=> base64_encode(sha1($nonce . $seed . $this->config['secretkey'], true)),
                'nonce' => base64_encode($nonce),
                'seed' => $seed,
            ]
        ];
    }

    private function createRequest(Order $order): array
    {
        return [
            'buyer' => [
                'name' => $order->first_name,
                'surname' => $order->last_name,
                'email' => auth()->user()->email,
                'mobile' => $order->phone_number,
                'address' => [
                    'city' => $order->city->name,
                    'postalCode' => $order->post_code,
                    'country' => $order->country->code,
                    'phone' => $order->phone_number,
                ],
            ],
            'payment' => [
                'reference' => $order->id,
                'description' => 'purchase for ' . $order->item_count . ' items',
                'amount' => [
                    'currency' => CurrencyHelper::getDefaultCurrency()->alphabetic_code,
                    'total' => CurrencyHelper::toCurrencyFormat($order->grand_total),
                ],
            ],
            'ipAddress' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'expiration' => date('c', strtotime('+1 hour')),
            'returnUrl' => route('orders.index'),
            'skipResult' => false,
            'noBuyerFill' => false,
            'captureAddress' => false,
        ];
    }

    private function validateSessionStatus(array $response): bool
    {
        return $response['status']['status'] == 'OK';
    }
}
