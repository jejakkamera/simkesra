<?php

namespace App\Livewire\Apps\Period;


use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Period;
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
    public $formTitle = 'Add Periode';
    public $userId;

    public function save()
    {
        // $validatedData = $this->form->getState()['data'];
        $user = Period::create([
            'name_period' => $this->data['name_period'],
            'start_date' => $this->data['start_date'],
            'end_date' => $this->data['end_date'],
            'validate_date' => $this->data['validate_date'],
            'is_active' => false,

        ]);
        event(new Registered($user));
        // Using save() method to persist the new user
        session()->flash('message', 'Periode created successfully');  // Corrected the redirection
        $this->redirectRoute(session('active_role') . '.PeriodDatalist');
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
                        TextInput::make('name_period')
                            ->name('periode')
                            ->label('Periode/Year')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Start Date')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        DatePicker::make('end_date')->after('start_date')
                            ->label('End Date')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        DatePicker::make('validate_date')->after('end_date')->rules(['after:end_date'])
                            ->label('Validate Date')
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),

                    ])
            ])->statePath('data');
    }
}

