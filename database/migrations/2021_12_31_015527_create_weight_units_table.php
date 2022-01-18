<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightUnitsTable extends Migration
{
    public function up(): void
    {
        Schema::create('weight_units', function (Blueprint $table) {
            $table->id();
            $table->string('weight_unit_name', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weight_units');
    }
}
