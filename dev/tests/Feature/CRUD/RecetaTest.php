<?php

namespace Tests\Feature\CRUD;

use App\Models\Receta;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecetaTest extends TestCase
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
        
        $this->receta = Receta::factory()->create(["user_id" => $this->user->id]);
    }


    /**
     * @group recetas
     */
    public function test_usuario_puede_ver_listado_recetas()
    {
        $this->actingAs($this->user);
        $ruta = route("recetas.index");

        $response = $this->get($ruta);
        $response->assertViewIs("recetas.index");
    }

    /**
     * @group recetas
     */
    public function test_ususario_puede_ver_receta()
    {
        $this->actingAs($this->user);

        $receta = $this->receta;
        $ruta = route("recetas.show", compact("receta"));

        $response = $this->get($ruta);
        $response->assertViewIs("recetas.show");
    }

    /**
     * @group recetas
     */
    public function test_ususario_puede_crear_receta()
    {
        $this->actingAs($this->user);

        $ruta_receta_create = route("recetas.create");
        $ruta_receta_store = route("recetas.store");
        
        
        $response = $this->get($ruta_receta_create);
        $response->assertViewIs("recetas.create");

        $formData = $this->getFormData();
        $response = $this->post($ruta_receta_store, $formData);
        
        $this->assertEquals(2, Receta::all()->count());
        $receta = Receta::find(2);
        $this->assertNotNull($receta);
        $this->assertTrue($this->comparaReceta($receta, $formData));

        $response->assertRedirect( route("recetas.edit", compact("receta")) );
    }

    /**
     * @group recetas
     */
    public function test_usuario_puede_editar_receta()
    {
        $this->actingAs($this->user);
        $receta = $this->receta;

        $ruta_editar_receta = route("recetas.edit", compact("receta"));
        $ruta_update_receta = route("recetas.update", compact("receta"));

        $response = $this->get($ruta_editar_receta);
        $response->assertViewIs("recetas.edit");

        $formData = $this->getFormData();
        $response = $this->put($ruta_update_receta, $formData);
        $response->assertRedirect($ruta_editar_receta);
        $receta = Receta::find(1);
        $this->assertNotNull($receta);
        $this->assertTrue($this->comparaReceta($receta, $formData));
    }

    /**
     * @group recetas
     */
    public function test_usuario_puede_eliminar_receta()
    {
        $this->actingAs($this->user);
        $receta = $this->receta;

        $ruta_delete = route("recetas.destroy", compact("receta"));
        $ruta_index = route("recetas.index");

        $response = $this->delete($ruta_delete);
        $response->assertRedirect($ruta_index);

        $this->assertEquals(0, Receta::all()->count());
    }



    private function getFormData($overrides = [])
    {
        $receta = Receta::factory()->make();

        return array_merge([
            "nombre" => $receta->nombre,
            'descripcion' => $receta->descripcion,
            'calorias' => $receta->calorias,
            'raciones' => $receta->raciones,
            'tiempo' => $receta->tiempo,
            'categoria' => $receta->categoria,
            //'imagen' => 'image|nullable',
        ]
        , $overrides);
    }

    private function comparaReceta(Receta $receta, array $data) : bool
    {
        $res = true;

        foreach($data as $key => $value){
            if($key != "categoria"){
                if($receta->$key != $value){
                    $res = false;
                    break;
                }
            }
        }

        return $res;
    }
}
