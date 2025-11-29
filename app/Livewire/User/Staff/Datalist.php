<?php

namespace App\Livewire\User\Staff;


use Livewire\Component;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use App\Models\User;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Exportable;
use \Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class Datalist extends PowerGridComponent
{

    use WithExport;
    public string $sortField = 'name';


    public function datasource(): Builder
    {
        // return User::where('role', 'staff');
        return User::with('roles')->where('role', 'teller');
    }

    public function header(): array
    {
        return [
            Button::add('add-staff')
                ->slot("<i class='fas fa-plus'></i>")
                ->class('btn btn-lg btn-primary')
                ->route(session('active_role') . '.UserCreateStaff', []),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name');
    }

    public function setUp(): array
    {
        return [
            Header::make()->showToggleColumns()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

        ];
    }
    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),
            Column::make('Email', 'email')
                ->searchable()
                ->sortable(),
            Column::make('Roles', 'roles_list') // Using the custom accessor
                ->searchable(),
            Column::make('Created At', 'created_at')
                ->searchable()
                ->sortable(),
            Column::action('Action'),


        ];
    }

    public function actions(User $row): array
    {
        $actions = [
            Button::add('edit')
                ->slot("<i class='fas fa-edit'></i>")
                ->route(session('active_role') . '.UserEditStaff', ['UserId' => $row->id])
                ->class('btn btn-xs btn-outline-warning')
                ->tooltip('Edit Record'),
            Button::add('delete')
                ->slot("<i class='fas fa-trash'></i>")
                ->confirm('Are you sure?')
                ->class('btn btn-xs btn-outline-danger')
                ->dispatch('delete', ['id' => $row->id]),
        ];

        if (Auth::check() && session('active_role') === 'admin') {
            $actions[] = Button::add('login-as')
                ->slot("<i class='ti ti-user-check'></i>")
                ->class('btn btn-xs btn-outline-primary')
                ->tooltip('Login sebagai user ini')
                ->dispatch('login-as', ['id' => (string)$row->id]);
        }

        return $actions;
    }

    #[On('delete')]
    public function delete($id): void
    {
        User::find($id)->delete();
        session()->flash('message', 'User Delete successfully');
        $this->redirectRoute(session('active_role') . '.UserDatalistStaff');
    }

    #[On('login-as')]
    public function loginAs($id): void
    {
        if (!$id || !Auth::check()) {
            session()->flash('error', 'ID pengguna tidak valid');
            return;
        }

        if (session('active_role') !== 'admin') {
            abort(403, 'Hanya admin yang diperbolehkan melakukan login sebagai user lain.');
        }

        if ((int) Auth::id() === (int) $id) {
            session()->flash('message', 'Anda sudah menggunakan akun ini.');
            return;
        }

        $targetUser = User::findOrFail($id);

        session([
            'impersonator_id' => Auth::id(),
            'impersonator_name' => Auth::user()->name,
            'impersonator_role' => session('active_role'),
        ]);

        Auth::login($targetUser);
        session(['active_role' => $targetUser->role ?? session('active_role')]);

        $role = $targetUser->role ?? session('active_role');
        $roleSlug = trim($role, '/') ?: 'admin';
        $this->redirectRoute(session('active_role') . '.Dashboard', navigate: true);
    }
}
