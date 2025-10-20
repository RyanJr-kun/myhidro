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
        Schema::table('users', function (Blueprint $table) {
            $table->string('organisasi')->nullable()->after('email');
            $table->string('nomer_telepon')->nullable()->after('organisasi');
            $table->string('alamat')->nullable()->after('nomer_telepon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['organisasi', 'nomer_telepon', 'alamat']);
        });
    }
};
