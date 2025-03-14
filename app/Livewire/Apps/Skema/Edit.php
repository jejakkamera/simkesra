<?php

namespace App\Livewire\Apps\Skema;

use Livewire\Component;
use App\Livewire\Forms\PostForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Skema;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;
use Filament\Support\RawJs;
use Filament\Forms\Components\DatePicker;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public $formTitle = 'Edit Data Jurusan';
    public $KeyId;

    public function render(Request $request)
    {
        return view('livewire.edit');
    }

    public function mount(Request $request): void
    {
        $this->KeyId = $request->query('KeyId');
        $user = Skema::where('id',$this->KeyId)->first();
        if (!$user) {
            session()->flash('error', 'Record Not Found !');
            $this->redirectRoute(session('active_role') . ".SkemaDatalist");
            // return false;
        } else {
            $this->form->fill([
                'judul' => $user->judul,
                'nominal' => $user->nominal,
                'wilayah' => $user->wilayah,
                'is_active' => $user->is_active,
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'data.judul' => 'required|string|max:100',
            'data.nominal' => 'required|max:51',
            'data.is_active' => 'required|max:51',
        ]);

        $user = Skema::find($this->KeyId);
        if (!$user) {
            $this->addError('error', 'User not found.');
            return;
        }

        $user->judul = $this->data['judul'];
        $user->nominal = $this->data['nominal'];
        $user->is_active = $this->data['is_active'];
        $user->wilayah = $this->data['wilayah'];

        $user->save();
        session()->flash('message', 'User updated successfully');
        $this->redirectRoute(session('active_role') . '.SkemaDatalist');
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
                            ->extraAttributes(['class' => 'form-control'])
                            ->required(),
                        Select::make('is_active')->label('Status')
                            ->options(['1' => 'Open', '0' => 'Close'])

                    ])
            ])->statePath('data');
    }
}