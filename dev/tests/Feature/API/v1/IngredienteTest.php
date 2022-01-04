<?php

namespace Tests\Feature\API\v1;

use App\Models\Ingrediente;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Testing\AssertableJsonString;
use Tests\TestCase;

class IngredienteTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var Authenticatable */
    private $admin;
    
    /** @var Authenticatable */
    private $user;


    public function setUp() : void 
    {
        parent::setUp();

        $this->seed([
            UserSeeder::class,
            PermissionSeeder::class
        ]);

        $this->admin = User::find(1);
        $this->user = User::factory()->create();
    }


    /**
     * @group ingredientes
     */
    public function test_usuario_puede_ver_listado()
    {
        $this->actingAs($this->user);
        
        Ingrediente::factory()->count(3)->create(["user_id" => $this->user->id]);
        Ingrediente::factory()->create(["user_id" => NULL]);
        
        $ruta = route("api.v1.ingredientes.index");

        $response = $this->get($ruta)->decodeResponseJson();

        $response->assertStructure([
            '*' => [
                'id',
                'user_id',
                'cat_id',
                'nombre',
                'descripcion',
                'marca',
                'barcode',
                'imagen',
                'url',
                'calorias',
                'fat_saturadas',
                'fat_poliinsaturadas',
                'fat_monoinsaturadas',
                'fat_trans',
                'colesterol',
                'sodio',
                'potasio',
                'fibra',
                'carb_total',
                'carb_azucar',
                'proteina',
                'marca',
                'deleted_at',
                'created_at',
                'updated_at',
            ]
        ]);

        $this->assertEquals(4, $response->count() );

    }

    /**
     * @group ingredientes
     */
    public function test_usuario_puede_ver_ingrediente()
    {
        $this->actingAs($this->user);

        $ingrediente = Ingrediente::factory()->create(["user_id" => $this->user->id]);
        $ruta = route("api.v1.ingredientes.show", compact("ingrediente"));

        $response = $this->get($ruta);
        
        $response
            ->decodeResponseJson()
            ->assertStructure([
                'id',
                'user_id',
                'cat_id',
                'nombre',
                'descripcion',
                'marca',
                'barcode',
                'imagen',
                'url',
                'calorias',
                'fat_saturadas',
                'fat_poliinsaturadas',
                'fat_monoinsaturadas',
                'fat_trans',
                'colesterol',
                'sodio',
                'potasio',
                'fibra',
                'carb_total',
                'carb_azucar',
                'proteina',
                'marca',
                'deleted_at',
                'created_at',
                'updated_at',
        ]);


        $this->assertTrue($this->comparaIngrediente($ingrediente, $response->json() ));
    }

    /**
     * @group ingredientes
     */
    public function test_usuario_puede_crear_ingrediente()
    {
        $this->actingAs($this->user);
        $ruta = route("api.v1.ingredientes.store");

        $data = $this->getFormData();

        $response = $this->post($ruta, $data);
        $response->assertCreated();
        $this->assertEquals(1, Ingrediente::all()->count());

        $ingrediente = Ingrediente::find(1);
        $this->assertNotNull($ingrediente);

        $this->assertTrue($this->comparaIngrediente($ingrediente, $data));
    }

    /**
     * @group ingredientes
     */
    public function test_usuario_puede_editar_ingrediente()
    {
        $this->actingAs($this->user);

        $ingrediente = Ingrediente::factory()->create(["user_id"=>$this->user->id]);

        $ruta = route("api.v1.ingredientes.update", compact("ingrediente"));

        $data = $this->getFormData();

        $response = $this->put($ruta, $data);
        $response->assertOk();

        $ingrediente = Ingrediente::find(1);
        $this->assertNotNull($ingrediente);

        $this->assertTrue($this->comparaIngrediente($ingrediente, $data));

    }


    /**
     * @group ingredientes
     */
    public function test_usuario_puede_eliminar_ingrediente()
    {
        $this->actingAs($this->user);

        $ingrediente = Ingrediente::factory()->create(["user_id"=>$this->user->id]);

        $ruta = route("ingredientes.destroy", compact("ingrediente"));

        $response = $this->delete($ruta);

        $this->assertEquals(0, Ingrediente::all()->count());
    }



    private function getFormData( $overrides = []) : array
    {
        $ingrediente = Ingrediente::factory()->make();

        return array_merge([
            "nombre" => $ingrediente->nombre,
            "descripcion" => $ingrediente->descripcion,
            "marca" => $ingrediente->marca,
            "barcode" => $ingrediente->barcode,
            //"imagen" => $ingrediente->imagen,        
            "url" => $ingrediente->url,   
            "calorias" => $ingrediente->calorias,     
            "fat_total" => $ingrediente->fat_total,        
            "fat_saturadas" => $ingrediente->fat_saturadas,        
            "fat_poliinsaturadas" => $ingrediente->fat_poliinsaturadas,        
            "fat_monoinsaturadas" => $ingrediente->fat_monoinsaturadas,        
            "fat_trans" => $ingrediente->fat_trans,        
            "colesterol" => $ingrediente->colesterol,        
            "sodio" => $ingrediente->sodio,        
            "potasio" => $ingrediente->potasio,        
            "fibra" => $ingrediente->fibra,        
            "carb_total" => $ingrediente->carb_total,        
            "carb_azucar" => $ingrediente->carb_azucar,        
            "proteina" => $ingrediente->proteina,
            "categoria" => $ingrediente->cat_id,
        ], $overrides);
    }


    private function comparaIngrediente(Ingrediente $ingrediente, array $data) : bool
    {
        // TODO: Mejorar la comparación de ingrediente con las fechas y la categoria
        $res = true;

        foreach ($data as $key => $value) {
            if ($key == "deleted_at" || $key == "created_at" || $key == "updated_at")
            {
                // $carbon = Carbon::parse($value);
                
                // if ($carbon->notEqualTo($ingrediente->$key)){
                //     $res = false;
                //     break;
                // }
            }
            else{
                if($key != "categoria")
                {                
                    if($ingrediente->$key != $value){
                    $res = false;
                    break;
                    }
                }
            }
        }

        return $res;
    }




    private function getArrayParaComparacionJSON (Ingrediente $ingrediente)
    {
        $res = [];

        foreach ($ingrediente->getAttributes() as $key => $value) {
            $res[$key] = $value;
        }

        return $res;
    }
}
