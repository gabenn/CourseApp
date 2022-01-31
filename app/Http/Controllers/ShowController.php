<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class ShowController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    function edit($courseId){

        $course = Course::find($courseId);
    
        return view('editCourse', ['course', $course]);
    }
    function add(){
        return view('addCourse');
    }
}
