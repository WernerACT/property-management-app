<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('account_types')->insert([
            ['name' => 'Vendor'],
            ['name' => 'Tenant'],
            ['name' => 'ManagingAgent'],
        ]);
    }
}
