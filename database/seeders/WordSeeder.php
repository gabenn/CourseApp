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
        foreach(Course::all() as $course)
            for($i = 0; $i < 8; $i++)
                Word::create(['course_id'=>$course->id,'polish'=>Str::random(6),'english'=>Str::random(6)]);
    }
}
