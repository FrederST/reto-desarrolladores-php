<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeightUnitIdAndCurrencyIdToProductsTable extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('weight_unit_id')->constrained();
            $table->foreignId('currency_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->removeColumn('weight_unit_id');
            $table->removeColumn('weight_unit_id');
        });
    }
}
