<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Khsing\World\Models\City;
use Khsing\World\Models\Country;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'grand_total',
        'item_count',
        'payment_method',
        'first_name',
        'last_name',
        'address',
        'city_id',
        'country_id',
        'post_code',
        'phone_number',
        'notes',
        'payment_process_id',
        'payment_process_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
