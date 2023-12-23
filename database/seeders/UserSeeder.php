<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'name' => 'テストユーザー',
            'name_kana' => 'てすとゆーざー',
            'email' => 'admin@example.com',
            'email_verified_at' => DB::raw('CURRENT_TIMESTAMP'),
            'password' => Hash::make('admin123'),
            'joining_date' => now(),
            'authority' => 1,
            'full_time_authority' => 1,
            'transportation_expenses_flag' => 1,
        ];

        DB::table('users')->insert($param);

        $param = [
            'name' => '社員A',
            'name_kana' => 'しゃいんA',
            'email' => 'sample1@sample.com',
            'email_verified_at' => DB::raw('CURRENT_TIMESTAMP'),
            'password' => Hash::make('password'),
            'joining_date' => now(),
            'authority' => 0,
            'full_time_authority' => 1,
            'transportation_expenses_flag' => 1,
        ];

        DB::table('users')->insert($param);

        $param = [
            'name' => '猫',
            'name_kana' => 'ねこB',
            'email' => 'sample2@sample.com',
            'email_verified_at' => DB::raw('CURRENT_TIMESTAMP'),
            'password' => Hash::make('password'),
            'joining_date' => now(),
            'authority' => 0,
            'full_time_authority' => 0,
            'transportation_expenses_flag' => 0,
        ];

        DB::table('users')->insert($param);

    }
}
