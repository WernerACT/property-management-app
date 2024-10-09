<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tenant_types')->insert([
            ['name' => 'Consumer'],
            ['name' => 'Business'],
        ]);
    }
}
