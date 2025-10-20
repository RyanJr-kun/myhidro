<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
     public function run(): void
    {
        Role::firstOrCreate(['nama' => 'admin']);
        Role::firstOrCreate(['nama' => 'pengawas']);
    }
}
