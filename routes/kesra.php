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

// Rute untuk dashboard pendaftar
Route::middleware(['auth', 'checkActiveRole:kesra'])->group(function () {
    Route::get('/dashboard', Dashboard::class);
    Route::get('/staff/teller/datalist', UserDatalistStaff::class)->name('kesra.UserDatalistStaff');
    Route::get('/staff/teller/add', UserCreateStaff::class)->name('kesra.UserCreateStaff');
    Route::get('/staff/teller/edit', UserEditStaff::class)->name('kesra.UserEditStaff');

    Route::get('/staff/datalist', UserDatalist::class)->name('kesra.UserDatalist');
    Route::get('/staff/add', UserCreate::class)->name('kesra.UserCreate');
    Route::get('/staff/edit', UserEdit::class)->name('kesra.UserEdit');
    Route::get('/staff/plotrole', UserPlotRole::class)->name('kesra.UserPlotRole');

    Route::get('/apps/information', AppsInformation::class)->name('kesra.AppsInformation');
    Route::get('/apps/information/edit', AppsInformationEdit::class)->name('kesra.AppsInformationEdit');

    Route::get('/apps/dashboard/information/datalist', DashInformationDatalist::class)->name('kesra.DashInformationDatalist');
    Route::get('/apps/dashboard/information/add', DashInformationCreate::class)->name('kesra.DashInformationCreate');
    Route::get('/apps/dashboard/information/edit', DashInformationEdit::class)->name('kesra.DashInformationEdit');

    Route::get('/apps/period/bank/datalist', PeriodDatalistBank::class)->name('kesra.PeriodDatalistBank');
    Route::get('/apps/period/bank/dashboard', PeriodDashboardBank::class)->name('kesra.PeriodDashboardBank_call');
    Route::get('/apps/period/bank/ViewDashboard', ViewDashboard::class)->name('kesra.PeriodDashboardBank');
    Route::get('/apps/period/bank/scanqrcode', PeriodScanQrcode::class)->name('kesra.PeriodScanQrcode');
    Route::get('/apps/period/bank/pivot', pivotFlaging::class)->name('kesra.pivotFlaging');
    Route::post('/apps/qr/scan-qr', [Validateqr::class, 'idqr'])->name('kesra.scanqr');
    Route::post('/apps/validasi-save/{id_pendaftar}', [Validateqr::class, 'ValidasiSave']);
    Route::post('/apps/biodata-save/{id_pendaftar}', [Validateqr::class, 'BiodataSave']);
    Route::get('/apps/qr/validate/{id_pendaftar}/{id_periode}', PeriodFlagging::class)->name('kesra.PeriodFlagging');

    Route::get('/apps/period/datalist', PeriodDatalist::class)->name('kesra.PeriodDatalist');
    Route::get('/apps/period/add', PeriodCreate::class)->name('kesra.PeriodCreate');
    Route::get('/apps/period/edit', PeriodEdit::class)->name('kesra.PeriodEdit');

    Route::get('/apps/skema/datalist', SkemaDatalist::class)->name('kesra.SkemaDatalist');
    Route::get('/apps/skema/add', SkemaCreate::class)->name('kesra.SkemaCreate');
    Route::get('/apps/skema/edit', SkemaEdit::class)->name('kesra.SkemaEdit');

    Route::get('/apps/penerima/datalist', PenerimaDatalist::class)->name('kesra.PenerimaDatalist');
    Route::get('/apps/penerima/edit', PenerimaEdit::class)->name('kesra.PenerimaEdit');

    Route::get('/apps/penerima/bantuan/datalist', PenerimaBantuanDatalist::class)->name('kesra.PenerimaBantuanDatalist');
    Route::get('/apps/penerima/bantuan/upload-penerima', PenerimaBantuanUploadPenerima::class)->name('kesra.PenerimaBantuanUploadPenerima');

    Route::get('/apps/penerima/bantuan/kartu/{UserId}', [PenerimaBantuanKartu::class, 'index'])->name('kesra.PenerimaBantuanKartu');
});
