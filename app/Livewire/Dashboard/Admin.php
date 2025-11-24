<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\DashboardInformation;

class Admin extends Component
{
    public $pictures;
    public $downloads;

    public function mount()
    {
        // Pisahkan berdasarkan type
        $this->pictures = DashboardInformation::where('type', 'show_picture')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $this->downloads = DashboardInformation::where('type', 'download')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.admin');
    }
}
