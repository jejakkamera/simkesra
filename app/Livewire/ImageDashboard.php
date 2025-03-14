<?php

namespace App\Livewire;

use Livewire\Component;
// use App\Models\SchoolInformation;

class ImageDashboard extends Component
{
    public $downloads;

    public function mount()
    {
        // Ambil data dari tabel SchoolInformation dengan kondisi type = 'download'
        // $this->downloads = SchoolInformation::where('type', 'show_picture')->get();
    }

    public function render()
    {
        return view('livewire.image-dashboard', [
            'downloads' => $this->downloads
        ]);
    }
}
