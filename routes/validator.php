<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Validator as Dashboard;
use App\Livewire\Apps\Period\Bank\Datalist as PeriodDatalistBank;
use App\Livewire\Apps\Period\Validator\ViewDashboard as ViewDashboard;
use App\Livewire\Apps\Period\Validator\PemenanganDatalist;
use App\Livewire\Apps\Period\Validator\PemenanganCreate;
use App\Livewire\Apps\Period\Validator\PemenanganBukti;
use App\Livewire\Apps\Period\Validator\ProfileCreate;
use App\Livewire\Apps\Period\Validator\PemenanganReport;

// Rute untuk dashboard pendaftar
Route::middleware(['auth', 'checkActiveRole:validator'])->group(function () {
    Route::get('/dashboard', Dashboard::class);
    Route::get('/apps/period/validator/datalist', PeriodDatalistBank::class)->name('validator.PeriodDatalistBank');
    Route::get('/apps/period/validator/ViewDashboard', ViewDashboard::class)->name('validator.PeriodDashboardBank');
    Route::get('/apps/period/validator/Pemenangan', PemenanganDatalist::class)->name('validator.PemenanganDatalist');
    Route::get('/apps/period/validator/PemenanganCreate', PemenanganCreate::class)->name('validator.PemenanganCreate');
    Route::get('/apps/period/validator/bukti/', PemenanganBukti::class)->name('validator.PemenanganBukti');
    Route::get('/apps/period/validator/report/', PemenanganReport::class)->name('validator.PemenanganReport');
    Route::get('/apps/profile/add', ProfileCreate::class)->name('validator.ProfileCreate');

});