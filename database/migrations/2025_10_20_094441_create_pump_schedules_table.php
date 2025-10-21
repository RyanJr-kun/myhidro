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
        Schema::create('pump_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('pump_name');
            $table->time('start_time');
            $table->integer('duration_minutes');
            $table->json('days');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pump_schedules');
    }
};
