<?php

namespace Tests\Feature\CRUD;

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
            PermissionSeeder::class,
        ]);

        $this->admin = User::find(1);
        $this->user = User::factory()->create();
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_ver_listado_categorias()
    {
        $this->actingAs($this->user);

        $response = $this->get(route("recetas.categoria.index"));
        $response->assertViewIs("recetas.categorias.index");
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_ver_categoria()
    {
        $this->actingAs($this->user);

        $categoria = CategoriaReceta::factory()->create(["user_id"=>$this->user->id]);

        $response = $this->get(route("recetas.categoria.show", compact("categoria")));
        $response->assertViewIs("recetas.categorias.show");
        $response->assertViewHas("categoria");
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_crear_categoria()
    {
        $this->actingAs($this->user);

        $ruta_create = route("recetas.categoria.create");
        $ruta_store = route("recetas.categoria.store");
        $ruta_index = route("recetas.categoria.index");

        $response = $this->get($ruta_create);
        $response->assertViewIs("recetas.categorias.create");

        $formData = $this->getFormData();

        $response = $this->post($ruta_store, $formData);
        $response->assertRedirect($ruta_index);
        $this->assertEquals(1, CategoriaReceta::all()->count());

        $categoria = CategoriaReceta::find(1);
        $this->assertNotNull($categoria);
        $this->assertTrue($this->comparaCategoria($categoria, $formData));
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_editar_categoria()
    {
        $this->actingAs($this->user);

        $categoria = CategoriaReceta::factory()->create(["user_id"=>$this->user->id]);

        $ruta_edit = route("recetas.categoria.edit", compact("categoria"));
        $ruta_update = route("recetas.categoria.update", compact("categoria"));
        $ruta_index = route("recetas.categoria.index");

        $response = $this->get($ruta_edit);
        $response->assertViewIs("recetas.categorias.edit");
        $response->assertViewHas("categoria");

        $formData = $this->getFormData();
        $response = $this->put($ruta_update, $formData);
        $response->assertRedirect($ruta_index);

        $categoria = CategoriaReceta::find(1);
        $this->assertNotNull($categoria);

        $this->assertTrue($this->comparaCategoria($categoria, $formData));
    }


    /**
     * @group categoria-receta
     */
    public function test_usuario_puede_eliminar_categoria()
    {
        $this->actingAs($this->user);
        
        $categoria = CategoriaReceta::factory()->create(["user_id"=>$this->user->id]);

        $ruta_borrar = route("recetas.categoria.destroy", compact("categoria"));
        $ruta_index = route("recetas.categoria.index");

        $response = $this->delete($ruta_borrar);
        $response->assertRedirect($ruta_index);

        $this->assertEquals(0, CategoriaReceta::all()->count());
    }




    private function getFormData($overrides = [])
    {
        $categoria = CategoriaReceta::factory()->make(["user_id"=>$this->user->id]);

        return array_merge([
            'nombre' => $categoria->nombre,
            'descripcion' => $categoria->descripcion,
            'categoria'=>'',
        ], $overrides);
    }


    private function comparaCategoria(CategoriaReceta $categoria, array $data)
    {
        if($categoria->nombre != $data["nombre"]){
            return false;
        }

        if($categoria->descripcion != $data["descripcion"]){
            return false;
        }

        if($categoria->catParent_id != $data["categoria"]){
            return false;
        }

        return true;
    }
}

