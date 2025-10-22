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
        Schema::create('tanamen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->integer('jumlah_benih')->unsigned();
            $table->dateTime('tanggal_tanam');
            $table->integer('estimasi_panen_hari')->unsigned();
            $table->dateTime('tanggal_panen_aktual')->nullable();
            $table->enum('status', ['ditanam', 'dipanen', 'gagal'])->default('ditanam');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanamans');
    }
};
