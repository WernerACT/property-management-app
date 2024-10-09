<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AddressTypeSeeder::class,
            PropertyTypeSeeder::class,
            TenantTypeSeeder::class,
            VendorTypeSeeder::class,
            BankAccountTypeSeeder::class,
            AccountTypeSeeder::class,
            PrioritySeeder::class,
        ]);
    }
}
