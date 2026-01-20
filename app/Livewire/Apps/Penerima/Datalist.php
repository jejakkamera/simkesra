<?php

namespace App\Livewire\Apps\Penerima;

use Livewire\Component;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use App\Models\Profile;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Exportable;
use \Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class Datalist extends PowerGridComponent
{

    use WithExport;
    public string $sortField = 'nama_lengkap';
    public string $tableName = 'periodelist';
    public string $sortDirection = 'desc';
    public string $primaryKey = 'profiles.id';

    public function datasource(): Builder
    {
        $query = Profile::query()
        ->join('wilayah_kec', 'wilayah_kec.id_wil', '=', 'profiles.kode_kecamatan') // Join dengan tabel departments
        ->join('kelurahans', 'kelurahans.id_kelurahan', '=', 'profiles.id_kelurahan') // Join dengan tabel departments
        ->select(
            'profiles.*',
            'profiles.id as id', 
            'wilayah_kec.nm_wil',
            'kelurahans.kemendagri_kelurahan_nama'
        );
        return $query;
    }

    public function header(): array
    {
        return [
            
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nik')
            ->add('nama_lengkap')
            ->add('tempat_lahir')
            ->add('tanggal_lahir')
            ->add('nama_ibu')
            ->add('alamat')
            ->add('kemendagri_kelurahan_nama')
            ->add('nm_wil')
            ->add('kode_pos')
            ->add('tempat_mengajar')
            ->add('Alamat_mengajar');
    }

    public function setUp(): array
    {
        $this->showCheckBox('id');

        return [
            Header::make()->showToggleColumns()->showSearchInput(), 
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
            Filter::inputText('nama_lengkap')->operators(['contains']),
            Filter::inputText('nik')->operators(['contains']),
            Filter::inputText('kemendagri_kelurahan_nama')->operators(['contains']),
            Filter::inputText('nm_wil')->operators(['contains']),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('NIK', 'nik')
                ->searchable()
                ->sortable(),
            Column::make('Nama', 'nama_lengkap')
                ->searchable()
                ->sortable(),
            Column::make('Tempat Lahir', 'tempat_lahir') // Using the custom accessor
                ->searchable(),
            Column::make('Tanggal Lahir', 'tanggal_lahir')
                ->searchable()
                ->sortable(),
            Column::make('Nama Ibu Kandung', 'nama_ibu')
                ->searchable()
                ->sortable(),
            Column::make('Alamat', 'alamat') // Using the custom accessor
                ->searchable(),
            Column::make('Desa', 'kemendagri_kelurahan_nama') // Using the custom accessor
                ->searchable(),
            Column::make('Kecamatan', 'nm_wil') // Using the custom accessor
                ->searchable(),
            Column::make('Kode Pos', 'kode_pos') // Using the custom accessor
                ->searchable(),
            Column::make('Tempat Mengajar', 'tempat_mengajar') // Using the custom accessor
                ->searchable(),
            Column::make('Alamat Mengajar', 'Alamat_mengajar') // Using the custom accessor
                ->searchable(),
            Column::action('Action'),


        ];
    }

    public function actions(Profile $row): array
    {
       
            return [
                Button::add('edit')
                    ->slot("<i class='fas fa-edit'></i>")
                    ->route(session('active_role') . '.PenerimaEdit', ['UserId' => $row->id])
                    ->class('btn btn-xs btn-outline-warning')->tooltip('Edit Record'),
            ];
       
        
    }

}

