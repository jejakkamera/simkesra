<?php

namespace App\Livewire\Apps\Dashboard\Information;
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
use App\Models\DashboardInformation;
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
        $user = DashboardInformation::find($this->KeyId);
        if (!$user) {
            session()->flash('error', 'Record Not Found !');
            $this->redirectRoute(session('active_role') . ".DashInformationDatalist");
            // return false;
        } else {
            $this->form->fill([
                'description' => $user->description,
                'type' => $user->type,
                'logo' => $user->file_path,
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'data.description' => 'required|string|max:100',
            'data.type' => 'required|string|max:51',
        ]);

        $user = DashboardInformation::find($this->KeyId);
        if (!$user) {
            $this->addError('error', 'User not found.');
            return;
        }

        $user->description = $this->data['description'];
        $user->type = $this->data['type'];

        $validatedData = $this->form->getState();
        $user->file_path = $validatedData['logo'];

        $user->save();
        session()->flash('message', 'User updated successfully');
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
