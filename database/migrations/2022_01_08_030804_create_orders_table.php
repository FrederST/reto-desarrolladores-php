<?php

use App\Constants\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained();
            $table->enum('status', OrderStatus::STATUSES)->default(OrderStatus::STATUS_WAIT);
            $table->unsignedBigInteger('grand_total');
            $table->unsignedInteger('item_count');
            $table->string('payment_method')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->foreignId('country_id')->references('id')->on('world_countries');
            $table->foreignId('city_id')->references('id')->on('world_cities');
            $table->string('post_code');
            $table->string('phone_number');
            $table->text('notes')->nullable();
            $table->text('payment_process_id')->nullable();
            $table->text('payment_process_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
