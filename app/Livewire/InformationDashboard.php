<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SchoolInformation;

class InformationDashboard extends Component
{

    public $downloads;

    public function mount()
    {
        // Ambil data dari tabel SchoolInformation dengan kondisi type = 'download'
        $this->downloads = SchoolInformation::where('type', 'download')->get();
    }

    public function render()
    {

        return view('livewire.information-dashboard', [
            'downloads' => $this->downloads
        ]);
    }
}
