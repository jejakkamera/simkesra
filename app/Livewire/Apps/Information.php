<?php

namespace App\Livewire\Apps;

use Livewire\Component;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use App\Models\ExcelImportLog;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Jobs\ImportExcelJob;

class Information extends Component
{
    public $school; // Properti untuk menyimpan data sekolah

    public function mount()
    {
        // dd(session()->all()); // Menampilkan semua data yang ada di session

        // Memuat data sekolah berdasarkan ID
        $this->school = School::findOrFail(1);
        // dd($this->school);
    }

    public function GenerateToken()
    {
        // Menghasilkan token baru
        $this->school->token = md5($this->school->name . time());
        $this->school->save();
    }
    public function AppsInformationEdit()
    {
        $this->redirectRoute(session('active_role') . '.AppsInformationEdit');
    }

    public function render()
    {
        return view('livewire.apps.information', ['school' => $this->school]);
    }

}