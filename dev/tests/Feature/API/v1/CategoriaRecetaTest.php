<?php

namespace Tests\Feature\API\v1;

use App\Models\CategoriaReceta;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaRecetaTest extends TestCase
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
     * @group categoria-receta
     */
    public function test_usuario_puede_ver_listado()
    {
        $this->actingAs($this->user);

        CategoriaReceta::factory()->count(3)->create(["user_id"=>$this->user->id]);
        CategoriaReceta::factory()->create(["user_id" => NULL, "publicado" => 1]);

        $ruta = route("api.v1.recetas.categoria.index");

        $response = $this->get($ruta)->decodeResponseJson();

        $response->assertStructure([
                    '*' => [
                        'id',
                        'user_id',
                        'catParent_id',
                        'nombre',
                        'descripcion',
                        'created_at',
                        'updated_at',
                    ]
                ]);
        
        $this->assertEquals(4, $response->count() );
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_ver_categoria()
    {
        $this->actingAs($this->user);

        $categoria = CategoriaReceta::factory()->create(["user_id" => $this->user->id]);
        $ruta = route("api.v1.recetas.categoria.show", compact("categoria"));

        $response = $this->get($ruta)->decodeResponseJson();

        $response->assertStructure([
            'id',
            'user_id',
            'catParent_id',
            'nombre',
            'descripcion',
            'created_at',
            'updated_at',
        ]);
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_crear_categoria()
    {
        $this->actingAs($this->user);

        $ruta = route("api.v1.recetas.categoria.store");

        $data = $this->getFormData();

        $response = $this->post($ruta, $data);

        $response->assertCreated();

        $categoria = CategoriaReceta::find(1);
        $this->assertNotNull($categoria);

        $this->assertTrue($this->compararCategoria($categoria, $data));
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_editar_categoria()
    {
        $this->actingAs($this->user);

        $categoria = CategoriaReceta::factory()->create(["user_id" => $this->user->id]);
        $ruta = route("api.v1.recetas.categoria.update", compact("categoria"));

        $data = $this->getFormData();

        $response = $this->put($ruta, $data);
        $response->assertOk();

        $categoria = CategoriaReceta::find(1);
        $this->assertNotNull($categoria);
        $this->assertTrue($this->compararCategoria($categoria, $data));
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_eliminar_categoria()
    {
        $this->actingAs($this->user);

        $categoria = CategoriaReceta::factory()->create(["user_id" => $this->user->id]);
        $ruta = route("api.v1.recetas.categoria.destroy", compact("categoria"));

        $response = $this->delete($ruta);
        $response->assertOk();

        $response = $response->json();
        $this->assertEquals("info", $response["tipo"]);
        $this->assertEquals("Acción realizada con éxito", $response["mensaje"]);
    }



    private function getFormData(array $overrides = [])
    {
        /** @var CategoriaReceta */
        $categoria = CategoriaReceta::factory()->make();

        return array_merge([
            "nombre" => $categoria->nombre,
            "descripcion" => $categoria->descripcion,
            "categoria" => ''
        ], $overrides);
    }


    private function compararCategoria(CategoriaReceta $categoria, array $data) : bool
    {
        if($categoria->nombre != $data["nombre"]) return false;
        if($categoria->descripcion != $data["descripcion"]) return false;
        if($categoria->categoria != $data["categoria"]) return false;

        return true;
    }
}

