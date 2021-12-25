<?php

namespace Tests\Feature\API\v1;

use App\Models\Ingrediente;
use App\Models\Receta;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IngredienteRecetaTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var Authenticatable */
    private $admin;

    /** @var Authenticatable */
    private $user;

    /** @var Receta */
    private $receta;


    public function setUp() : void
    {
        parent::setUp();

        $this->seed([
            UserSeeder::class,
            PermissionSeeder::class
        ]);

        $this->admin = User::find(1);
        $this->user = User::factory()->create();


        $ingredientes = Ingrediente::factory()->count(3)->create(["user_id"=>$this->user->id]);

        $this->receta = Receta::factory()
                    ->hasAttached($ingredientes, [
                        "cantidad"=>"100",
                        "unidad_medida"=>"gr"
                    ])
                    ->create(["user_id"=>$this->user->id])
        ;
    }


    public function test_usuario_puede_ver_listado_de_ingredientes_en_receta()
    {
        $this->actingAs($this->user);
        $receta = $this->receta;

        $ruta = route("api.v1.recetas.ingrediente.index", compact("receta"));

        $response = $this->get($ruta);
        $response->assertOk();

        $response
            ->decodeResponseJson()
            ->assertStructure([
                [
                    "id",
                    "user_id",
                    "cat_id",
                    "nombre",
                    "descripcion",
                    "marca",
                    "barcode",
                    "imagen",
                    "url",
                    "calorias",
                    "fat_total",
                    "fat_saturadas",
                    "fat_poliinsaturadas",
                    "fat_monoinsaturadas",
                    "fat_trans",
                    "colesterol",
                    "sodio",
                    "potasio",
                    "fibra",
                    "carb_total",
                    "carb_azucar",
                    "proteina",
                    "deleted_at",
                    "created_at",
                    "updated_at",
                    "pivot" => [
                        "receta_id",
                        "ingrediente_id",
                        "cantidad",
                        "unidad_medida",
                    ]
                ]
            ]);

        $this->assertEquals(3, $response->decodeResponseJson()->count());
    }


    public function test_usuario_puede_ver_ingrediente_en_receta()
    {
        $this->actingAs($this->user);

        $receta = $this->receta;
        $ingrediente = $receta->ingredientes()->first();
        $ruta = route("api.v1.recetas.ingrediente.show", compact("receta", "ingrediente"));

        // TODO: Ver como hacer que show e index dejen ver ingredientes publicos sin permiso

        $response = $this->get($ruta);
        $response->assertOk();

        $response
        ->decodeResponseJson()
        ->assertStructure([
            "id",
            "user_id",
            "cat_id",
            "nombre",
            "descripcion",
            "marca",
            "barcode",
            "imagen",
            "url",
            "calorias",
            "fat_total",
            "fat_saturadas",
            "fat_poliinsaturadas",
            "fat_monoinsaturadas",
            "fat_trans",
            "colesterol",
            "sodio",
            "potasio",
            "fibra",
            "carb_total",
            "carb_azucar",
            "proteina",
            "deleted_at",
            "created_at",
            "updated_at",
            "pivot" => [
                "receta_id",
                "ingrediente_id",
                "cantidad",
                "unidad_medida",
            ]
        ]);

        //TODO: Comparar $ingrediente con response atributo por atributo
        //dd($ingrediente, $ingrediente->pivot);
    }


    public function test_usuario_puede_crear_ingrediente_en_receta()
    {
        $this->actingAs($this->user);

        $ingrediente = Ingrediente::factory()->create(["user_id" => $this->user->id]);
        $receta = $this->receta;
        $ruta = route("api.v1.recetas.ingrediente.store", compact("receta"));

        $data = $this->getFormData();

        $response = $this->post($ruta, $data);
        $response->assertCreated();

        //dd($response->json());
        //TODO : Comprobar que el response json es igual que $data
        
        $this->assertEquals(4, $this->receta->ingredientes()->count());
    }



    public function test_usuario_puede_editar_ingrediente_en_receta()
    {
        $this->actingAs($this->user);

        $receta = $this->receta;
        $ingrediente = $this->receta->ingredientes()->first();
        $ruta = route("api.v1.recetas.ingrediente.update", compact("receta", "ingrediente"));

        $data = $this->getFormData(["ingrediente"=>$ingrediente->id]);
        
        $response = $this->put($ruta, $data);
        $response->assertOk();

        // TODO: Comprobar que en el response hay lo mismo que en data
    }


    public function test_usuario_puede_eliminar_ingrediente_en_receta()
    {
        $this->actingAs($this->user);

        $receta = $this->receta;
        $ingrediente = $this->receta->ingredientes()->first();
        $ruta = route("api.v1.recetas.ingrediente.destroy", compact("receta", "ingrediente"));

        $response = $this->delete($ruta);
        $response->assertOk();

        $this->assertEquals(2, $receta->ingredientes()->count());
    }


    private function getFormData( $overrides = [] ) : array
    {
        $ingrediente = Ingrediente::factory()->create(["user_id"=>$this->user->id]);

        return array_merge([
            'ingrediente' => $ingrediente->id,
            'cantidad' => $this->faker->randomNumber(3),
            'unidad_medida' => 'gr',
        ], $overrides);
    }
}
