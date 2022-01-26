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
        Word::create(['course_id'=>5,'polish'=>'pies','english'=>'dog']);
        Word::create(['course_id'=>5,'polish'=>'kot','english'=>'cat']);
        Word::create(['course_id'=>5,'polish'=>'kon','english'=>'horse']);
        Word::create(['course_id'=>5,'polish'=>'krolik','english'=>'rabbit']);
        Word::create(['course_id'=>5,'polish'=>'krowa','english'=>'cow']);
        foreach(Course::all() as $course)
            for($i = 0; $i < 8; $i++)
                Word::create(['course_id'=>$course->id,'polish'=>Str::random(6),'english'=>Str::random(6)]);
    }
}
