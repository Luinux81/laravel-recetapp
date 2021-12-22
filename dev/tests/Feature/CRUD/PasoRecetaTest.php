<?php

namespace Tests\Feature\CRUD;

use App\Models\Ingrediente;
use App\Models\PasoReceta;
use App\Models\Receta;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasoRecetaTest extends TestCase
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


        for($i = 1; $i < 4; $i++)
        {
            $this->receta->pasos()->save(
                new PasoReceta([
                    "orden" => $i,
                    "texto" => $this->faker->text(100)
                ])
            );
        }
    }


    public function test_usuario_puede_ver_listado_pasos()
    {
        $this->actingAs($this->user);

        $ruta_receta_show = route("recetas.show", ["receta" => $this->receta->id]);
        $ruta_receta_edit = route("recetas.edit", ["receta" => $this->receta->id]);

        $response = $this->get($ruta_receta_show);
        $response->assertViewIs("recetas.show");

        $response = $this->get($ruta_receta_edit);
        $response->assertViewIs("recetas.edit");
    }

    // TODO: En crear, editar y borrar comprobar que los ordenes de los pasos están bien
    
    public function test_usuario_puede_crear_paso_en_receta()
    {
        $this->actingAs($this->user);

        $ruta_paso_receta_create = route("recetas.paso.create", ["receta" => $this->receta->id]);
        $ruta_paso_receta_store = route("recetas.paso.store", ["receta" => $this->receta->id]);

        $response = $this->get($ruta_paso_receta_create);
        $response->assertViewIs("recetas.pasos.create");

        $formData = $this->getFormData();

        $response = $this->post($ruta_paso_receta_store, $formData);
        $paso = $this->receta->pasos()->where('orden', $formData["orden"])->get();

        $this->assertNotNull($paso);
        $this->assertEquals(1, $paso->count());

        $response->assertRedirect(route('recetas.paso.edit', ["receta" => $this->receta->id, "paso" => $paso->first()->id]));
    }


    public function test_usuario_puede_editar_paso_en_receta()
    {
        $this->actingAs($this->user);
        $receta = $this->receta;
        $paso = $this->receta->pasos()->first();

        $ruta_edit_paso_receta = route("recetas.paso.edit", compact("receta","paso"));
        $ruta_update_paso_receta = route("recetas.paso.update", compact("receta","paso"));
        $ruta_receta_edit = route("recetas.edit", compact("receta"));


        $response = $this->get($ruta_edit_paso_receta);
        $response->assertViewIs("recetas.pasos.edit");

        $formData = $this->getFormData(false);
        $response = $this->put($ruta_update_paso_receta, $formData);
        $response->assertRedirect($ruta_receta_edit);
    }


    public function test_usuario_puede_eliminar_paso_en_receta()
    {
        $this->actingAs($this->user);
        $receta = $this->receta;
        $paso = $receta->pasos()->first();

        $ruta_borrar_paso = route("recetas.paso.destroy", compact("receta", "paso"));
        $ruta_receta_edit = route("recetas.edit", compact("receta"));

        $response = $this->delete($ruta_borrar_paso);
        $response->assertRedirect($ruta_receta_edit);

        $this->assertEquals(2, $receta->pasos()->count());
    }


    private function getFormData($create = true, $overrides = [])
    {        
        if($create){
            $max = $this->receta->pasos()->count() + 1;
        }
        else{
            $max = $this->receta->pasos()->count();
        }

        return array_merge([
            'orden' => $this->faker->numberBetween(1, $max),
            'texto' => $this->faker->text(100)
            ], $overrides);
    }
}
