<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\User;
class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([[
            "name"=> "Nombre docente",
            "lastname" => "Apellido Docente",
            "user" => "doc111",
            "password" => Hash::make( "doc111"),
            "rol" => 1, //docente
        ],
        [
            "name"=> "alumno 1",
            "lastname" => "apellido 1",
            "user" => "alu222",
            "password" => Hash::make( "alu222"),
            "rol"=> 2, //alumno
        ]]);

        $faker = Faker::create();
        
        for($i = 0; $i <= 200;$i++){
            User::create([
                'name' => substr( $faker->name , 0 , 25),
                'lastname' => substr( $faker->lastName(),0,25),
                'user' => $faker->unique()->userName(),
                'password' => Hash::make( $faker->userName()),
                "rol" => 2
            ]);
        }
    
    }
}
