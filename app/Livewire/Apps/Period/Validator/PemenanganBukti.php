<?php

namespace App\Livewire\Apps\Period\Validator;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pemenangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PemenanganBukti extends Component
{
    use WithFileUploads;

    public ?Pemenangan $pemenangan = null;
    public $idUser;
    public $periode;

    // === Form 1 ===
    public $foto_ktp;
    public $foto_diri;

    // === Form 2 ===
    public $foto_kegiatan_1;
    public $foto_kegiatan_2;
    public $foto_surat_tugas;

    public bool $showKTP = false; // default tersembunyi

    public function toggleKTP()
    {
        $this->showKTP = !$this->showKTP;
    }

    public function mount(Request $request)
    {
        $this->idUser = $request->query('idUser');
        $this->periode = $request->query('periode');

        // Ambil data pemenangan
        $this->pemenangan = Pemenangan::with('profile', 'period', 'skema')
            ->find($this->idUser);

        // Jika tidak ditemukan, tampilkan pesan error
        if (!$this->pemenangan) {
            $this->pemenangan = new Pemenangan();
            session()->flash('error', '⚠️ Data pemenangan tidak ditemukan.');
        }
    }

    public function saveIdentitas()
    {
        if (!$this->pemenangan || !$this->pemenangan->profile) {
            session()->flash('error', 'Data profil tidak ditemukan.');
            return;
        }

        $profile = $this->pemenangan->profile;
        $updated = false;

        if ($this->foto_ktp) {
            $ktpPath = $this->foto_ktp->store("profile/{$profile->id}", 'public');
            $profile->fotoktp = $ktpPath;
            $updated = true;
        }

        if ($this->foto_diri) {
            $diriPath = $this->foto_diri->store("profile/{$profile->id}", 'public');
            $profile->fotodiri = $diriPath;
            $updated = true;
        }

        if ($updated) {
            $profile->save();
            session()->flash('message', '✅ Foto identitas berhasil diperbarui.');
        } else {
            session()->flash('error', '⚠️ Tidak ada file yang diunggah.');
        }
    }

    public function saveBukti()
    {
        if (!$this->pemenangan || !$this->pemenangan->id) {
            session()->flash('error', 'Tidak ada data pemenangan yang valid.');
            return;
        }

        // Validasi file
        $this->validate([
            'foto_kegiatan_1' => 'nullable|image|max:4096',
            'foto_kegiatan_2' => 'nullable|image|max:4096',
            'foto_surat_tugas' => 'nullable|mimes:jpg,jpeg,png,pdf|max:4096',
        ], [
            'foto_kegiatan_1.image' => 'Foto kegiatan 1 harus berupa gambar.',
            'foto_kegiatan_2.image' => 'Foto kegiatan 2 harus berupa gambar.',
            'foto_surat_tugas.mimes' => 'Surat tugas harus berupa gambar atau PDF.',
        ]);

        // Simpan file ke storage
        $pathKegiatan1 = $this->foto_kegiatan_1
            ? $this->foto_kegiatan_1->store('pemenangan/foto_kegiatan', 'public')
            : $this->pemenangan->foto_kegiatan_1;

        $pathKegiatan2 = $this->foto_kegiatan_2
            ? $this->foto_kegiatan_2->store('pemenangan/foto_kegiatan', 'public')
            : $this->pemenangan->foto_kegiatan_2;

        $pathSurat = $this->foto_surat_tugas
            ? $this->foto_surat_tugas->store('pemenangan/surat_tugas', 'public')
            : $this->pemenangan->foto_surat_tugas;

        // Update ke database
        $this->pemenangan->update([
            'foto_kegiatan_1' => $pathKegiatan1,
            'foto_kegiatan_2' => $pathKegiatan2,
            'foto_surat_tugas' => $pathSurat,
        ]);

        session()->flash('message', '✅ Bukti kegiatan berhasil diunggah.');

        // Reset input agar preview hilang
        $this->reset(['foto_kegiatan_1', 'foto_kegiatan_2', 'foto_surat_tugas']);
    }

    public function render()
    {
        return view('livewire.apps.period.validator.pemenangan-bukti');
    }
}
