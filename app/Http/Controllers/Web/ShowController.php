<?php

namespace App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Word;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use App\Http\Services\CoursesService;
use App\Http\Services\WordsService;

/**
 * Mimo małego rozmiaru, kontroler wie trochę za dużo; Gdybyśmy chcieli zmienić sposób przechowywania modeli czy dostarczania informacji,
 * kontroler musiałby ulec zmianom ponieważ wczytuje dane bezpośrednio.
 * 
 * Zamiast tego zastosowałbym strukturę Kontroler - Serwis, gdzie kontroler jest odpowiedzialny za dostarczanie danych, a serwis za zapisywanie i odczytywanie danych.
 * Kontroler nie musi, a nawet bardzo często nie powinien, być świadomy dokładnego sposobu przechowywania danych.
 * 
 * Przykładowa lektura: https://farhan.dev/tutorial/laravel-service-classes-explained/
 * 
 * Trzymanie wszystkiego w ShowController tu jest okej, bo aplikacja jest małych rozmiarów; w takim sensie, że ilość kodu nie jest na tyle duża, żeby zrobiło się chaotycznie.
 * Mimo wszystko podzieliłbym ShowController na dwa kontrolery w zależności od tego co jest głównym punktem; CourseController i WordsController.
 * 
 * Przez to że jest jeden kontroler od widoków, dodawanie kolejnych ścieżek do routera spowoduje że zrobi się tu chaotycznie, bo jeden kontroler zajmuje się wyświetlaniem wszystkich widoków.
 * 
 * Fajny jest podział kontrolerów, w takim sensie że CourseController i WordsController są pod Controllers/Api - od razu wiem czego się po nich spodziewać i co zawierają bez patrzenia na nie.
 * To samo można zrobić tutaj, poprawiłoby to czytelność.
 * 
 * - Controllers
 *  - Api
 *    - CourseController.php
 *    - WordsController.php
 *  - Web
 *   - CourseController.php
 *   - WordsController.php
 * 
 * 
 * Przykładowa lektura: https://laracasts.com/discuss/channels/laravel/seperate-controllers-for-api-and-web
 */
class ShowController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    function courses(){

        $courses = CoursesService::getCourses();
    
        return view('courses', ['courses' => $courses]);
    }

    function edit($courseId){

        $course = CoursesService::getCourseById($courseId);
        $words = WordsService::getWordsFromCourse($courseId);
        
        return view('editCourse', ['course' => $course, 'words' => $words]);
    }

    function add(){
        return view('addCourse');
    }

    function course($courseId){
        
        $course = CoursesService::getCourseById($courseId);
        $words = WordsService::getWordsFromCourse($courseId);

        return view('course', ['course' => $course, 'words' => $words]);
    }

    function words(){
        $words = WordsService::getPaginatedWords(10);
        return view('words', ['words' => $words]);
    }

    function test($courseId){
        
        $course = CoursesService::getCourseById($courseId);
        $words = WordsService::getWordsFromCourse($courseId);
        return view('test', ['course' => $course, 'words' => $words]);
    }
}
