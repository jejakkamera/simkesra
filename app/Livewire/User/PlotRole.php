<?php

namespace App\Livewire\User;

use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Http\Request ;
use App\Models\User;
use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Model_has_roles;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PlotRole extends Component implements HasForms
{
  use InteractsWithForms;
  public ?array $data = [];
   public $formTitle = 'Edit Data Staff';
    public $userId;
    public User $user;

    public function save()
    {
        $roleIds = $this->form->getState()['roles'];

        $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        if (empty($roleNames)) {
            // No valid roles found
            session()->flash('error', 'Selected roles are invalid.');
            return redirect()->back();
        }

        // Sync roles using their names
        $this->user->syncRoles($roleNames);
        session()->flash('message', 'Roles updated successfully.');
        $this->redirectRoute(session('active_role') . '.UserDatalist');
    }

     public function mount(Request $request): void
    {
        $this->userId = $request->query('UserId');
        $this->user = User::find($this->userId);
        $this->data['roles'] = $this->user->roles->pluck('id')->toArray();
        $this->form->fill([
            'roles' => $this->data['roles']
        ]);
    }

     public function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('roles')
              ->multiple()
              ->options(Role::all()->pluck('name', 'id')->toArray())
            ])
            ->statePath('data');
    }


    public function render(Request $request)
    {

        return view('livewire.edit' );
    }
}
