<?php

namespace App\Livewire\Apps\Skema;

use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Skema;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use \Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\Registered;
use Filament\Support\RawJs;
use Filament\Forms\Components\DatePicker;

use Livewire\Component;

class Add extends Component implements HasForms
{

    use InteractsWithForms;
    public ?array $data = [];
    public $formTitle = 'Add Skema';
    public $userId;

    public function save()
    {
        // $validatedData = $this->form->getState()['data'];
        $user = Skema::create([
            'judul' => $this->data['judul'],
            'nominal' => $this->data['nominal'],
            'wilayah' => $this->data['wilayah'],
            'is_active' => $this->data['is_active'],
        ]);
        event(new Registered($user));
        // Using save() method to persist the new user
        session()->flash('message', 'Periode created successfully');  // Corrected the redirection
        $this->redirectRoute(session('active_role') . '.SkemaDatalist');
    }

    public function render()
    {
        return view('livewire.edit');
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
                        TextInput::make('judul')
                            ->name('judul')
                            ->label('Skema')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        TextInput::make('nominal')
                            ->name('nominal')
                            ->label('Nominal')
                            ->numeric()
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        TextInput::make('wilayah')
                            ->name('wilayah')
                            ->label('Wilayah')
                            ->extraAttributes(['class' => 'form-control']),
                        Select::make('is_active')->label('Status')
                            ->options(['1' => 'Open', '0' => 'Close'])
                        

                    ])
            ])->statePath('data');
    }
}

