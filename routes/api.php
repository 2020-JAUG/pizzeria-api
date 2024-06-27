<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Helpers\CrudRoutesHelper;
use App\Http\Controllers\PizzaController;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
});


Route::group([
    'middleware' => ['jwt.verify'],
    'prefix' => 'pizzas'
], function (Router $router) {
    CrudRoutesHelper::registerApiCrudRoutes($router, PizzaController::class);
});
