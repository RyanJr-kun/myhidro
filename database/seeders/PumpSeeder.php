<?php

namespace Database\Seeders;

use App\Models\sistem_control\Pump; // Asumsi model Pump berada di namespace ini
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PumpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data yang ada untuk menghindari duplikasi saat seeder dijalankan ulang
        DB::table('pumps')->truncate();

        $pumps = [
            ['name' => 'pompa hidroponik', 'status' => '0'],
            ['name' => 'pompa kolam', 'status' => '0'],
            ['name' => 'pompa pembuangan', 'status' => '0'],
        ];

        foreach ($pumps as $pump) {
            Pump::create($pump);
        }
    }
}
