<?php

namespace App\Livewire\Apps;

use Livewire\Component;
use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolType;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class InformationEdit extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public $formTitle = 'Profile Sekolah';

    public function render()
    {
        return view('livewire.edit');
    }

    public function save()
    {
        $this->validate([
            'data.name' => 'required|string|max:51',
            'data.address' => 'required|string|max:51',
            'data.phone_number' => 'required|string|max:19',
        ]);

        $user = School::find(1);
        if (!$user) {
            $this->addError('error', 'User not found.');
            return;
        }

        $user->name = $this->data['name'];
        $user->address = $this->data['address'];
        $user->phone_number = $this->data['phone_number'];

        $validatedData = $this->form->getState();
        $user->logo = $validatedData['logo'];

        // Simpan data ke relasi

        $user->save();
        session(['school_logo' => $validatedData['logo']]);

        session()->flash('message', 'User updated successfully');
        $this->redirectRoute(session('active_role') . '.AppsInformation');
    }

    public function mount(Request $request): void
    {
        $user = School::findOrFail(1);

        if (!$user) {
            session()->flash('message', 'Warning : Data Not Found');
            $this->redirectRoute(session('active_role') . ".SekolahProfile");
            // return false;
        } else {
            
            $this->form->fill([
                'name' => $user->name,
                'address' => $user->address,
                'phone_number' => $user->phone_number,
                'logo' => $user->logo,
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')->label('Nama Sekolah')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required()
                            ->maxLength(50),
                        TextInput::make('address')->label('Alamat Sekolah')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required()
                            ->maxLength(50),
                        TextInput::make('phone_number')->label('Nomor Telepon')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required()
                            ->maxLength(18),
                        FileUpload::make('logo')
                            ->disk('public')
                            ->required()
                            ->label('Foto (jpg,jpeg,png)')
                            ->rules('required', 'image', 'mimes:jpg,jpeg,png'),

                    ])
            ])
            ->statePath('data');
    }
}
