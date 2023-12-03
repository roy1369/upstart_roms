<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariousRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'user_id' => 2,
            'type' => 0,
            'result' => now(),
            'status' => 0,
            'correction_start_time' => now(),
            'correction_end_time' => now(),
            'comment' => '打刻を忘れていたため',
        ];

        DB::table('various_requests')->insert($param);

        $param = [
            'user_id' => 2,
            'type' => 2,
            'result' => now(),
            'status' => 1,
            'correction_transportation_expenses' => 1200,
            'comment' => '電車賃が変わったため',
        ];

        DB::table('various_requests')->insert($param);

        $param = [
            'user_id' => 3,
            'type' => 1,
            'result' => now(),
            'status' => 2,
            'comment' => '有給を使いたいため',
        ];

        DB::table('various_requests')->insert($param);

        $param = [
            'user_id' => 1,
            'type' => 2,
            'result' => now(),
            'status' => 1,
            'correction_transportation_expenses' => 1200,
            'comment' => '電車賃が変わったため',
        ];

        DB::table('various_requests')->insert($param);
    }
}
