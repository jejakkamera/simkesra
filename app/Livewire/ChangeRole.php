<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChangeRole extends Component
{
   public $roles;

   public function changeRoleSelected($role)
    {
        $user = Auth::user();

        if (in_array($role, $this->roles)) {
            session(['active_role' => $role]);
            return redirect('/')->with('status', 'Role switched to ' . $role);
        } else {
            return redirect('/')->withErrors(['error' => 'You do not have this role']);
        }
    }

    public function mount()
    {
        // $this->roles = Auth::user()->roles;
        $user = Auth::user();

        // Ambil role dari kolom role di tabel users
        $baseRole = $user->role;

        // Ambil role dari spatie/laravel-permission
        $spatieRoles = $user->roles->pluck('name')->toArray();

        // Gabungkan kedua sumber role
        $this->roles = array_unique(array_merge([$baseRole], $spatieRoles));
    }

    public function render()
    {
        return view('livewire.change-role', [
            'roles' => $this->roles,
        ]);
    }
}
