<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\YourDataImport;
use App\Models\ExcelImportLog;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\StudentsImport;

class ImportExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $importLogId;
    protected $periode;
    protected $skema;

    /**
     * Create a new job instance.
     */
    public function __construct($importLogId, $periode, $skema)
    {
        $this->importLogId = $importLogId;
        $this->periode = $periode;
        $this->skema = $skema;
    }


    /**
     * Execute the job.
     */
    public function handle()
    {
        $importLog = ExcelImportLog::find($this->importLogId);

        try {
            // Lakukan impor data dari file Excel
            Excel::import(new StudentsImport($importLog->id, $this->periode, $this->skema),  $importLog->file_path);

            // Jika impor berhasil, ubah status menjadi completed
            $importLog->status = 'completed';
            $importLog->save();
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, catat sebagai failed
            $this->failed($e);
        }
    }

    public function failed(\Exception $exception = null)
    {
        $importLog = ExcelImportLog::find($this->importLogId);
        $importLog->status = 'failed';

        if ($exception) {
            // Menyimpan pesan kesalahan ke dalam note, jika ada
            $importLog->notes = $exception->getMessage();
        }

        $importLog->save();
    }
}
