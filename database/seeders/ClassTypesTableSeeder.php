<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('class_types')->delete();

        $data = [
            ['name' => 'Primary', 'code' => 'P'],
            ['name' => 'Secondary', 'code' => 'J'],
            ['name' => 'High School', 'code' => 'S'],
        ];

        DB::table('class_types')->insert($data);

    }
}
