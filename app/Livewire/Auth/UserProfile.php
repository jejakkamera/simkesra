<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request ;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;

class UserProfile extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public $formTitle = 'Edit Data profile';
    public $userId;

    public function save(){
      $this->validate([
            'data.name' => 'required|string|max:255',
            'data.password' => 'nullable|string|min:8',
        ]);

        $user = User::find($this->userId);
        if (!$user) {
            $this->addError('error', 'User not found.');
            return;
        }

        $user->name = $this->data['name'];
        if (!empty($this->data['password'])) {
            $user->password = Hash::make($this->data['password']);
        }

        $user->save();
        session()->flash('message', 'User updated successfully');
        $this->redirectRoute( 'UserProfile');
    }

    public function mount(): void
    {
        $this->userId = auth()->id();
        $user = User::find($this->userId);
        if(!$user){
            session()->flash('message', 'User updated successfully');
            $this->redirectRoute(session('active_role').".StaffDatalist");
            // return false;
        }else{
          $this->form->fill([
            'name' => $user->name,
        ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
        ->schema([
        Section::make()
            ->schema([
                TextInput::make('name')
                    ->extraAttributes(['class'=>'form-control'])
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(fn ($get) => $get('password') !== null)
                    ->revealable()
            ])
            ])
            ->statePath('data');
    }


    public function render()
    {

        return view('livewire.edit' );
    }
}
