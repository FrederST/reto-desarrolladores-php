<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('code', 10)->unique();
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('weight', 8, 2)->nullable();
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('sale_price');
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
