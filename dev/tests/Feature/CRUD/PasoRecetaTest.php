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


    /** @group pasos_receta */
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

    
    /** @group pasos_receta */
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
        
        $this->assertTrue($this->pasosEnOrden($this->receta));
    }


    /** @group pasos_receta */
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

        $this->assertTrue($this->pasosEnOrden($this->receta));
    }


    /** @group pasos_receta */
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

        $this->assertTrue($this->pasosEnOrden($this->receta));
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

    private function pasosEnOrden(Receta $receta)
    {
        $pasos = $receta->pasos()->orderBy('orden')->get();
        $res = true;
        $i = 1;

        foreach($pasos as $paso){
            if($paso->orden != $i){
                $res = false;
                break;
            }
            else{
                $i++;
            }
        }

        return $res;
    }
}
