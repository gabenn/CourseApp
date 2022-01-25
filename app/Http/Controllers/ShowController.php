<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Word;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class ShowController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    function courses(){

        $courses = Course::all();
    
        return view('courses', ['courses' => $courses]);
    }

    function edit($courseId){

        $course = Course::find($courseId);
    
        return view('editCourse', ['course' => $course]);
    }

    function add(){
        return view('addCourse');
    }

    function course($courseId){

        $course = Course::find($courseId);
        $words = Word::all()->where('course_id',$courseId);
        return view('course', ['course' => $course, 'words' => $words]);
    }

    function words(){
        $words = Word::all();
        return view('words', ['words' => $words]);
    }
}
