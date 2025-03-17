<?php

namespace App\Livewire\Apps\Skema;

use Livewire\Component;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use App\Models\Skema;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Exportable;
use \Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class Datalist extends PowerGridComponent
{

    use WithExport;
    public string $sortField = 'is_active';


    public function datasource(): Builder
    {
        // return User::where('role', 'staff');
        return Skema::query();
    }

    public function header(): array
    {
        return [
            Button::add('add-skema')
                ->slot("<i class='fas fa-plus'></i>")
                ->class('btn btn-lg btn-primary')
                ->route(session('active_role') . '.SkemaCreate', []),
        ];
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

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // ->add('description')
            ->add('is_active', fn($item) => $item->is_active === 1 ? '<span class="badge bg-label-info me-1">Open</span' : '<span class="badge bg-label-warning me-1">Close</span');
    }

    public function columns(): array
    {
        return [
            Column::make('Skema', 'judul')
                ->searchable()
                ->sortable(),
            Column::make('Nominal', 'nominal')
                ->searchable()
                ->sortable(),
            Column::make('Wilayah', 'wilayah') // Using the custom accessor
                ->searchable(),
            Column::make('Status', 'is_active')
                ->searchable()
                ->sortable(),
            Column::action('Action'),


        ];
    }

    public function actions(Skema $row): array
    {
        return [
            Button::add('edit')
                ->slot("<i class='fas fa-edit'></i>")
                ->route(session('active_role') . '.SkemaEdit', ['KeyId' => $row->id])
                ->class('btn btn-xs btn-outline-warning')->tooltip('Edit Record')
        ];
    }


}
