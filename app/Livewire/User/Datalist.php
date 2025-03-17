<?php

namespace App\Livewire\User;


use Livewire\Component;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use App\Models\User;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Exportable;
use \Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class Datalist extends PowerGridComponent
{

    use WithExport;
    public string $sortField = 'name';


    public function datasource(): Builder
    {
        // return User::where('role', 'staff');
        return User::with('roles')->whereNot('role', 'siswa');
    }

    public function header(): array
    {
        return [
            Button::add('add-staff')
                ->slot("<i class='fas fa-plus'></i>")
                ->class('btn btn-lg btn-primary')
                ->route(session('active_role') . '.UserCreate', []),
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
        return [
            Button::add('edit')
                ->slot("<i class='fas fa-edit'></i>")
                ->route(session('active_role') . '.UserEdit', ['UserId' => $row->id])
                ->class('btn btn-xs btn-outline-warning')->tooltip('Edit Record'),
            Button::add('role')
                ->slot("<i class='fas fa-user'></i>")
                ->route(session('active_role') . '.UserPlotRole', ['UserId' => $row->id])
                ->class('btn btn-xs btn-outline-info')->tooltip('Plot Role'),
            Button::add('skema')
                ->slot("<i class='fas fa-cloud'></i>")
                ->route(session('active_role') . '.UserPlotSkema', ['UserId' => $row->id])
                ->class('btn btn-xs btn-outline-info')->tooltip('Plot Skema'),
            Button::add('delete')
                ->slot("<i class='fas fa-trash'></i>")
                ->class('btn btn-xs btn-outline-danger')
                ->dispatch('delete', ['id' => $row->id]),
        ];
    }

    #[On('delete')]
    public function delete($id): void
    {
        User::find($id)->delete();
        session()->flash('message', 'User Delete successfully');
        $this->redirectRoute(session('active_role') . '.UserDatalist');
    }
}
