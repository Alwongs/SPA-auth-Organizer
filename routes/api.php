<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\PostponeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\DayController;
use App\Http\Controllers\API\PrintController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\SettingController;


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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/get-user', [AuthController::class, 'getUser']);

        Route::apiResources([
            'users' => UserController::class,
            'events' => EventController::class,
            'events/postpone' => PostponeController::class,
            'tasks' => TaskController::class,
            'companies' => CompanyController::class,
            'tests' => TestController::class,
            'days' => DayController::class,
            'cars' => CarController::class,
            'settings' => SettingController::class
        ]);
});

Route::apiResources(['days/print' => PrintController::class]);


