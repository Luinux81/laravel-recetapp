<?php

namespace Tests\Feature\CRUD;

use App\Models\Ingrediente;
use App\Models\Receta;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            PermissionSeeder::class,
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

        $ruta_receta_show = route("recetas.show", ["receta" => $this->receta->id]);
        $ruta_receta_edit = route("recetas.edit", ["receta" => $this->receta->id]);

        $response = $this->get($ruta_receta_show);
        $response->assertViewIs("recetas.show");

        $response = $this->get($ruta_receta_edit);
        $response->assertViewIs("recetas.edit");
    }


    public function test_usuario_puede_crear_ingrediente_en_receta()
    {
        $this->actingAs($this->user);

        $ruta_ingrediente_receta_create = route("recetas.ingrediente.create", ["receta" => $this->receta->id]);
        $ruta_ingrediente_receta_store = route("recetas.ingrediente.store", ["receta" => $this->receta->id]);
        $ruta_receta_edit = route("recetas.edit", ["receta" => $this->receta->id]);

        $response = $this->get($ruta_ingrediente_receta_create);
        $response->assertViewIs("recetas.ingredientes.create");

        $formData = $this->getFormData();

        $response = $this->post($ruta_ingrediente_receta_store, $formData);
        $response->assertRedirect($ruta_receta_edit);

        $this->assertEquals(4, $this->receta->ingredientes()->count());
    }


    public function test_usuario_puede_editar_ingrediente_en_receta()
    {
        $this->actingAs($this->user);

        $ingrediente = $this->receta->ingredientes()->first();
        $ruta_edit_ingrediente_receta = route("recetas.ingrediente.edit", ["receta" => $this->receta->id, "ingrediente" => $ingrediente->id]);
        $ruta_update_ingrediente_receta = route("recetas.ingrediente.update", ["receta" => $this->receta->id, "ingrediente" => $ingrediente->id]);
        $ruta_receta_edit = route("recetas.edit", ["receta" => $this->receta->id]);

        $response = $this->get($ruta_edit_ingrediente_receta);        
        $response->assertViewIs("recetas.ingredientes.edit");

        $response = $this->put($ruta_update_ingrediente_receta, $this->getFormData());
        $response->assertRedirect($ruta_receta_edit);

        //TODO: Comprobar que el registro se ha cambiado
    }


    public function test_usuario_puede_eliminar_ingrediente_en_receta()
    {
        $this->actingAs($this->user);

        $ingrediente = $this->receta->ingredientes()->first();

        $ruta_delete = route("recetas.ingrediente.destroy", ["receta" => $this->receta->id, "ingrediente" => $ingrediente->id]);
        $ruta_edit = route("recetas.edit", ["receta" => $this->receta->id]);

        $response = $this->delete($ruta_delete);
        $response->assertRedirect($ruta_edit);

        $this->assertEquals(2, $this->receta->ingredientes()->count());
    }


    private function getFormData($overrides = [])
    {
        $ingrediente = Ingrediente::factory()->create(["user_id" => $this->user->id]);

        return array_merge([
            'ingrediente' => $ingrediente->id,
            'cantidad' => $this->faker->randomNumber(3),
            'unidad_medida' => 'gr',
        ], $overrides);
    }

}



