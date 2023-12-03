<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthlyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'user_id' => 1,
            'date' => '2023-11-01',
            'total_working_time' => '176:00:00',
            'total_over_time' => '03:45:00',
            'num_working_days' => 22,
        ];

        DB::table('monthly_reports')->insert($param);

        $param = [
            'user_id' => 2,
            'date' => '2023-11-01',
            'total_working_time' => '168:00:00',
            'total_over_time' => '00:00:00',
            'num_working_days' => 21,
        ];

        DB::table('monthly_reports')->insert($param);

    }
}
