<?php

namespace App\Livewire\Apps\Penerima\Bantuan;

use Livewire\Component;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use App\Models\Pemenangan;
use App\Models\UserBantuan;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Exportable;
use \Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class Datalist extends PowerGridComponent
{
    
    use WithExport;
    public string $sortField = 'no_rekening';
    public string $tableName = 'penerimabantuan';
    public string $sortDirection = 'desc';
    public $Period;

    public function datasource(): Builder
    {
        
        // return Pemenangan::with('profile', 'period', 'skema')->get();

        $query = Pemenangan::query()
        ->join('profiles', 'profiles.id', '=', 'pemenangan.profile_id') // Join dengan tabel departments
            ->join('wilayah_kec', 'wilayah_kec.id_wil', '=', 'profiles.kode_kecamatan') // Join dengan tabel departments       
        ->join('periods', 'periods.id', '=', 'pemenangan.periode') // Join dengan tabel departments
        ->join('bantuan', 'bantuan.id', '=', 'pemenangan.idbantuan') // Join dengan tabel departments
         ->select(
             // Semua kolom dari tabel users
            '*', // Semua kolom dari tabel students
            'pemenangan.id as uuid'
        );

    if ($this->Period) {
        $query->where('periode', $this->Period);
    }

    if(session('active_role')=='unit'){
        $filterIds = UserBantuan::query()
            ->where('user_id', auth()->user()->id)
            ->pluck('bantuan_id') // Ambil kolom yang dipakai buat filtering
            ->toArray();

            $query->whereIn('bantuan.id', $filterIds);
    }

    return $query;

    }

    public function header(): array
    {
        if(session('active_role')=='admin'){
            return [
                Button::add('upload-pemenang')
                    ->slot("<i class='fas fa-upload'></i>")
                    ->class('btn btn-success')
                    ->dispatch('UploadPemenang', [])
                    ->tooltip('Upload Pemenang'),
                Button::add('cetak-pemenang')
                    ->slot("<i class='fas fa-print'></i>")
                    ->class('btn btn-info')
                    ->dispatch('CetakPemenang', [])
                    ->tooltip('Cetak All Kartu Tanda Pemenang Bantuan'),
            ];
        }elseif(session('active_role')=='unit' || session('active_role')=='teller' || session('active_role')=='bank'){
            return [
                Button::add('cetak-pemenang')
                    ->slot("<i class='fas fa-print'></i>")
                    ->class('btn btn-info')
                    ->dispatch('CetakPemenang', [])
                    ->tooltip('Cetak All Kartu Tanda Pemenang Bantuan'),
            ];
        }else{
            return [];
        }
    }

    #[On('UploadPemenang')]
    public function UploadPemenang()
    {
        $this->redirectRoute(session('active_role') . '.PenerimaBantuanUploadPenerima', ['periode' => $this->Period]);
    }
    
    #[On('CetakPemenang')]
    public function CetakPemenang()
    {
        $this->redirectRoute(session('active_role') . '.PenerimaBantuanKartuall', []);
    }

    public function setUp(): array
    {

        $this->Period = request()->query('periode');

        $this->persist(['sort', 'filters', 'search']);

        return [
            Header::make()->showToggleColumns()->includeViewOnTop('livewire.apps.period.bank.dashboardcall'),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
        ];
    }

    public function filters(): array
    {  
        return [
            Filter::inputText('nik')->operators(['contains']),
            Filter::inputText('nama_lengkap')->operators(['contains']),
            Filter::inputText('tempat_lahir')->operators(['contains']),
            Filter::inputText('nm_wil')->operators(['contains']),
        ];
    }

    public function columns(): array
    {
        return [
            
            Column::make('UUID', 'uuid')
                ->searchable()
                ->sortable(),
            Column::make('NIK', 'nik')
                ->searchable()
                ->sortable(),
            Column::make('Nama', 'nama_lengkap')
                ->searchable()
                ->sortable(),
            Column::make('Tempat Lahir', 'tempat_lahir')
                ->searchable()
                ->sortable(),
            Column::make('Tanggal Lahir', 'tanggal_lahir')
                ->searchable()
                ->sortable(),
            Column::make('rt', 'rt')
                ->searchable()
                ->sortable(),
            Column::make('rw', 'rw')
                ->searchable()
                ->sortable(),
            Column::make('Alamat', 'alamat')
                ->searchable()
                ->sortable(),
            Column::make('Desa', 'desa')
                ->searchable()
                ->sortable(),
            Column::make('Kecamatan', 'nm_wil')
                ->searchable()
                ->sortable(),
            Column::make('Pos', 'kode_pos')
                ->searchable()
                ->sortable(),
            Column::make('Nama Ibu', 'nama_ibu')
                ->searchable()
                ->sortable(),
            Column::make('Mengajar', 'tempat_mengajar')
                ->searchable()
                ->sortable(),
            Column::make('Alamat Mengajar', 'Alamat_mengajar')
                ->searchable()
                ->sortable(),
            Column::make('Skema', 'judul')
                ->searchable()
                ->sortable(),
            Column::make('Nomilan', 'nominal')
                ->searchable()
                ->sortable(),
            Column::make('Wilayah', 'wilayah')
                ->searchable()
                ->sortable(),
            Column::make('Periode', 'name_period')
                ->searchable()
                ->sortable(),
            Column::make('No Rek', 'no_rekening')
                ->searchable()
                ->sortable(),
            Column::make('Jenis Rek', 'jenis_rekening')
                ->searchable()
                ->sortable(),
            Column::make('Tipe Rek', 'tipe_rekening')
                ->searchable()
                ->sortable(),
            Column::make('Pencairan', 'verif_teller')
                ->searchable()
                ->sortable(),
            Column::make('Tanggal Pencairan', 'tanggal_verif_teller')
                ->searchable()
                ->sortable(),
            Column::action('Action'),
        
           
        ];
    }

    public function actions(Pemenangan $row): array
    {
        if(session('active_role')=='admin'){
            return [
                Button::add('profile')
                    ->slot("<i class='fas fa-user'></i>")
                    // ->route(session('active_role') . '.UserEdit', ['UserId' => $row->id])
                    ->class('btn btn-xs btn-outline-secondary')->tooltip('Edit Record'),
                Button::add('flaging')
                    ->slot("<i class='fas fa-handshake'></i> ")
                    ->route(session('active_role') . '.PeriodFlagging', ['id_pendaftar' => $row->uuid, 'id_periode' => $row->periode])
                    ->class('btn btn-xs btn-outline-primary')->tooltip('Print Barcode'),
                Button::add('barcode')
                    ->slot("<i class='fas fa-qrcode'></i> ")
                    ->route(session('active_role') . '.PenerimaBantuanKartu', ['UserId' => $row->uuid])
                    ->class('btn btn-xs btn-outline-info')->tooltip('Print Barcode'),
                Button::add('delete')->confirm('Are you sure you want to Delete?')
                    ->slot("<i class='fas fa-trash'></i>")
                    ->class('btn btn-xs btn-outline-danger')
                    ->dispatch('delete', ['id' => $row->uuid]),
                Button::add('unflag')->confirm('Are you sure you want to Unflag?')
                    ->slot("<i class='fa-solid fa-handshake-slash'></i>")
                    ->class('btn btn-xs btn-outline-danger')
                    ->dispatch('unflag', ['id' => $row->uuid]),
            ];
        }elseif(session('active_role')=='bank'){
            return [
                Button::add('profile')
                    ->slot("<i class='fas fa-edit'></i>")
                    // ->route(session('active_role') . '.UserEdit', ['UserId' => $row->id])
                    ->class('btn btn-xs btn-outline-secondary')->tooltip('Edit Record'),
                Button::add('flaging')
                    ->slot("<i class='fas fa-handshake'></i> ")
                    ->route(session('active_role') . '.PeriodFlagging', ['id_pendaftar' => $row->uuid, 'id_periode' => $row->periode])
                    ->class('btn btn-xs btn-outline-primary')->tooltip('Print Barcode'),
                Button::add('barcode')
                    ->slot("<i class='fas fa-qrcode'></i> ")
                    ->route(session('active_role') . '.PenerimaBantuanKartu', ['UserId' => $row->uuid])
                    ->class('btn btn-xs btn-outline-info')->tooltip('Print Barcode'),
                Button::add('unflag')->confirm('Are you sure you want to Unflag?')
                    ->slot("<i class='fa-solid fa-handshake-slash'></i>")
                    ->class('btn btn-xs btn-outline-danger')
                    ->dispatch('unflag', ['id' => $row->uuid]),
            ];
        }elseif(session('active_role')=='unit'){
            return [
                Button::add('profile')
                    ->slot("<i class='fas fa-edit'></i>")
                    // ->route(session('active_role') . '.UserEdit', ['UserId' => $row->id])
                    ->class('btn btn-xs
                    btn-outline-secondary')->tooltip('Edit Record'),
                Button::add('barcode')
                    ->slot("<i class='fas fa-qrcode'></i> ")
                    ->route(session('active_role') . '.PenerimaBantuanKartu', ['UserId' => $row->uuid])
                    ->class('btn btn-xs btn-outline-info')->tooltip('Print Barcode'),
            ];
        }elseif(session('active_role')=='teller'){
            return [
                Button::add('profile')
                    ->slot("<i class='fas fa-edit'></i>")
                    // ->route(session('active_role') . '.UserEdit', ['UserId' => $row->id])
                    ->class('btn btn-xs btn-outline-secondary')->tooltip('Edit Record'),
                Button::add('flaging')
                    ->slot("<i class='fas fa-handshake'></i> ")
                    ->route(session('active_role') . '.PeriodFlagging', ['id_pendaftar' => $row->uuid, 'id_periode' => $row->periode])
                    ->class('btn btn-xs btn-outline-primary')->tooltip('Print Barcode'),
                Button::add('barcode')
                    ->slot("<i class='fas fa-qrcode'></i> ")
                    ->route(session('active_role') . '.PenerimaBantuanKartu', ['UserId' => $row->uuid])
                    ->class('btn btn-xs btn-outline-info')->tooltip('Print Barcode')
            ];
        }
        
    }

    #[On('delete')]
    public function delete($id): void
    {
        Pemenangan::find($id)->delete();
        session()->flash('message', 'User Delete successfully');
        $this->redirectRoute(session('active_role') . '.PenerimaBantuanDatalist');
    }

    #[On('unflag')]
    public function unflag($id): void
    {
        Pemenangan::find($id)->update([
            'verif_teller' => null,
            'tanggal_verif_teller' => null,  // atau bisa menggunakan format tanggal yang sesuai
            'id_verif_teller' => null,  // atau bisa menggunakan format tanggal yang sesuai
        ]);

        Log::info("Pembaruan data pemenangan dengan ID {$id} berhasil.", [
            'verif_teller' => null,
            'tanggal_verif_teller' => now(),
            'user_id' => auth()->user()->email, // Misalnya Anda ingin mencatat siapa yang melakukan perubahan
            'updated_at' => now(),
        ]);

        session()->flash('message', 'User Unflag successfully');
        $this->redirectRoute(session('active_role') . '.PenerimaBantuanDatalist',['periode' => $this->Period]);
    }
   


}

