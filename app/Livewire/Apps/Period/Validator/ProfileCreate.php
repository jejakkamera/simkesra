<?php

namespace App\Livewire\Apps\Period\Validator;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Profile;
use App\Models\Kelurahan;
use App\Models\WilayahKec;

class ProfileCreate extends Component
{
    use WithFileUploads;

    public $nama_lengkap;
    public $nik;
    public $periode;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $nama_ibu;
    public $rt;
    public $rw;
    public $kode_pos;
    public $tempat_mengajar;
    public $Alamat_mengajar;

    public $id_kec = null;
    public $id_kelurahan = null;
    public $kecamatans = [];
    public $kelurahans = [];

    public $fotoktp;
    public $fotodiri;

    protected $listeners = ['updatedIdKec'];

    public function mount(Request $request)
    {
        // Ambil NIK dari query string (?nik=...)
        $this->nik = $request->query('nik', '');
        $this->periode = $request->query('periode', '');

        // Muat daftar kecamatan
        $this->kecamatans = WilayahKec::where('id_induk_wilayah', '022100')
            ->orderBy('nm_wil', 'asc')
            ->get();
    }

    public function updatedIdKec($value)
    {
        // Update daftar kelurahan berdasarkan kecamatan terpilih
        $this->kelurahans = Kelurahan::where('id_kec', $value)
            ->orderBy('kemendagri_kelurahan_nama', 'asc')
            ->get();

        $this->id_kelurahan = null;
        $this->dispatch('$refresh');
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'id_kec' && $this->id_kec) {
            $this->updatedIdKec($this->id_kec);
        }
    }

    public function save()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:profiles,nik',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kode_pos' => 'nullable|string|max:10',
            'tempat_mengajar' => 'nullable|string|max:255',
            'Alamat_mengajar' => 'nullable|string|max:255',
            'id_kec' => 'required',
            'id_kelurahan' => 'required',
            'nama_ibu' => 'required|string|max:255',
            'fotoktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'fotodiri' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $kelurahan = Kelurahan::where('id_kelurahan', $this->id_kelurahan)->first();
        $uuid = (string) Str::uuid();

        $pathKtp = $this->fotoktp ? $this->fotoktp->store('fotoktp', 'public') : null;
        $pathDiri = $this->fotodiri ? $this->fotodiri->store('fotodiri', 'public') : null;

        Profile::create([
            'id' => $uuid,
            'nik' => $this->nik,
            'nama_lengkap' => $this->nama_lengkap,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'alamat' => $this->alamat,
            'desa' => $kelurahan ? $kelurahan->kemendagri_kelurahan_nama : null,
            'kode_kecamatan' => $this->id_kec,
            'kode_pos' => $this->kode_pos,
            'nama_ibu' => $this->nama_ibu,
            'tempat_mengajar' => $this->tempat_mengajar,
            'Alamat_mengajar' => $this->Alamat_mengajar,
            'fotoktp' => $pathKtp,
            'fotodiri' => $pathDiri,
            'id_kelurahan' => $this->id_kelurahan,
        ]);

        session()->flash('success', 'Berhasil Menambahkan Data!');
        return redirect()->route(session('active_role') . '.PemenanganCreate',['periode'=>$this->periode,'nik'=>$this->nik]);
    }

    public function render()
    {
        return view('livewire.apps.period.validator.profile-create', [
            'kecamatans' => $this->kecamatans,
            'kelurahans' => $this->kelurahans,
        ]);
    }
}
