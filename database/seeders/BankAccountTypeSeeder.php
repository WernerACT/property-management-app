<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bank_account_types')->insert([
            ['name' => 'Business Cheque Account'],
            ['name' => 'Cheque Account'],
        ]);
    }
}
