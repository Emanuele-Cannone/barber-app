<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

final class ServiceTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public array $active;

    public array $name;

    public array $description;

    public array $duration;

    public bool $showErrorBag = true;

    public $listeners = ['recordUpdated' => 'render'];

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            /*
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            */
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage(perPage: 5, perPageValues: [0, 5, 10, 50, 100, 500])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Service::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('active')
            ->add('description')
            ->add('name')
            ->add('duration', fn (Service $model) => Carbon::parse($model->duration)->format('H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('service.name'), 'name')
                ->editOnClick(Auth::user()->can('manage-service'))
                ->sortable()
                ->searchable(),

            Column::make(__('service.description'), 'description')
                ->editOnClick(Auth::user()->can('manage-service'))
                ->searchable(),

            Column::make(__('service.duration'), 'duration')
                ->editOnClick(Auth::user()->can('manage-service')),

            Column::make(__('service.active'), 'active')
                ->toggleable(Auth::user()->can('manage-service')),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    public function header(): array
    {
        return [
            Button::add('add-service')
                ->slot(__('service.add_service'))
                ->class('rounded-md ring-1 transition focus-within:ring-2 dark:ring-pg-primary-600 dark:text-pg-primary-300 dark:bg-pg-primary-800 dark:placeholder-pg-primary-400 rounded-md border-0 bg-transparent py-2 px-3 ring-0 placeholder:text-gray-400 sm:text-sm sm:leading-6 w-auto')
                ->dispatch('addService', []),
        ];
    }

    public function actions(Service $row): array
    {
        return [
            Button::add('delete')
                ->slot('Delete')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('showDeleteModal', ['model' => 'Service', 'modelId' => $row->id])
        ];
    }

    #[On('addService')]
    public function addService(): void
    {
        $this->dispatch('showCreateServiceModal');
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        $this->validate();

        try{

            DB::beginTransaction();
            Service::query()->findOrFail($id)->update([$field => $value]);
            DB::commit();

        } catch (\Exception $exception) {

            Log::error($exception->getMessage());
            DB::rollBack();
        }
    }

    protected function messages()
    {
        return [
            'duration.*.regex'     => 'Invalid format: :values',
            'name.*.unique' => 'Name already listed.',
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        $this->validate();

        try{

            DB::beginTransaction();
            Service::query()->findOrFail($id)->update([$field => $value]);
            DB::commit();

        } catch (\Exception $exception) {

            Log::error($exception->getMessage());
            DB::rollBack();
        }

    }

    protected function rules()
    {
        return [

            'active.*' => [
                'required',
                'boolean',
            ],

            'name.*' => [
                'required',
                'string',
                'unique:services,name,{{id}}'
            ],

            'description.*' => [
                'required',
                'string',
            ],

            'duration.*' => [
                'required',
                'string',
                'regex:/^(?:0[0-7]):[0-5]\d$/'
            ],

        ];
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
