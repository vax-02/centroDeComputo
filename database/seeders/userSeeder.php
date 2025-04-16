<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;
class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([[
            "name"=> "docente 1",
            "lastname" => "apellido 1",
            "user" => "doc111",
            "password" => Hash::make( "doc111"),
            "rol" => 1, //docente
        ],
        [
            "name"=> "docente 2",
            "lastname" => "apellido 2",
            "user" => "doc222",
            "password" => Hash::make( "doc222"),
            "rol" => 1, //docente
        ],[
            "name"=> "alumno 1",
            "lastname" => "apellido 1",
            "user" => "alu222",
            "password" => Hash::make( "alu222"),
            "rol"=> 2, //alumno
        ]]);
    
    }
}
