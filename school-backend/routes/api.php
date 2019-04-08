<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/test', 'API\TestController');
Route::apiResource('/classes', 'API\ClassController');
Route::get('/classes/byYear/{year}', 'API\ClassController@getByYear');
Route::get('/classes/{id}/students', 'API\ClassController@getStudents');

Route::apiResource('/students', 'API\StudentController');
Route::get('/students/byCity/{city}', 'API\StudentController@getByCity');