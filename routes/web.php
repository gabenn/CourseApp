<?php

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/edit-course/{courseid}', [ShowController::class, 'edit']);

Route::get('/add-course', [ShowController::class, 'add']);