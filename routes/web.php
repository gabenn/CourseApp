<?php

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ShowController;
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

Route::get('/', [ShowController::class, 'courses']);

Route::get('/edit-course/{courseid}', [ShowController::class, 'edit']);

Route::get('/add-course', [ShowController::class, 'add']);

Route::get('/courses', [ShowController::class, 'courses']);

Route::get('/courses/{courseid}', [ShowController::class, 'course']);

Route::get('/words', [ShowController::class, 'words']);

Route::get('/test/{courseid}', [ShowController::class, 'test']);