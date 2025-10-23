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
        Schema::create('ikans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ikan');
            $table->integer('jumlah_bibit')->unsigned();
            $table->dateTime('tanggal_tebar');
            $table->integer('estimasi_panen_hari')->unsigned();
            $table->dateTime('tanggal_panen_aktual')->nullable();
            $table->enum('status', ['ditebar', 'dipanen', 'gagal'])->default('ditebar');
            $table->integer('pekan_interval_jam')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ikans');
    }
};
