<?php

namespace App\Livewire\Apps\Period\Bank;

use Livewire\Component;
use PowerComponents\LivewirePowerGrid\{Button, Column, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use App\Models\Period as Periode;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Exportable;
use \Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class Datalist extends PowerGridComponent
{
    use WithExport;
    public string $sortField = 'is_active';
    public string $tableName = 'periodelist';
    public string $sortDirection = 'desc';

    public function datasource(): Builder
    {
        // return User::where('role', 'staff');
        return Periode::query();
    }

    public function header(): array
    {
        return [
            
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            // ->add('description')
            ->add('is_active', fn($item) => $item->is_active === 1 ? '<span class="badge bg-label-info me-1">Open</span' : '<span class="badge bg-label-warning me-1">Close</span');
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

    public function filters(): array
    {
        $categories = [
            ['id' => 1, 'name' => 'Open'],
            ['id' => 0, 'name' => 'Close'],
        ];

        return [
            Filter::inputText('name_period')->operators(['contains']),
            Filter::select('is_active')
                ->dataSource($categories)->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('Nama periode', 'name_period')
                ->searchable()
                ->sortable(),
            Column::make('start_date', 'start_date')
                ->searchable()
                ->sortable(),
            Column::make('end_date', 'end_date') // Using the custom accessor
                ->searchable()
                ->sortable(),
            Column::make('validate_date', 'validate_date') // Using the custom accessor
                ->searchable()
                ->sortable(),
            Column::make('Status', 'is_active')
                ->searchable()
                ->sortable(),
            Column::action('Action'),
        ];
    }

    public function actions(Periode $row): array
    {
        return [
            Button::add('skema')
                ->slot("<i class='menu-icon tf-icons ti ti-report-money'></i>")
                ->route(session('active_role') . '.PeriodDashboardBank', ['periode' => $row->id])
                ->class('btn btn-xs btn-outline-primary')->tooltip('Dashboard List'),
        ];
    }


}
