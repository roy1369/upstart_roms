<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param = [
            'user_id' => 1,
        ];

        DB::table('addresses')->insert($param);

        $param = [
            'user_id' => 2,
        ];

        DB::table('addresses')->insert($param);

        $param = [
            'user_id' => 3,
        ];

        DB::table('addresses')->insert($param);
    }
}
