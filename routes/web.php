<?php

use Illuminate\Support\Facades\Route;


// Redirigir la raíz (/) a /admin
Route::get('/', function () {
    return redirect('/admin');
});