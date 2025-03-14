<?php

use Illuminate\Support\Facades\Route;


// Rute untuk dashboard pendaftar
Route::middleware(['auth', 'checkActiveRole:teller'])->group(function () {
   
});
