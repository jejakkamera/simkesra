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
        set_time_limit(600);
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

        if ($exception instanceof \Exception || $exception instanceof \Error) {
            // Log exception atau error yang terjadi
            \Log::error('ImportExcelJob failed', [
                'exception' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        } else {
            \Log::error('ImportExcelJob failed with unknown error');
        }
    
        // Anda bisa melakukan aksi tambahan di sini, misalnya mengupdate status di database
        $importLog = ExcelImportLog::find($this->importLogId);
        if ($importLog) {
            $importLog->update(['status' => 'failed']);
        }
    }
}
