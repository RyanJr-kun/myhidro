<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pump_histories', function (Blueprint $table) {
            $table->id();
            $table->string('pump_name');
            $table->enum('triggered_by', ['Manual','Otomatis']);
            $table->enum('status', ['ON', 'OFF']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pump_histories');
    }
};
