<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(AttendaceSeeder::class);
        $this->call(VariousRequestSeeder::class);
        $this->call(MonthlyReportSeeder::class);
        $this->call(PaidHolidaySeeder::class);
        $this->call(AddressSeeder::class);
    }
}
