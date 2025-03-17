<?php

namespace App\Livewire\Apps\Period\Bank;

use Livewire\Component;
use App\Models\Pemenangan;
use Illuminate\Http\Request;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use App\Models\Skema;
use App\Models\WilayahKec;
use App\Models\UserBantuan;

class ViewDashboard extends Component 
{
    public string $periode;
    public $pivotData;
    public ?array $data = [];

    public function mount(Request $request)
    {
        $this->periode = $request->query('periode');
    }


    public function render()
    {
        $this->pivotData = Pemenangan::query()
                ->join('profiles', 'profiles.id', '=', 'pemenangan.profile_id') // Join dengan tabel departments
                    ->join('wilayah_kec', 'wilayah_kec.id_wil', '=', 'profiles.kode_kecamatan') // Join dengan tabel departments       
                ->join('periods', 'periods.id', '=', 'pemenangan.periode') // Join dengan tabel departments
                ->join('bantuan', 'bantuan.id', '=', 'pemenangan.idbantuan') // Join dengan tabel departments
                ->where('periode', $this->periode)->where('id_verif_teller',auth()->user()->email)
                ->select(
                   
                    'pemenangan.no_rekening',
                    'pemenangan.jenis_rekening',
                    'pemenangan.tipe_rekening',
                    'pemenangan.id_verif_teller',
                    'pemenangan.tanggal_verif_teller',
                    'pemenangan.verif_teller',
                    
                    'profiles.nik',
                    
                    'wilayah_kec.nm_wil',
                    'bantuan.judul',
                    'bantuan.nominal',
                    
                )
                ->where('periode', $this->periode )
                ->get();
        
            $kecamatan = WilayahKec::where('id_induk_wilayah','022100')->orderby('nm_wil','ASC')->get();
            $filterIds = UserBantuan::query()->join('bantuan', 'bantuan.id', '=', 'user_bantuan.bantuan_id')
                ->where('user_id', auth()->user()->id)->get();
        return view('livewire.apps.period.bank.view-dashboard',['pemenangan' =>$this->pivotData,'kecamatan'=>$kecamatan,'filterIds'=>$filterIds]);
    }
}
