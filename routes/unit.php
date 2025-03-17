<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Admin as Dashboard;
use App\Livewire\Apps\Penerima\Datalist as PenerimaDatalist;
use App\Livewire\Apps\Penerima\Edit as PenerimaEdit;
use App\Livewire\Apps\Period\Bank\Datalist as PeriodDatalistBank;
use App\Livewire\Apps\Period\Bank\Dashboard as PeriodDashboardBank;
use App\Livewire\Apps\Period\Bank\ViewDashboard as ViewDashboard;
use App\Livewire\Apps\Period\Bank\Pivot as pivotFlaging;
use App\Livewire\Apps\Penerima\Bantuan\Datalist as PenerimaBantuanDatalist;
use App\Http\Controllers\Kartuall as PenerimaBantuanKartuall;
use App\Http\Controllers\Kartu as PenerimaBantuanKartu;


// Rute untuk dashboard pendaftar
Route::middleware(['auth', 'checkActiveRole:unit'])->group(function () {   
    Route::get('/dashboard', Dashboard::class);
    Route::get('/apps/penerima/datalist', PenerimaDatalist::class)->name('unit.PenerimaDatalist');
    Route::get('/apps/penerima/edit', PenerimaEdit::class)->name('unit.PenerimaEdit');
    Route::get('/apps/period/unit/datalist', PeriodDatalistBank::class)->name('unit.PeriodDatalistBank');
    Route::get('/apps/period/bank/dashboard', PeriodDashboardBank::class)->name('unit.PeriodDashboardBank_call');
    Route::get('/apps/period/bank/ViewDashboard', ViewDashboard::class)->name('unit.PeriodDashboardBank');
    Route::get('/apps/period/bank/pivot', pivotFlaging::class)->name('unit.pivotFlaging');
    Route::get('/apps/penerima/bantuan/datalist', PenerimaBantuanDatalist::class)->name('unit.PenerimaBantuanDatalist');
    Route::get('/apps/penerima/bantuan/kartuall', [PenerimaBantuanKartuall::class, 'index'])->name('unit.PenerimaBantuanKartuall');
    Route::get('/apps/penerima/bantuan/kartu/{UserId}', [PenerimaBantuanKartu::class, 'index'])->name('unit.PenerimaBantuanKartu');
});
