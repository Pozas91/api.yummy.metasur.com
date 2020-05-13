<?php

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

Route::namespace('Api')->group(function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/user', 'AuthController@user');

        Route::apiResource('tags', 'TagController', ['only' => 'index']);

        Route::get('/recipes/pdf', 'RecipeController@pdf');
        Route::apiResources([
            'recipes' => 'RecipeController'
        ]);
    });
});


