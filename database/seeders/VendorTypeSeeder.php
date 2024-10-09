<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vendor_types')->insert([
            ['name' => 'Consumer'],
            ['name' => 'Business'],
        ]);
    }
}
