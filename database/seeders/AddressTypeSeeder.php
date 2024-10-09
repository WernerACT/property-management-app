<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('address_types')->insert([
            ['name' => 'Office'],
            ['name' => 'Residential'],
        ]);
    }
}
