<?php

namespace App\Livewire\Apps\Period\Validator;

use Livewire\Component;
use Livewire\WithFileUploads;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Pemenangan;
use App\Models\Profile;
use App\Models\UserBantuan;

class PemenanganCreate extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use WithFileUploads;

    public ?array $data = [];
    public ?string $periode = null;
    public ?Profile $selectedProfile = null;
    public bool $isNewProfile = false;
    public ?string $nikMessage = null;
    public bool $periodeAktif = true;
    public ?string $periodeMessage = null;
    public bool $showCreateProfileModal = false;
    public $ifnik;

    public $fotoKtp;

    public function mount(): void
    {
        $this->periode = request()->query('periode');
        $this->ifnik = request()->query('nik', '');

        if ($this->periode) {
            $periode = \App\Models\Period::find($this->periode);
            if ($periode) {
                $today = now()->startOfDay();
                if (!$periode->is_active || $today->gt(\Carbon\Carbon::parse($periode->validate_date))) {
                    $this->periodeAktif = false;
                    $this->periodeMessage = '⛔ Pengajuan sudah ditutup untuk periode ini.';
                }
            } else {
                $this->periodeAktif = false;
                $this->periodeMessage = '❌ Periode tidak ditemukan.';
            }
        } else {
            $this->periodeAktif = false;
            $this->periodeMessage = '⚠️ Tidak ada periode aktif saat ini.';
        }

        // 🟢 Tambahan ini:
        if (!empty($this->ifnik)) {
            // set langsung ke field form
            $this->data['nik'] = $this->ifnik;

            // jalankan fungsi pengecekan NIK
            $this->checkNIK($this->ifnik);
        }
    }

    /**
     * Jalankan setiap kali field input berubah
     */
    public function updatedData($value, $key)
    {
        if ($key === 'nik') {
            $this->checkNIK($value);
        }
    }

    /**
     * Validasi dan cari NIK di database
     */
    private function checkNIK($value)
    {
        $nik = preg_replace('/\D/', '', $value);

        // Jika NIK kosong → reset semua data profil
        if (empty($nik)) {
            $this->selectedProfile = null;
            $this->isNewProfile = false;
            $this->showCreateProfileModal = false;
            $this->nikMessage = null;
            return;
        }

        // Pastikan panjang NIK = 16 digit angka
        if (!preg_match('/^\d{16}$/', $nik)) {
            $this->selectedProfile = null;
            $this->isNewProfile = false;
            $this->showCreateProfileModal = false;
            $this->nikMessage = '⚠️ NIK harus terdiri dari 16 digit angka.';
            return;
        }

        // Cari di database
        $this->selectedProfile = Profile::with('kecamatan')->where('nik', $nik)->first();

        if ($this->selectedProfile) {
            $this->isNewProfile = false;
            $this->nikMessage = '✅ Data penerima ditemukan.';
            $this->showCreateProfileModal = false;
        } else {
            $this->isNewProfile = true;
            $this->nikMessage = 'ℹ️ NIK belum terdaftar. Silakan isi data penerima baru.';
            $this->showCreateProfileModal = true;
        }
    }

    /**
     * Upload foto KTP → kirim ke OCR server (n8n)
     */
    public function updatedFotoKtp()
    {
        if (!$this->fotoKtp) return;

        try {
            // Simpan file sementara
            $filename = 'ktp_scan_' . now()->timestamp . '.' . $this->fotoKtp->getClientOriginalExtension();
            $path = $this->fotoKtp->storeAs('ktp_temp', $filename, 'public');
            $filePath = storage_path("app/public/{$path}");

            if (!file_exists($filePath)) {
                $this->nikMessage = '❌ File tidak ditemukan di server.';
                return;
            }

            // Kirim ke server OCR (n8n)
            $response = Http::timeout(60)
                ->attach('file', fopen($filePath, 'r'), basename($filePath))
                ->post('https://n8n-r3dgg4qq.n8x.biz.id/webhook/1c9e436a-fe25-45d9-96d6-840dd343ee78');

            if ($response->failed()) {
                $this->nikMessage = '❌ Gagal menghubungi server OCR (status ' . $response->status() . ').';
                return;
            }

            $result = $response->json();
            $data = $result['output'] ?? $result;
            \Log::info('OCR Response', $data);

            // Isi hasil OCR
            $this->data['nik'] = $data['nik'] ?? '';
            $this->data['nama_lengkap'] = $data['nama'] ?? '';
            $this->data['jenis_kelamin'] = $data['jenis_kelamin'] ?? '';
            $this->data['agama'] = $data['agama'] ?? '';
            $this->data['status_perkawinan'] = $data['status_perkawinan'] ?? '';

            // Validasi NIK hasil OCR
            if (!empty($data['nik']) && preg_match('/^\d{16}$/', $data['nik'])) {
                $this->checkNIK($data['nik']);
                $this->nikMessage = '✅ NIK berhasil dibaca dari foto: ' . $data['nik'];
            } else {
                $this->selectedProfile = null;
                $this->isNewProfile = false;
                $this->nikMessage = '⚠️ Tidak ditemukan NIK valid dalam gambar.';
            }

            // Hapus file setelah diproses
            dispatch(function () use ($filePath) {
                sleep(1);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            });

        } catch (\Throwable $th) {
            \Log::error('OCR Error', ['message' => $th->getMessage()]);
            $this->nikMessage = '❌ Gagal OCR: ' . $th->getMessage();
        }
    }

    /**
     * Buat form input
     */
    public function form(Form $form): Form
    {
        $bantuanOptions = UserBantuan::query()
            ->join('bantuan', 'bantuan.id', '=', 'user_bantuan.bantuan_id')
            ->leftJoin('kelurahans as k', 'k.id', '=', 'user_bantuan.bantuan_kelurahan_id')
            ->where('user_id', auth()->user()->id)
            ->select('bantuan.id as id', 'bantuan.judul', 'bantuan.wilayah', 'k.kemendagri_kelurahan_nama')
            ->get()
            ->mapWithKeys(fn($b) => [
                $b->id => "{$b->judul} - " . ($b->kemendagri_kelurahan_nama ?? $b->wilayah ?? 'Semua Wilayah')
            ])
            ->toArray();

        return $form
            ->schema([
                Forms\Components\Section::make('Input Pengajuan Penerima')
                    ->schema([
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK Penerima')
                            ->reactive()
                            ->debounce(600)
                            ->maxLength(16)
                            ->helperText('Masukkan NIK penerima (16 digit) atau gunakan tombol Scan KTP.')
                            ->disabled(!$this->periodeAktif)
                            ->required(),

                        Forms\Components\Select::make('idbantuan')
                            ->label('Pilih Skema Bantuan')
                            ->options($bantuanOptions)
                            ->disabled(!$this->periodeAktif)
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Simpan data pengajuan
     */
    public function save()
    {
        if (!$this->periodeAktif) {
            session()->flash('error', $this->periodeMessage);
            return;
        }

        $state = $this->form->getState();

        // Cegah jika NIK tidak valid
        $nik = $state['nik'] ?? null;
        if (empty($nik) || !preg_match('/^\d{16}$/', $nik)) {
            session()->flash('error', '⚠️ NIK harus terdiri dari 16 digit angka.');
            return;
        }

        DB::beginTransaction();

        try {
            if (!$this->selectedProfile) {
                session()->flash('error', '⚠️ NIK belum valid. Silakan isi profil baru.');
                DB::rollBack();
                return;
            }

            $exists = Pemenangan::where('profile_id', $this->selectedProfile->id)
                ->where('idbantuan', $state['idbantuan'])
                ->where('periode', $this->periode)
                ->exists();

            if ($exists) {
                session()->flash('error', '⚠️ Penerima ini sudah diajukan pada periode ini.');
                DB::rollBack();
                return;
            }

            $pemenangan = Pemenangan::create([
                'id' => Str::uuid(),
                'profile_id' => $this->selectedProfile->id,
                'idbantuan' => $state['idbantuan'],
                'periode' => $this->periode,
                'verif_teller' => '-',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            session()->flash('message', '✅ Pengajuan berhasil disimpan.');
            return $this->redirectRoute(session('active_role') . '.PemenanganBukti', [
                'idUser' => $pemenangan->id,
                'periode' => $this->periode,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', '❌ Gagal menyimpan: ' . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.apps.period.validator.pemenangan-create', [
            'periodeAktif' => $this->periodeAktif,
            'periodeMessage' => $this->periodeMessage,
        ]);
    }
}
