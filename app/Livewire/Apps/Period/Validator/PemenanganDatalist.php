<?php

namespace App\Livewire\Apps\Period\Validator;

use App\Models\Pemenangan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\{
    PowerGrid, PowerGridComponent, PowerGridFields, Column, Header, Footer, Exportable, Button
};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Livewire\Attributes\On;

class PemenanganDatalist extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'pemenangan.created_at';
    public string $tableName = 'pemenangan_list';
    public ?string $Period = null;

    /**
     * Source data utama
     */
    public function datasource(): Builder
    {
        $query = Pemenangan::query()
            ->join('profiles as p', 'p.id', '=', 'pemenangan.profile_id')
            ->join('bantuan as b', 'b.id', '=', 'pemenangan.idbantuan')
            ->join('periods as per', 'per.id', '=', 'pemenangan.periode')
            ->join('kelurahans as k', 'k.id_kelurahan', '=', 'p.id_kelurahan')
            ->join('wilayah_kec as wk', 'wk.id_wil', '=', 'k.id_kec')
            ->select(
                'pemenangan.id',
                'p.nama_lengkap',
                'p.nik',
                'b.judul as bantuan',
                'per.name_period as periode_nama',
                'wk.nm_wil as kecamatan',
                'k.kemendagri_kelurahan_nama as kelurahan',
                'pemenangan.no_rekening',
                'pemenangan.verif_teller',
                'pemenangan.tanggal_verif_teller',
                'pemenangan.status',
                'pemenangan.created_at',
                'pemenangan.foto_kegiatan_1',
                'pemenangan.foto_kegiatan_2',
                'pemenangan.foto_surat_tugas'
            );

        if ($this->Period) {
            $query->where('pemenangan.periode', $this->Period);
        }

        if (auth()->user()->hasRole('validator')) {
            $wilayahIds = DB::table('user_bantuan')
                ->where('user_id', auth()->id())
                ->pluck('bantuan_kelurahan_id');

            if ($wilayahIds->isNotEmpty()) {
                // $query->whereIn('k.id', $wilayahIds);
            }
        }

        return $query;
    }

    /**
     * Field mapping untuk PowerGrid
     */
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('nama_lengkap')
            ->add('nik')
            ->add('bantuan')
            ->add('periode_nama')
            ->add('kecamatan')
            ->add('kelurahan')
            ->add('bukti', function ($row) {
                $hasBukti = $row->foto_kegiatan_1 || $row->foto_kegiatan_2 || $row->foto_surat_tugas;
                return $hasBukti
                    ? '<span class="badge bg-success">Lengkap</span>'
                    : '<span class="badge bg-secondary">Belum Ada Bukti</span>';
            })
            ->add('status', function ($row) {
                return match ($row->status) {
                    'Disetujui' => '<span class="badge bg-success">Disetujui</span>',
                    'Ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
                    default     => '<span class="badge bg-warning text-dark">Diajukan</span>',
                };
            })
            ->add('created_at', fn($row) =>
                $row->created_at ? date('d M Y', strtotime($row->created_at)) : '-'
            );
    }

    /**
     * Kolom tabel
     */
    public function columns(): array
    {
        return [
            Column::action('Actions'),
            Column::make('Status', 'status')->sortable(),
            Column::make('Bukti', 'bukti'),
            Column::make('Nama Lengkap', 'nama_lengkap')->sortable()->searchable(),
            Column::make('NIK', 'nik')->sortable()->searchable(),
            Column::make('Bantuan', 'bantuan')->sortable()->searchable(),
            Column::make('Periode', 'periode_nama')->sortable(),
            Column::make('Kecamatan', 'kecamatan')->sortable(),
            Column::make('Kelurahan', 'kelurahan')->sortable(),
            Column::make('Tanggal Ajuan', 'created_at')->sortable(),
        ];
    }

    /**
     * Filter tabel
     */
    public function filters(): array
    {
        return [
            Filter::inputText('nama_lengkap')->operators(['contains']),
            Filter::inputText('nik')->operators(['contains']),
            Filter::inputText('bantuan')->operators(['contains']),
            Filter::inputText('kecamatan')->operators(['contains']),
            Filter::inputText('kelurahan')->operators(['contains']),
        ];
    }

    /**
     * Setup tabel
     */
    public function setUp(): array
    {
        $this->Period = request()->query('periode');

        return [
            Header::make()
                ->showToggleColumns()
                ->includeViewOnTop('livewire.apps.period.validator.dashboardcall'),
            Footer::make()->showPerPage()->showRecordCount(),
            Exportable::make('export')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV)
                ->striped(),
        ];
    }

    /**
     * Header button
     */
    public function header(): array
    {
        return [
            Button::add('add')
                ->slot('<i class="fas fa-plus"></i>  Ajukan Penerima')
                ->class('btn btn-primary btn-sm fw-bold')
                ->route(session('active_role') . '.PemenanganCreate', [
                    'periode' => $this->Period,
                ]),
        ];
    }

    /**
     * Tombol aksi per baris
     */
    public function actions(Pemenangan $row): array
    {
        $buttons = [
            Button::add('bukti')
                ->slot('<i class="fas fa-images"></i>')
                ->class('btn btn-xs btn-outline-warning')
                ->route(session('active_role') . '.PemenanganBukti', [
                    'idUser' => $row->id,
                    'periode' => $this->Period,
                ]),
        ];

        // tampilkan tombol hapus hanya jika status masih diajukan
        if ($row->status === 'Diajukan') {
            $buttons[] = Button::add('delete')
                ->slot('<i class="fas fa-trash-alt"></i>')
                ->class('btn btn-xs btn-outline-danger ms-1')
                ->id('delete-' . $row->id)
                ->dispatch('hapusPengajuan', ['id' => $row->id]);
        }

        return $buttons;
    }

    /**
     * Event hapus pengajuan
     */
    #[On('hapusPengajuan')]
    public function hapusPengajuan($id)
    {
        $p = Pemenangan::find($id);

        if (!$p) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        if ($p->status !== 'Diajukan') {
            session()->flash('error', 'Hanya pengajuan dengan status Diajukan yang dapat dihapus.');
            return;
        }

        $p->delete();

        session()->flash('message', '✅ Pengajuan berhasil dihapus.');
    }
}
