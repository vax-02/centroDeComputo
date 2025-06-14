<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class subjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("subjects")->insert([
            [
                "title"=>"FISICA I",
                "name"=> "FIS 1100",
                "paralelo" => "A",
                "user_id" => 1,
            ],
            [
                "title"=>"FISICA I",
                "name"=> "FIS 1100",
                "paralelo" => "B",
                "user_id" => 1,
            ],
            [
                "title"=>"FISICA I",
                "name"=> "FIS 1100",
                "paralelo" => "C",
                "user_id"=> 1,
            ],
            
            [
                "title"=>"FISICA I",
                "name"=> "FIS 1100",
                "paralelo" => "D",
                "user_id"=> 1,
            ],
        ]);
    }
}
