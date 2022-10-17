<?php
namespace App\Http\Services;

use App\Http\Requests\CourseRequest;
use App\Models\Course;

class CoursesService{
    public static function getCourses(){
      return Course::all();
    }
    public static function getCourseById($course_id){
      return Course::find($course_id);
    }
    public static function getPaginatedCourses($records){
      return Course::paginate($records);
    }
    public static function createValidatedCourse(CourseRequest $request){
      return Course::create($request->validated());
    }
}
?>