<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/greeting', function () {
    return 'Hello World';
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken('auth_token');

    return ['token' => $token->plainTextToken];
});

// V1
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('token/check', function () {
            return 'welcome';
        });

        // Education
        Route::prefix('education')->group(function () {
            // Education List
            Route::get('list', [EducationController::class, 'index']);

            // Education Detail
            Route::get('detail/{id}', [EducationController::class, 'show']);

            // Education Add
            Route::post('add', [EducationController::class, 'store']);

            // Education Update
            Route::post('update', [EducationController::class, 'update']);

            // Education Delete
            Route::delete('delete', [EducationController::class, 'destroy']);
        });

        // Experience
        Route::prefix('experience')->group(function () {
            // Experience List
            Route::get('list', [ExperienceController::class, 'index']);

            // Experience Detail
            Route::get('detail/{id}', [ExperienceController::class, 'show']);

            // Experience Add
            Route::post('add', [ExperienceController::class, 'store']);

            // Experience Update
            Route::post('update', [ExperienceController::class, 'update']);

            // Experience Delete
            Route::delete('delete', [ExperienceController::class, 'destroy']);
        });
    });
});
