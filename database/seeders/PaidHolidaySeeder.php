<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaidHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'user_id' => 1,
            'amount' => 6,
        ];

        DB::table('paid_holidays')->insert($param);

        $param = [
            'user_id' => 2,
            'amount' => 0,
        ];

        DB::table('paid_holidays')->insert($param);

        $param = [
            'user_id' => 3,
            'amount' => 11,
        ];

        DB::table('paid_holidays')->insert($param);
    }
}
