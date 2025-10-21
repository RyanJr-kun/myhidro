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
        Schema::table('pump_histories', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->renameColumn('created_at', 'start_time');
            $table->renameColumn('updated_at', 'end_time');
            $table->timestamp('end_time')->nullable()->change();

            $table->integer('duration_in_seconds')->nullable()->after('triggered_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logika untuk mengembalikan (jika diperlukan)
        Schema::table('pump_histories', function (Blueprint $table) {
            $table->enum('status', ['ON', 'OFF'])->after('pump_name');
            $table->renameColumn('start_time', 'created_at');
            $table->renameColumn('end_time', 'updated_at');
            $table->dropColumn('duration_in_seconds');
        });
    }
};
