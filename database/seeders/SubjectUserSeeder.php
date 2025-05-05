<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class SubjectUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //201 ESTUDIANTES
        $inicio = 2;
        $fin = 50;
        for($i = 1; $i < 5;$i++){ //ID DE MATERIAS
            for($j = $inicio; $j <= $fin; $j++){ //ID ESTUDIANTES
                DB::table('subjects_users')->insert([
                    'subject_id' => $i,
                    'user_id' => $j,
                    'semestre' => 'I/25'
                ]);
            }
            $inicio = $fin+1;
            $fin+=50;
        }
/*
        2 50
        51 101
        102 152
        153 203*/
    }
}
