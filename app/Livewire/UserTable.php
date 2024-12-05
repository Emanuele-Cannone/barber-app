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

    public $listeners = ['recordUpdated' => 'render'];

    public function setUp(): array
    {

        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage(perPage: 5, perPageValues: [0, 5, 10, 50, 100, 500])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()
            ->with(['roles']);
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
            ->add('role', function (User $model) use ($roles) {
                return Blade::render('<x-select-role type="occurrence" :options=$options  :modelId=$userId  :selected=$selected />',
                    [
                        'options' => $roles,
                        'userId' => intval($model->id),
                        'selected' => intval($model->roles[0]->id)
                    ]
                );
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

    #[On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }


    public function actions(User $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: ' . $row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id]),

        ];
    }

    public function header(): array
    {
        return [
            Button::add('add-user')
                ->slot(__('user.add_user'))
                ->class('rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 dark:bg-pg-primary-800 dark:placeholder-pg-primary-400 rounded-md border-0 bg-transparent py-2 px-3 ring-0 placeholder:text-gray-400 sm:text-sm sm:leading-6 w-auto')
                ->dispatch('addUser', []),
        ];
    }

    #[On('addUser')]
    public function addUser(): void
    {
        $this->dispatch('showCreateUserModal');
    }

    #[On('roleChanged')]
    public function roleChanged($roleId, $modelId): void
    {
        $user = User::find($modelId);

        if(!$user->hasRole('Super-Admin')){
            $user->roles()->sync($roleId);
        }
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
