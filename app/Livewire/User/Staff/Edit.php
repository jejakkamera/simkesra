<?php

namespace App\Livewire\User\Staff;

use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public $formTitle = 'Edit Data Staff';
    public $userId;
    public function save()
    {
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
        if ($user->role == 'siswa') {
            session()->flash('message', 'User updated successfully');
            $this->redirectRoute(session('active_role') . '.SiswaProfile', ['KeyId' => $user->student->id]);
        } else {
            session()->flash('message', 'User updated successfully');
            $this->redirectRoute(session('active_role') . '.UserDatalist');
        }
    }

    public function mount(Request $request): void
    {
        $this->userId = $request->query('UserId');
        $user = User::find($this->userId);
        if (!$user) {
            session()->flash('message', 'User updated successfully');
            $this->redirectRoute(session('active_role') . ".UserDatalist");
            // return false;
        } else {
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
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required(fn($get) => $get('password') !== null)
                            ->revealable()
                    ])
            ])
            ->statePath('data');
    }


    public function render(Request $request)
    {

        return view('livewire.edit');
    }
}
