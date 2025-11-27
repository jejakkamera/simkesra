<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Admin as Dashboard;
use App\Livewire\User\Datalist as UserDatalist;
use App\Livewire\User\Add as UserCreate;
use App\Livewire\User\Edit as UserEdit;
use App\Livewire\User\PlotRole as UserPlotRole;

use App\Livewire\User\Staff\Datalist as UserDatalistStaff;
use App\Livewire\User\Staff\Add as UserCreateStaff;
use App\Livewire\User\Staff\Edit as UserEditStaff;

use App\Livewire\Apps\Information as AppsInformation;
use App\Livewire\Apps\InformationEdit as AppsInformationEdit;

use App\Livewire\Apps\Dashboard\Information\Datalist as DashInformationDatalist;
use App\Livewire\Apps\Dashboard\Information\Add as DashInformationCreate;
use App\Livewire\Apps\Dashboard\Information\Edit as DashInformationEdit;

use App\Livewire\Apps\Period\Bank\Datalist as PeriodDatalistBank;
use App\Livewire\Apps\Period\Bank\Dashboard as PeriodDashboardBank;
use App\Livewire\Apps\Period\Bank\ScanQrcode as PeriodScanQrcode;
use App\Livewire\Apps\Period\Bank\Flagging as PeriodFlagging;
use App\Livewire\Apps\Period\Bank\Pivot as pivotFlaging;
use App\Livewire\Apps\Period\Bank\ViewDashboard as ViewDashboard;

use App\Livewire\Apps\Period\Datalist as PeriodDatalist;
use App\Livewire\Apps\Period\Add as PeriodCreate;
use App\Livewire\Apps\Period\Edit as PeriodEdit;

use App\Livewire\Apps\Skema\Datalist as SkemaDatalist;
use App\Livewire\Apps\Skema\Add as SkemaCreate;
use App\Livewire\Apps\Skema\Edit as SkemaEdit;

use App\Livewire\Apps\Penerima\Datalist as PenerimaDatalist;
use App\Livewire\Apps\Penerima\Edit as PenerimaEdit;

use App\Livewire\Apps\Penerima\Bantuan\Datalist as PenerimaBantuanDatalist;
use App\Livewire\Apps\Penerima\Bantuan\UploadPenerima as PenerimaBantuanUploadPenerima;
use App\Http\Controllers\Kartu as PenerimaBantuanKartu;
use App\Http\Controllers\Validateqr as Validateqr;
use App\Http\Controllers\Kartuall as PenerimaBantuanKartuall;

// Rute untuk dashboard pendaftar
Route::middleware(['auth', 'checkActiveRole:bank'])->group(function () {
    Route::get('/dashboard', Dashboard::class);
    Route::get('/staff/teller/datalist', UserDatalistStaff::class)->name('bank.UserDatalistStaff');
    Route::get('/staff/teller/add', UserCreateStaff::class)->name('bank.UserCreateStaff');
    Route::get('/staff/teller/edit', UserEditStaff::class)->name('bank.UserEditStaff');

   
    Route::get('/apps/period/bank/datalist', PeriodDatalistBank::class)->name('bank.PeriodDatalistBank');
    Route::get('/apps/period/bank/dashboard', PeriodDashboardBank::class)->name('bank.PeriodDashboardBank_call');
    Route::get('/apps/period/bank/ViewDashboard', ViewDashboard::class)->name('bank.PeriodDashboardBank');
    Route::get('/apps/period/bank/scanqrcode', PeriodScanQrcode::class)->name('bank.PeriodScanQrcode');
    Route::get('/apps/period/bank/pivot', pivotFlaging::class)->name('bank.pivotFlaging');
    Route::post('/apps/qr/scan-qr', [Validateqr::class, 'idqr'])->name('bank.scanqr');
    Route::post('/apps/validasi-save/{id_pendaftar}', [Validateqr::class, 'ValidasiSave']);
    Route::post('/apps/biodata-save/{id_pendaftar}', [Validateqr::class, 'BiodataSave']);
    Route::get('/apps/qr/validate/{id_pendaftar}/{id_periode}', PeriodFlagging::class)->name('bank.PeriodFlagging');

    Route::get('/apps/period/datalist', PeriodDatalist::class)->name('bank.PeriodDatalist');
    Route::get('/apps/period/add', PeriodCreate::class)->name('bank.PeriodCreate');
    Route::get('/apps/period/edit', PeriodEdit::class)->name('bank.PeriodEdit');

    Route::get('/apps/penerima/datalist', PenerimaDatalist::class)->name('bank.PenerimaDatalist');
    Route::get('/apps/penerima/edit', PenerimaEdit::class)->name('bank.PenerimaEdit');
    Route::get('/apps/penerima/bantuan/datalist', PenerimaBantuanDatalist::class)->name('bank.PenerimaBantuanDatalist');
    Route::get('/apps/penerima/bantuan/kartu/{UserId}', [PenerimaBantuanKartu::class, 'index'])->name('bank.PenerimaBantuanKartu');
    Route::get('/apps/penerima/bantuan/kartuall', [PenerimaBantuanKartuall::class, 'index'])->name('bank.PenerimaBantuanKartuall');
    Route::post('/apps/penerima/bantuan/tandaterima', [PenerimaBantuanKartuall::class, 'tandaterima'])->name('bank.TandaTerima');
});
