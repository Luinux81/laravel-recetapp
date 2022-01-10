<?php

namespace App\Http\Livewire;

use App\Models\Ingrediente;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\CategoriaIngrediente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class IngredientesTable extends PowerGridComponent
{
    use ActionButton;

    public array $perPageValues = [10, 50, 100, 500, 1000];

    public string $sortField = 'nombre';
    
    public string $sortDirection = 'asc';

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
        
        return $user->getAllIngredientes();
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
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
                return Ingrediente::find($entry->id)->nombre;
            })
            ->addColumn('cat_id', function ($entry){
                return CategoriaIngrediente::find($entry->cat_id)?->nombre;
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

        $categorias = $user->categoriasIngrediente()->orderBy('nombre')->get();

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

    public function actions(): array
    {
        return [
            Button::add('edit')
                ->target('')
                ->caption(__('Editar'))
                ->class('boton boton--gris')
                ->route('ingredientes.edit',['ingrediente'=>'id'])
            ,
            
            Button::add('destroy')
                ->target('')
                ->caption(__('Borrar'))
                ->class('boton boton--rojo')
                ->route('ingredientes.destroy',['ingrediente'=>'id'])
                ->method('delete')
                ,
            
            Button::add('publish')
                ->target('')
                ->caption(__('Publicar'))
                ->class('boton boton--gris')
                ->route('ingredientes.publish',['ingrediente'=>'id'])
                ->method('post')
        ];
    }
}
