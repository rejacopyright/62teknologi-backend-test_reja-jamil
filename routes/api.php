<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'auth\login_c@login');
Route::get('logout', 'auth\login_c@logout');

Route::group(["middleware" => ["auth:api,token"]], function () {
    Route::get('/', function () {
        return 'access granted';
    });
});
// Route::group(["middleware" => "auth:token", "prefix" => "v1"], function () {
//     Route::get('/', function () {
//         return 'Home V1';
//     });
// });
