<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i =0; $i<10; $i++)
        {
            DB::table('users')->insert([
                'name' => "JohnDoe$i",
                'email' => "john$i@doe.fr",
                'password' => bcrypt('0000')
            ]);
        }
    }
}
