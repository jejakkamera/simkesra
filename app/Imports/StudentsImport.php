<?php

namespace App\Imports;

use App\Models\Students;
use App\Models\User;
use App\Models\Profile;
use App\Models\ExcelImportLogDetail;
use App\Models\WilayahKec;
use App\Models\Pemenangan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
ini_set('memory_limit', '2048M');
class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $importLog;
    protected $periode;
    protected $skema;

    public function __construct($importLog, $periode, $skema)
    {
        $this->importLog = $importLog;
        $this->periode = $periode;
        $this->skema = $skema;
    }

    public function model(array $row)
    {
        // DB::beginTransaction();
        
        // try {
            if (!empty($row['nik']) && !is_null($row['nama_lengkap']) && !is_null($row['nama_ibu_kandung']) &&  !is_null($row['kecamatan'])) {
               
                

                $namaKecamatan = strtolower($row['kecamatan']); // Ubah dulu ke lowercase
                $kecamatan = WilayahKec::whereRaw('LOWER(nm_wil) LIKE ?', ["%{$namaKecamatan}%"])->where('id_induk_wilayah','022100')->first();
                
                if($kecamatan){
                   
                    $profilestatus = 'exists';
                    $id = (string) Str::uuid();
                    $profile = Profile::where('nik', $row['nik'])->first();
                    
                    if ($profile) {
                        $id = $profile->id; // Ambil ID yang sudah ada
                    } else {
                        $profile = Profile::create([
                            'id' => $id,
                            'kode_kecamatan' => $kecamatan->id_wil,
                            'nik'    => $row['nik'],
                            'nama_lengkap'    => $row['nama_lengkap'],
                            'tempat_lahir'    => $row['tempat_lahir'],
                            'alamat'    => $row['alamat'],
                            'rt'    => $row['rt'],
                            'rw'    => $row['rw'],
                            'desa'    => $row['keldesa'],
                            'kode_pos'    => $row['kode_pos'],
                            'nama_ibu'    => $row['nama_ibu_kandung'],
                            'tempat_mengajar'    => $row['tempat_mengajar'],
                            'Alamat_mengajar'    => $row['alamat_lembaga'],
                            'tanggal_lahir'    => Carbon::createFromFormat('d/m/Y', $row['tanggal_bulan_tahun_lahir'])->format('Y-m-d'),

                        ]);
                        $profilestatus = 'add';
                    }
                    
                   
                    $statuspemenangan='Exist';
                    $Pemenangan = Pemenangan::with('skema')->where('profile_id', $id)->where('periode',$this->periode)->first();
                    // dd($profile->id);
                    if(!$Pemenangan){
                        
                        $pemenang=Pemenangan::create([
                            'id' => (string) Str::uuid(),
                            'profile_id' => $id,
                            'idbantuan' => $this->skema,
                            'periode' => $this->periode,
                            'no_rekening' => $row['no_rekening'],
                            // 'jenis_rekening' => $row['jenis_rekening'],
                            // 'tipe_rekening' => $row['tipe_rekening'],
                        ]);
                        $statuspemenangan='Add';
                    }
                    
                    

                    if($statuspemenangan=='Add'){
                        $status = 'success';
                        $note = 'User Register '.$status.' : ' . $row['nama_lengkap'] . ' : ' . $row['no_rekening'].'(Add Pemenangan Success)' ;
                    }else{
                        $status = 'failed';
                        $note = 'User Register  '.$status.' : ' . $row['nama_lengkap'] .'(Pemenangan Exist : '.$Pemenangan->skema->judul.')' ;
                    }
                    
                   
                    ExcelImportLogDetail::create([
                        'import_log_id' => $this->importLog,
                        // 'user_id' => $id,
                        'status' => $status,
                        'note' => $note ,
                    ]);
                    // DB::commit();
                    
                    return $profile;
                } else {    
                    ExcelImportLogDetail::create([
                        'import_log_id' => $this->importLog,
                        // 'user_id' => null,
                        'status' => 'failed',
                        'note' => 'Kecamatan Not Found: ' . json_encode($row),
                    ]);
                    return null;
                }

                    
                // } else {
                //     ExcelImportLogDetail::create([
                //         'import_log_id' => $this->importLog->id,
                //         // 'user_id' => null,
                //         'status' => 'failed',
                //         'note' => 'Exist NIK: ' . json_encode($row),
                //     ]);
                //     return null;
                // }
            } else {
                // DB::rollBack();
                // Buat log dengan status failed jika data tidak lengkap
                ExcelImportLogDetail::create([
                    'import_log_id' => $this->importLog,
                    // 'user_id' => null,
                    'status' => 'failed',
                    'note' => 'Incomplete data primary: ' . json_encode($row),
                ]);

                return null;
            }
        // } catch (\Exception $e) {
        //     // Rollback transaction jika ada kesalahan
        //     DB::rollBack();

        //     // Buat log dengan status failed dan catat error
        //     ExcelImportLogDetail::create([
        //         'import_log_id' => $this->importLog,
        //         // 'user_id' => null,
        //         'status' => 'failed',
        //         'note' => 'Error: ' . $e->getMessage(),
        //     ]);
        // }

        return null;
    }

    public function headingRow(): int
    {
        return 1; // Data dimulai dari baris ke-6
    }
}
