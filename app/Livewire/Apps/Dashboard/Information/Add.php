<?php

namespace App\Livewire\Apps\Dashboard\Information;

use Livewire\Component;
use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\DashboardInformation;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use \Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;
use Filament\Support\RawJs;
use Filament\Forms\Components\DatePicker;

class Add extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public $formTitle = 'Add Informasi';
    public $userId;

    public function render()
    {
        return view('livewire.edit');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function save()
    {
        $this->validate([
            'data.description' => 'required|string|max:100',
            'data.type' => 'required|string|max:51',
        ]);
        // $validatedData = $this->form->getState()['data'];
        $validatedData = $this->form->getState();
        $logo = $validatedData['logo'];
        $user = DashboardInformation::create([
            'description' => $this->data['description'],
            'type' => $this->data['type'],
            'file_path' => $logo,

        ]);
        event(new Registered($user));
        // Using save() method to persist the new user
        session()->flash('message', 'Information created successfully');  // Corrected the redirection
        $this->redirectRoute(session('active_role') . '.DashInformationDatalist');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('description')
                            ->label('Deskripsi')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        Select::make('type')->label('Type')
                            ->options(['download' => 'File Download', 'show_picture' => 'Gambar']),
                        FileUpload::make('logo')
                            ->disk('public')
                            ->required()
                            ->label('Foto (jpg,jpeg,png,pdf,docx,doc)')
                            ->rules('required', 'image', 'mimes:jpg,jpeg,png,pdf,docx,doc'),

                    ])
            ])->statePath('data');
    }
}
