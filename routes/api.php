<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'auth\login_c@login');
Route::get('logout', 'auth\login_c@logout');

Route::group(["middleware" => ["auth:api,token"], "prefix" => "v1"], function () {
    Route::get('/', function () {
        return 'access granted';
    });
    Route::get('location', 'LocationController@getLocation');
    Route::get('term', 'TermController@getTerm');
    Route::get('categories', 'CategoriesController@getCategories');
    Route::get('attributes', 'AttributesController@getAttributes');
    Route::group(["prefix" => "business"], function () {
        Route::get('search', 'BusinessController@getBusiness');
        Route::post('create', 'BusinessController@createBusiness');
        Route::put('{id}', 'BusinessController@updateBusiness');
        Route::delete('{id}', 'BusinessController@deleteBusiness');
    });
});
