<?php

namespace App\Livewire\User\Skema;

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
use App\Models\Skema;
use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Model_has_roles;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Plot extends Component implements HasForms
{
  use InteractsWithForms;
  public ?array $data = [];
   public $formTitle = 'Edit Data Staff';
    public $userId;
    public User $user;

    public function save()
    {
        // Ambil ID bantuan yang dipilih dari form
        $bantuanIds = $this->form->getState()['bantuan'];
    
        // Ambil nama bantuan berdasarkan ID bantuan yang dipilih
        $bantuanNames = Skema::whereIn('id', $bantuanIds)->pluck('judul')->toArray();
    
        // Jika tidak ada bantuan yang valid, tampilkan pesan error
        if (empty($bantuanNames)) {
            session()->flash('error', 'Selected bantuan are invalid.');
            return redirect()->back();
        }
    
        // Sinkronisasi bantuan yang dipilih ke user
        $this->user->bantuan()->sync($bantuanIds); // sync() untuk relasi many-to-many
    
        session()->flash('message', 'Bantuan updated successfully.');
        
        // Redirect ke rute yang diinginkan
        $this->redirectRoute(session('active_role') . '.UserDatalist');
    }
    

     public function mount(Request $request): void
    {
        $this->userId = $request->query('UserId');
        $this->user = User::find($this->userId);
        
        $this->data['bantuan'] = $this->user->bantuan->pluck('id')->toArray();
        $this->form->fill([
            'bantuan' => $this->data['bantuan']
        ]);
    }

     public function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('bantuan')
            ->multiple()
            ->options(
                Skema::all()->mapWithKeys(function ($item) {
                    // Gabungkan judul dan wilayah menjadi satu string
                    return [$item->id => $item->judul . ' - ' . ($item->wilayah ?? 'No Region')];
                })->toArray()
            )])
            ->statePath('data');
    }


    public function render(Request $request)
    {
        if (!$this->user) {
            session()->flash('error', 'User not found.');
            $this->redirectRoute(session('active_role') . '.UserDatalist');  // Ganti dengan rute yang sesuai
        }
    
        // Cek apakah role user bukan 'unit'
        if ($this->user->role !== 'unit') {
            // Jika bukan unit, redirect ke datalist atau halaman lain
            session()->flash('error', 'You do not have permission to access this data. Role Must "Unit"');
            $this->redirectRoute(session('active_role') . '.UserDatalist');  // Ganti dengan rute yang sesuai
        }
        return view('livewire.edit' );
    }
}
