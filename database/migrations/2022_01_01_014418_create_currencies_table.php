<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->tinyInteger('minor_unit')->nullable();
            $table->string('alphabetic_code', 3)->unique();
            $table->string('numeric_code', 3)->unique();
            $table->string('symbol', 3)->default('$');
            $table->timestamp('disable_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
}
