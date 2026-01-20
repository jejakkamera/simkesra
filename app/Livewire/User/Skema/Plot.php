<?php

namespace App\Livewire\User\Skema;

use App\Models\User;
use App\Models\Skema;
use App\Models\UserBantuan;
use Illuminate\Http\Request;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\DB;

class Plot extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public $formTitle = 'Edit Data Staff';
    public $userId;
    public User $user;

    public function save()
    {
        $selected = $this->form->getState()['bantuan'] ?? [];

        if (empty($selected)) {
            session()->flash('error', 'Silakan pilih minimal satu bantuan.');
            return redirect()->back();
        }

        // Hapus data lama user (supaya sync)
        UserBantuan::where('user_id', $this->user->id)->delete();

        $insertData = [];
        $role = $this->user->role ?? null;

        foreach ($selected as $value) {
            if ($role === 'validator') {
                // Format validator: "bantuan_id|bantuan_kelurahan_id"
                $parts = explode('|', $value);
                $bantuanId = (int) $parts[0];
                $bantuanKelurahanId = isset($parts[1]) && $parts[1] !== '' ? (int) $parts[1] : null;

                $insertData[] = [
                    'user_id' => $this->user->id,
                    'bantuan_id' => $bantuanId,
                    'bantuan_kelurahan_id' => $bantuanKelurahanId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                // Format unit: bantuan_id saja (integer)
                $insertData[] = [
                    'user_id' => $this->user->id,
                    'bantuan_id' => (int) $value,
                    'bantuan_kelurahan_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        UserBantuan::insert($insertData);

        session()->flash('message', 'Bantuan berhasil diperbarui.');
        $this->redirectRoute(session('active_role') . '.UserDatalist');
    }


    public function mount(Request $request): void
    {
        $this->userId = $request->query('UserId');
        $this->user = User::find($this->userId);

        $role = $this->user->role ?? null;

        if ($role === 'validator') {
            // Untuk validator, load dengan format "bantuan_id|bantuan_kelurahan_id"
            $existingData = UserBantuan::where('user_id', $this->user->id)
                ->get()
                ->map(function ($item) {
                    if ($item->bantuan_kelurahan_id) {
                        return $item->bantuan_id . '|' . $item->bantuan_kelurahan_id;
                    }
                    // Bantuan nasional (tanpa kelurahan)
                    return (string) $item->bantuan_id;
                })
                ->toArray();
        } else {
            // Untuk unit, load bantuan_id saja
            $existingData = UserBantuan::where('user_id', $this->user->id)
                ->pluck('bantuan_id')
                ->toArray();
        }

        $this->data['bantuan'] = $existingData;
        $this->form->fill([
            'bantuan' => $existingData,
        ]);
    }

    public function form(Form $form): Form
    {
        $targetUser = $this->user;
        $role = $targetUser->role ?? null;

        $options = [];
        $label = 'Pilih Bantuan';

        if ($role === 'validator') {
            /**
             * Logika:
             * 1. Ambil semua bantuan yang punya relasi ke kelurahan di tabel bantuan_kelurahan
             * 2. Tambahkan juga semua bantuan yang wilayah = NULL (berlaku nasional)
             * 
             * Format key: bantuan_id|bantuan_kelurahan_id (menggunakan id dari tabel bantuan_kelurahan)
             */

            // ============================================
            // A. Bantuan dengan wilayah tertentu
            // Menggunakan bantuan_kelurahan.id sebagai key
            // ============================================
            $queryA = DB::table('bantuan as b')
                ->join('bantuan_kelurahan as bk', 'bk.bantuan_id', '=', 'b.id')
                ->join('kelurahans as k', 'k.id', '=', 'bk.kelurahan_id')
                ->join('wilayah_kec as wk', 'wk.id_wil', '=', 'k.id_kec')
                ->whereNotNull('b.wilayah')
                ->selectRaw("CONCAT(b.id,'|',bk.id) as opt_key")
                ->selectRaw("CONCAT(b.judul,' - ',wk.nm_wil,' - ',k.kemendagri_kelurahan_nama) as opt_label");

            // ============================================
            // B. Bantuan nasional (wilayah NULL)
            // Tanpa kelurahan, key = bantuan_id saja
            // ============================================
            $queryB = DB::table('bantuan as b')
                ->whereNull('b.wilayah')
                ->selectRaw("CAST(b.id AS CHAR) as opt_key")
                ->selectRaw("CONCAT(b.judul,' - Karawang') as opt_label");

            // ============================================
            // Gabungkan keduanya
            // ============================================
            $rows = $queryA->unionAll($queryB)->get();

            // Konversi ke array untuk Select Filament
            $options = $rows->pluck('opt_label', 'opt_key')->toArray();

            $label = 'Pilih Bantuan — Kecamatan — Kelurahan (Validator)';
        }

        elseif ($role === 'unit') {
            // Unit = tampil semua bantuan tanpa detail kelurahan
            $bantuans = DB::table('bantuan')->select('id', 'judul', 'wilayah')->get();
            foreach ($bantuans as $b) {
                $options[$b->id] = "{$b->judul} - " . ($b->wilayah ?? 'Semua Wilayah');
            }
            $label = 'Pilih Bantuan (Semua Wilayah) — Unit';
        }

        else {
            $label = 'Pilih Bantuan (Tidak Ada Hak Akses)';
            $options = [];
        }

        return $form
            ->schema([
                Select::make('bantuan')
                    ->label($label)
                    ->multiple()
                    ->options($options)
                    ->searchable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function render(Request $request)
    {
        if (!$this->user) {
            session()->flash('error', 'User not found.');
            $this->redirectRoute(session('active_role') . '.UserDatalist');
        }

        if (!in_array($this->user->role, ['unit', 'validator'])) {
            session()->flash('error', 'You do not have permission to access this data. Role Must "Unit" or "Validator"');
            $this->redirectRoute(session('active_role') . '.UserDatalist');
        }

        return view('livewire.edit');
    }
}
