<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Spatie\Permission\Models\Role;

final class UserTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage(perPage: 5, perPageValues: [0, 5, 10, 50, 100, 500])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()->with(['roles']);
    }

    public function relationSearch(): array
    {
        return [
            'roles' => [
                'name'
            ]
        ];
    }

    public function fields(): PowerGridFields
    {

        $roles = Role::all();

        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('profile_image', fn(User $model) => '<div class="w-12 h-12"><img class="h-full w-full shrink-0 grow-0 rounded-full" src="' . asset('storage/profilePhotos/' . e($model->profile_image)) . '"></div>')
            ->add('role', function (User $model) use ($roles) {
                return Blade::render('<x-select-role type="occurrence" :options=$options  :modelId=$userId  :selected=$selected/>', ['options' => $roles, 'userId' => intval($model->id), 'selected' => intval($model->roles[0]->id)]);
            })
            ->add('created_at');
    }

    public function columns(): array
    {
        return [

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('profile_image', 'profile_image'),

            Column::make('Role', 'role')
                ->searchable(),


            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }

    #[On('roleChanged')]
    public function roleChanged($roleId, $modelId): void
    {
        User::find($modelId)->roles()->sync($roleId);
        $this->dispatch('notify');
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
