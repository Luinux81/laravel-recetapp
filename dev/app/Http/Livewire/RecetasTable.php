<?php

namespace App\Http\Livewire;

use App\Models\CategoriaReceta;
use App\Models\Receta;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class RecetasTable extends PowerGridComponent
{
    use ActionButton;

    //Messages informing success/error data is updated.
    public bool $showUpdateMessages = true;

    public array $perPageValues = [10, 50, 100, 500, 1000];

    public string $sortField = 'nombre';
    
    public string $sortDirection = 'asc';

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): void
    {
        $this
            ->showPerPage(50)
            ->showRecordCount('full')
            //->showExportOption('download', ['excel', 'csv'])
            ->showSearchInput();
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */
    public function datasource(): ?Collection
    {
        /** @var User */
        $user = auth()->user();
        
        return $user->getAllRecetas();
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('nombre', function ($entry){
                return Receta::find($entry->id)->nombre;
            })
            ->addColumn('cat_id', function ($entry){
                return CategoriaReceta::find($entry->cat_id)?->nombre;
            })
            ->addColumn('calorias')
            ;
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

     /**
     * PowerGrid Columns.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Column>
     */
    public function columns(): array
    {
        /** @var User */
        $user = auth()->user();

        $categorias = $user->getAllCategoriasReceta();

        return [
            Column::add()
                ->title('Nombre')
                ->field('nombre')
                ->makeInputText('nombre')
                ->searchable()
                ->sortable(),

            Column::add()
                ->title('Categoria')
                ->field('cat_id')
                ->searchable()
                ->makeInputSelect($categorias,"nombre","cat_id")
                ->sortable(),

            Column::add()
                ->title('Calorias (100g)')
                ->field('calorias')
                ->sortable(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable this section only when you have defined routes for these actions.
    |
    */

     /**
     * PowerGrid Receta action buttons.
     *
     * @return array<int, \PowerComponents\LivewirePowerGrid\Button>
     */


    public function actions(): array
    {
        return [
            Button::add('seed')
            ->target('')
            ->caption(__('Seeder'))
            ->class('boton boton--azul')
            ->route('admin.seed.receta', ['receta' => 'id'])
            ,

            Button::add('edit')
                ->target('')
                ->caption(__('Editar'))
                ->class('boton boton--gris')
                ->route('recetas.edit', ['receta' => 'id'])
            ,
            
            Button::add('destroy')
                ->target('')
                ->caption(__('Borrar'))
                ->class('boton boton--rojo')
                ->route('recetas.destroy', ['receta' => 'id'])
                ->method('delete')
            ,
            
            Button::add('publish')
                ->target('')
                ->caption(__('Publicar'))
                ->class('boton boton--gris')
                ->route('recetas.publish',['receta'=>'id'])
                ->method('post')
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Method
    |--------------------------------------------------------------------------
    | Enable this section to use editOnClick() or toggleable() methods.
    | Data must be validated and treated (see "Update Data" in PowerGrid doc).
    |
    */

     /**
     * PowerGrid Receta Update.
     *
     * @param array<string,string> $data
     */

    /*
    public function update(array $data ): bool
    {
       try {
           $updated = Receta::query()
                ->update([
                    $data['field'] => $data['value'],
                ]);
       } catch (QueryException $exception) {
           $updated = false;
       }
       return $updated;
    }

    public function updateMessages(string $status = 'error', string $field = '_default_message'): string
    {
        $updateMessages = [
            'success'   => [
                '_default_message' => __('Data has been updated successfully!'),
                //'custom_field'   => __('Custom Field updated successfully!'),
            ],
            'error' => [
                '_default_message' => __('Error updating the data.'),
                //'custom_field'   => __('Error updating custom field.'),
            ]
        ];

        $message = ($updateMessages[$status][$field] ?? $updateMessages[$status]['_default_message']);

        return (is_string($message)) ? $message : 'Error!';
    }
    */
}
