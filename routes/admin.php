<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Admin as Dashboard;
use App\Livewire\User\Datalist as UserDatalist;
use App\Livewire\User\Add as UserCreate;
use App\Livewire\User\Edit as UserEdit;
use App\Livewire\User\PlotRole as UserPlotRole;
use App\Livewire\User\Skema\Plot as UserPlotSkema;

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
use App\Livewire\Apps\Penerima\Bantuan\FormCetak as PenerimaBantuanFormCetak;
use App\Http\Controllers\Kartu as PenerimaBantuanKartu;
use App\Http\Controllers\Kartuall as PenerimaBantuanKartuall;
use App\Http\Controllers\Validateqr as Validateqr;


// Rute untuk dashboard pendaftar
Route::middleware(['auth', 'checkActiveRole:admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class);
    Route::get('/staff/teller/datalist', UserDatalistStaff::class)->name('admin.UserDatalistStaff');
    Route::get('/staff/teller/add', UserCreateStaff::class)->name('admin.UserCreateStaff');
    Route::get('/staff/teller/edit', UserEditStaff::class)->name('admin.UserEditStaff');

    Route::get('/staff/datalist', UserDatalist::class)->name('admin.UserDatalist');
    Route::get('/staff/add', UserCreate::class)->name('admin.UserCreate');
    Route::get('/staff/edit', UserEdit::class)->name('admin.UserEdit');
    Route::get('/staff/plotrole', UserPlotRole::class)->name('admin.UserPlotRole');
    Route::get('/staff/plot/plotskema', UserPlotSkema::class)->name('admin.UserPlotSkema');

    Route::get('/apps/information', AppsInformation::class)->name('admin.AppsInformation');
    Route::get('/apps/information/edit', AppsInformationEdit::class)->name('admin.AppsInformationEdit');

    Route::get('/apps/dashboard/information/datalist', DashInformationDatalist::class)->name('admin.DashInformationDatalist');
    Route::get('/apps/dashboard/information/add', DashInformationCreate::class)->name('admin.DashInformationCreate');
    Route::get('/apps/dashboard/information/edit', DashInformationEdit::class)->name('admin.DashInformationEdit');

    Route::get('/apps/period/bank/datalist', PeriodDatalistBank::class)->name('admin.PeriodDatalistBank');
    Route::get('/apps/period/bank/dashboard', PeriodDashboardBank::class)->name('admin.PeriodDashboardBank_call');
    Route::get('/apps/period/bank/ViewDashboard', ViewDashboard::class)->name('admin.PeriodDashboardBank');
    Route::get('/apps/period/bank/scanqrcode', PeriodScanQrcode::class)->name('admin.PeriodScanQrcode');
    Route::get('/apps/period/bank/pivot', pivotFlaging::class)->name('admin.pivotFlaging');
    Route::post('/apps/qr/scan-qr', [Validateqr::class, 'idqr'])->name('admin.scanqr');
    Route::post('/apps/validasi-save/{id_pendaftar}', [Validateqr::class, 'ValidasiSave']);
    Route::post('/apps/biodata-save/{id_pendaftar}', [Validateqr::class, 'BiodataSave']);
    Route::get('/apps/qr/validate/{id_pendaftar}/{id_periode}', PeriodFlagging::class)->name('admin.PeriodFlagging');

    Route::get('/apps/period/datalist', PeriodDatalist::class)->name('admin.PeriodDatalist');
    Route::get('/apps/period/add', PeriodCreate::class)->name('admin.PeriodCreate');
    Route::get('/apps/period/edit', PeriodEdit::class)->name('admin.PeriodEdit');

    Route::get('/apps/skema/datalist', SkemaDatalist::class)->name('admin.SkemaDatalist');
    Route::get('/apps/skema/add', SkemaCreate::class)->name('admin.SkemaCreate');
    Route::get('/apps/skema/edit', SkemaEdit::class)->name('admin.SkemaEdit');

    Route::get('/apps/penerima/datalist', PenerimaDatalist::class)->name('admin.PenerimaDatalist');
    Route::get('/apps/penerima/edit', PenerimaEdit::class)->name('admin.PenerimaEdit');

    Route::get('/apps/penerima/bantuan/datalist', PenerimaBantuanDatalist::class)->name('admin.PenerimaBantuanDatalist');
    Route::get('/apps/penerima/bantuan/upload-penerima', PenerimaBantuanUploadPenerima::class)->name('admin.PenerimaBantuanUploadPenerima');
    Route::get('/apps/penerima/form/cetak', PenerimaBantuanFormCetak::class)->name('admin.PenerimaBantuanFormCetak');

    Route::get('/apps/penerima/bantuan/kartu/{UserId}', [PenerimaBantuanKartu::class, 'index'])->name('admin.PenerimaBantuanKartu');
    Route::get('/apps/penerima/bantuan/kartuall', [PenerimaBantuanKartuall::class, 'index'])->name('admin.PenerimaBantuanKartuall');
    Route::post('/apps/penerima/bantuan/tandaterima', [PenerimaBantuanKartuall::class, 'tandaterima'])->name('admin.TandaTerima');

    
});
