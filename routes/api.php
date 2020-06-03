<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'v1', 'prefix' => 'v1'], function () {
    Route::post('/login','AuthController@login');
    Route::get('/file/{dir}/{file}', 'FileController@showMe');

    Route::group(['middleware' => ['auth:api']], function(){
        Route::post('/logout','AuthController@logout');

        Route::group(['namespace' => 'Admin' ,'prefix' => 'admin', 'middleware' => 'isAdmin'], function (){
            Route::get('lecturers', 'UserController@getLecturers');
            Route::get('students', 'UserController@getStudents');
            Route::resource('user', 'UserController', ['except' => ['index', 'edit', 'update']]);

            Route::resource('course', 'CourseController', ['only' => ['index', 'store']]);
            Route::get('course/{course}', 'CourseController@show');
            Route::put('course/{course}', 'CourseController@update');
            Route::delete('course/{course}', 'CourseController@destroy');
        });

        Route::group(['namespace' => 'Lecturer' ,'prefix' => 'lecturer', 'middleware' => 'isLecturer'], function (){
            Route::resource('course', 'CourseController', ['only' => ['index']]);
            Route::get('courseMaterials/{id}', 'CourseMaterialController@getCourseMaterials');
            Route::post('courseMaterials', 'CourseMaterialController@store');
            Route::delete('courseMaterials/{id}', 'CourseMaterialController@destroy');
        });

        Route::group(['namespace' => 'Student' ,'prefix' => 'student', 'middleware' => 'isStudent'], function (){
            Route::get('myCourses', 'CourseController@getMyCourses');
            Route::post('enrollMe', 'CourseController@enrollMe');
            Route::get('courseMaterials/{id}', 'CourseController@getCourseMaterials');
        });
    });
});
