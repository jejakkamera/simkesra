<?php

namespace App\Livewire\Apps\Dashboard\Information;

use Livewire\Component;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use App\Models\DashboardInformation;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Exportable;
use \Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class Datalist extends PowerGridComponent
{
    use WithExport;
    public string $sortField = 'description';
    public string $tableName = 'Informationdatalist';

    public function datasource(): Builder
    {
        return DashboardInformation::query();
    }

    public function header(): array
    {
        return [
            Button::add('add-information')
                ->slot("<i class='fas fa-plus'></i>")
                ->class('btn btn-success')
                ->dispatch('AddInformation', [])
                ->tooltip('ADD Information'),
        ];
    }

    #[On('AddInformation')]
    public function AddInformation()
    {
        $this->redirectRoute(session('active_role') . '.DashInformationCreate');
    }

    #[On('deleteInformation')]
    public function deleteInformation($KeyId)
    {
        $information = DashboardInformation::find($KeyId);
        $information->delete();
        session()->flash('message', 'Information deleted successfully');
    }

    public function filters(): array
    {
        return [];
    }

    public function setUp(): array
    {
        $this->persist(['sort', 'filters', 'search']);

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

    public function columns(): array
    {
        return [
            Column::add()
                ->title('Deskripsi')
                ->field('description')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('type')
                ->field('type')
                ->sortable()
                ->searchable(),

            Column::make('Photo', 'file_path'),
            Column::action('Action'),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add(
                'file_path',
                fn($item) => $item->file_path 
                    ? '<a href="' . asset("storage/{$item->file_path}") . '" class="btn btn-xs btn-outline-info" target="_blank"><i class="fas fa-download"></i></a>'
                    : '<i class="fa-solid fa-file text-gray-500 text-xl"></i>'
            );
    }

    public function actions(DashboardInformation $row): array
    {
        return [
            Button::add('edit')
                ->slot("<i class='fas fa-pencil'></i>")
                ->route(session('active_role') . '.DashInformationEdit', ['KeyId' => $row->id])
                ->class('btn btn-xs btn-outline-warning')->tooltip('Edit Information'),
            Button::add('delete-information')
                ->slot("<i class='fas fa-trash'></i>")
                ->class('btn btn-xs btn-outline-danger')
                ->dispatch('deleteInformation', ['KeyId' => $row->id])
                ->tooltip('delete Information'),
        ];
    }
}
