<?php

namespace App\Livewire\User;

use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Http\Request ;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use \Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;

class Add extends Component implements HasForms
{
  use InteractsWithForms;
  public ?array $data = [];
  public $formTitle = 'Add Staff User';
  public $userId;

    public function save(){
        $validatedData = $this->form->getState()['data'];
        $user = User::create([
            'id' => Str::uuid(),
            'role' => $validatedData['roles'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'email_verified_at' => now()
        ]);

        $roleIds = $validatedData['roles'];
        $roleNames = Role::where('name', $roleIds)->pluck('name')->first();
        if (!empty($roleNames)) {
            $user->syncRoles($roleNames); // Sync roles using role names
        }

        // event(new Registered($user));
        session()->flash('message', 'User created successfully');  // Corrected the redirection
        $this->redirectRoute(session('active_role') . '.UserDatalist');
    }

    public function mount(): void
    {
          $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
        ->schema([
        Section::make()
            ->schema([
                TextInput::make('email')
                    ->unique(table: 'users', column: 'email', ignoreRecord: true)
                    ->email()
                    ->extraAttributes(['class'=>'form-control'])
                    ->required(),
                TextInput::make('name')
                    ->extraAttributes(['class'=>'form-control'])
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->same('password_confirmation')
                    ->revealable(),
                TextInput::make('password_confirmation')
                    ->password()
                    ->required()
                    ->same('password')
                    ->revealable()
                    ->label('Confirm Password'),
                Select::make('roles')
                    ->options(Role::all()->pluck('name', 'name')->map(function($name) {
                        return ucwords($name);
                    })->toArray())
              ])->statePath('data')
        ]);
    }

    public function render()
    {

        return view('livewire.edit' );
    }


}
