<?php

use Illuminate\Support\Facades\Route;


// Rute untuk dashboard pendaftar
Route::middleware(['auth', 'checkActiveRole:unit'])->group(function () {
 
});
