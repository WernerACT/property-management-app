<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('property_types')->insert([
            ['name' => 'Office'],
            ['name' => 'House'],
            ['name' => 'Townhouse'],
            ['name' => 'Sectional Title'],
            ['name' => 'Flat'],
        ]);
    }
}
