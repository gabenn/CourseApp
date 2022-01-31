<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Word;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $animals = [
            ['course_id'=>1,'polish'=>'pies','english'=>'dog'],
            ['course_id'=>1,'polish'=>'kot','english'=>'cat'],
            ['course_id'=>1,'polish'=>'koń','english'=>'horse'],
            ['course_id'=>1,'polish'=>'królik','english'=>'rabbit'],
            ['course_id'=>1,'polish'=>'krowa','english'=>'cow'],
            ['course_id'=>1,'polish'=>'wiewiórka','english'=>'squirrel'],
            ['course_id'=>1,'polish'=>'niedźwiedź','english'=>'bear'],
            ['course_id'=>1,'polish'=>'wąż','english'=>'snake'],
        ];
        Word::insert($animals);

        $professions = [
            ['course_id'=>2,'polish'=>'górnik','english'=>'miner'],
            ['course_id'=>2,'polish'=>'mechanik','english'=>'mechanic'],
            ['course_id'=>2,'polish'=>'inżynier','english'=>'engineer'],
            ['course_id'=>2,'polish'=>'piekarz','english'=>'baker'],
            ['course_id'=>2,'polish'=>'policjant','english'=>'policeman'],
            ['course_id'=>2,'polish'=>'nauczyciel','english'=>'teacher'],
            ['course_id'=>2,'polish'=>'sędzia','english'=>'judge']
        ];
        Word::insert($professions);
    }
}
