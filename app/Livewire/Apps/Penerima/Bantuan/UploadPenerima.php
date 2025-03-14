<?php

namespace App\Livewire\Apps\Penerima\Bantuan;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Jobs\ImportExcelJob;
use Illuminate\Support\Facades\Auth;
use App\Models\ExcelImportLog;
use App\Models\Period;
use App\Models\Skema;
use Livewire\Attributes\On;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class UploadPenerima extends Component
{
    use WithFileUploads;
    public $excel_file;
    public $Period;
    public $skemas;
    public $periodes;
    public $periode;  // Property to store the selected periode
    public $skema;    // Property to store the selected skema

    public function mount()
    {
        $this->Period = request()->query('periode');
        $this->periodes = Period::all(); // or your query to fetch periodes
        $this->skemas = Skema::all(); // or your query to fetch skemas
    }

    public function render()
    {
        $user = Auth::user();
        $logs = ExcelImportLog::where('user_id', $user->id)
            ->join('periods', 'periods.id', '=', 'excel_import_logs.periode_id')
            ->join('bantuan', 'bantuan.id', '=', 'excel_import_logs.skema_id')
            ->orderBy('excel_import_logs.created_at', 'desc')
            ->select([
                'excel_import_logs.*', // Pastikan semua kolom dari ExcelImportLog ada
                'periods.name_period',
                'bantuan.judul'
            ])
            ->limit(10)
            ->get();

        return view('livewire.apps.penerima.bantuan.upload-penerima', compact('logs'));
    }

    public function SiswaList()
    {
        $params = [];
        if($this->Period){
            $params['periode'] = $this->Period;
        }
        $this->redirectRoute(session('active_role') . '.PenerimaBantuanDatalist', $params);
    }

    #[On('pesertaUploadProsess')]
    public function pesertaUploadProsess()
    {
       
        $this->validate([
            'excel_file' => 'required|mimes:xlsx,xls', // Validasi tipe dan ukuran file
            'periode' => 'required|exists:periods,id',
            'skema' => 'required|exists:bantuan,id',
        ]);
        $filePath = $this->excel_file->store('imports');
        // Simpan file ke storage
        
        
        $importLog = ExcelImportLog::create([
            'file_path' => $filePath,
            'status' => 'pending', // Initial status
            'user_id' => Auth::id(), // User who uploaded the file
            'periode_id' => $this->periode, // Store the selected periode
            'skema_id' => $this->skema, // Store the selected skema
        ]);
         Excel::import(new StudentsImport($importLog->id, $this->periode, $this->skema),  $importLog->file_path);
        $importLog->update(['status' => 'completed']);

        // // Dispatch job untuk memproses file di background
        // ImportExcelJob::dispatch($importLog->id, $this->periode, $this->skema); // Passing periode and skema to the job


        // Kirim pesan sukses ke sesi
        session()->flash('success', 'File is being processed in the background.');
    }

}
