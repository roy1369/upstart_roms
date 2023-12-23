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
            'user_id' => 1,
            'date' => '2023-11-01',
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => '10:00:00',
            'working_address' => 'A店舗',
            'working_type' => 1,
            'end_time' => '19:00:00',
            'working_time' => '08:00:00',
            'rest_time' => '01:00:00',
            'start_station' => '猫駅',
            'end_station' => '犬駅',
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 1,
            'date' => '2023-11-02',
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => '10:00:00',
            'working_address' => 'A店舗',
            'working_type' => 1,
            'end_time' => '19:00:00',
            'working_time' => '08:00:00',
            'rest_time' => '01:00:00',
            'start_station' => '猫駅',
            'end_station' => '犬駅',
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 1,
            'date' => '2023-11-03',
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => '10:00:00',
            'working_address' => 'A店舗',
            'working_type' => 1,
            'end_time' => '19:00:00',
            'working_time' => '08:00:00',
            'rest_time' => '01:00:00',
            'start_station' => '猫駅',
            'end_station' => '犬駅',
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 1,
            'date' => '2023-11-04',
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => '10:00:00',
            'working_address' => 'A店舗',
            'working_type' => 1,
            'end_time' => '19:00:00',
            'working_time' => '08:00:00',
            'rest_time' => '01:00:00',
            'start_station' => '猫駅',
            'end_station' => '犬駅',
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 3,
            'date' => now(),
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => now(),
            'working_address' => 'A店舗',
            'working_type' => 0,
            'start_station' => '定期',
            'end_station' => '定期',
            'transportation_expenses' => 0,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 2,
            'date' => now(),
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => now(),
            'working_address' => 'A店舗',
            'working_type' => 1,
            'start_station' => '猫駅',
            'end_station' => '犬駅',
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 2,
            'date' => '2023-11-04',
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => '10:00:00',
            'working_address' => 'A店舗',
            'working_type' => 1,
            'end_time' => '22:45:00',
            'working_time' => '08:00:00',
            'rest_time' => '01:00:00',
            'over_time' => '03:45:00',
            'start_station' => '猫駅',
            'end_station' => '犬駅',
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

        $param = [
            'user_id' => 2,
            'date' => '2023-11-01',
            'start_address' => '猫県猫市猫谷町２－２２',
            'start_time' => '10:00:00',
            'working_address' => 'A店舗',
            'working_type' => 1,
            'end_time' => '20:15:00',
            'working_time' => '08:00:00',
            'rest_time' => '01:00:00',
            'over_time' => '01:15:00',
            'start_station' => '猫駅',
            'end_station' => '犬駅',
            'transportation_expenses' => 1200,
        ];

        DB::table('attendances')->insert($param);

    }
}