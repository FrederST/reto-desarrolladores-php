<?php

use App\Constants\ReportStatus;
use App\Constants\ReportTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ReportTypes::TYPES);
            $table->enum('status', ReportStatus::STATUSES);
            $table->text('info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
}
