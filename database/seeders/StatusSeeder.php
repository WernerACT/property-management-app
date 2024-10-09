<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('statuses')->insert([
            ['name' => 'Requested'],
            ['name' => 'In Progress'],
            ['name' => 'In Review'],
            ['name' => 'Declined'],
            ['name' => 'Approved'],
        ]);
    }
}
