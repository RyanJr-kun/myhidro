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
        Schema::table('tanamen', function (Blueprint $table) {
            $table->integer('pupuk_interval_hari')->unsigned()->nullable()->after('catatan');
            $table->integer('air_interval_hari')->unsigned()->nullable()->after('pupuk_interval_hari');
            $table->date('terakhir_pupuk')->nullable()->after('air_interval_hari');
            $table->date('terakhir_air')->nullable()->after('terakhir_pupuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanamen', function (Blueprint $table) {
            //
        });
    }
};
