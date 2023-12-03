<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AttendaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'user_id' => 0,
            'date' => now(),
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => now(),
            'working_address' => 0,
            'working_type' => 1,
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 2,
            'date' => now(),
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => now(),
            'working_address' => 1,
            'working_type' => 0,
            'transportation_expenses' => 0,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 1,
            'date' => now(),
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => now(),
            'working_address' => 0,
            'working_type' => 1,
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);
    }
}