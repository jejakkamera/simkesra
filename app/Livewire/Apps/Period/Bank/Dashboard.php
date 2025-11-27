<?php

namespace App\Livewire\Apps\Period\Bank;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Http\Request;
use Filament\Forms\Form;

class Dashboard extends Component
{
    public string $periode;
    public function render(Request $request)
    {
        $this->periode = $request->query('periode');
        return view('livewire.apps.period.bank.dashboard');
    }

    #[On('dashboard')]
    public function dashboard()
    {
        $this->redirectRoute(session('active_role') . '.PeriodDashboardBank',['periode'=>$this->periode]);
    }
    
    #[On('scanBarcode')]
    public function scanBarcode()
    {
        $this->redirectRoute(session('active_role') . '.PeriodScanQrcode',['periode'=>$this->periode]);
    }

    #[On('pivotFlaging')]
    public function pivotFlaging()
    {
        $this->redirectRoute(session('active_role') . '.pivotFlaging',['periode'=>$this->periode]);
    }

    #[On('datalist')]
    public function datalist()
    {
        $this->redirectRoute(session('active_role') . '.PenerimaBantuanDatalist',['periode'=>$this->periode]);
    }

    #[On('searchnik')]
    public function searchnik()
    {
        $this->redirectRoute(session('active_role') . '.PenerimaBantuanDatalist',['periode'=>$this->periode]);
    }

}
