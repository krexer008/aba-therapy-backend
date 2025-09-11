<?php

/*
|----------------------------
| API Routes
|----------------------------
 */


use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\TherapySessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Публичные маршруты (без аутентификации)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    // Добавить маршрут для отправки SMS-кода
    // Route::post('/send-code', [AuthController::class, 'send_code']);
});

// Защищенные маршруты (требуют токен)
Route::middleware('auth:sanctum')->group(function () {

    // Текущий пользователь
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Дети
    Route::prefix('children')->group(function () {
        Route::get('/', [ChildController::class, 'index']); // Список детей
        Route::get('/{id}', [ChildController::class, 'show']); // Профиль ребенка
        Route::get('/{id}/progress', [ChildController::class, 'progress']); // Прогресс по навыкам
        Route::get('/{id}/sessions', [ChildController::class, 'sessions']); // История занятий
        // Route::get('/{id}/program', [ChildController::class, 'program']); // Активная программа
        // Route::get('/{id}/family', [ChildController::class, 'parents']);
        // Route::post('/{id}/preferences', [ChildController::class, 'preferences']);
        // Route::get('/{id}/tms-history', [ChildController::class, 'tms_history']);
    });

    // Занятия
    Route::prefix('sessions')->group(function () {
        Route::post('/start', [TherapySessionController::class, 'start_session'])
            ->middleware('role:admin,therapist,curator');
        Route::post('/{id}/complete', [TherapySessionController::class, 'complete_session'])
            ->middleware('role:admin,therapist,curator');
        Route::get('/{id}/report', [TherapySessionController::class, 'get_session_report'])
            ->middleware('role:admin,therapist,curator');
        // Добавить маршрут для выбора стимула
        // Route::post('/{id}/select-tms', [TherapySessionController::class, 'select_tms']);
    });

    // Пробы (trials) - добавлю позже
    // Route::post('/trials', [TrialController::class, 'store']);
});
