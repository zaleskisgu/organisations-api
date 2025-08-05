<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\BuildingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API документация - Swagger UI
Route::get('/documentation', function () {
    return view('swagger-ui');
});

// JSON документация
Route::get('/docs', function () {
    return response()->json([
        'info' => [
            'title' => 'Organizations API',
            'version' => '1.0.0',
            'description' => 'API для работы с организациями, зданиями и видами деятельности'
        ],
        'base_url' => 'http://localhost:8000/api',
        'authentication' => [
            'type' => 'API Key',
            'header' => 'X-API-Key'
        ],
        'endpoints' => [
            'GET /buildings' => 'Получить все здания',
            'GET /organizations/building/{id}' => 'Получить организации в здании',
            'GET /organizations/activity/{id}' => 'Получить организации по виду деятельности',
            'GET /organizations/{id}' => 'Получить организацию по ID',
            'GET /organizations/search/radius' => 'Поиск организаций в радиусе',
            'GET /organizations/search/area' => 'Поиск организаций в области',
            'GET /organizations/search/activity-tree/{id}' => 'Поиск по дереву деятельности',
            'GET /organizations/search/name' => 'Поиск организаций по названию'
        ]
    ]);
});

// Тестовый маршрут
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::middleware('api.key')->group(function () {
    // Buildings
    Route::get('/buildings', [BuildingController::class, 'index']);
    
    // Organizations
    Route::get('/organizations/building/{buildingId}', [OrganizationController::class, 'getByBuilding']);
    Route::get('/organizations/activity/{activityId}', [OrganizationController::class, 'getByActivity']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
    
    // Search
    Route::get('/organizations/search/radius', [OrganizationController::class, 'searchByRadius']);
    Route::get('/organizations/search/area', [OrganizationController::class, 'searchByArea']);
    Route::get('/organizations/search/activity-tree/{activityId}', [OrganizationController::class, 'searchByActivityTree']);
    Route::get('/organizations/search/name', [OrganizationController::class, 'searchByName']);
}); 