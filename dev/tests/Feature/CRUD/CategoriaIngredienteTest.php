<?php

namespace Tests\Feature\CRUD;

use App\Models\CategoriaIngrediente;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriaIngredienteTest extends TestCase
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
     * @group categoria-ingrediente
     */
    public function test_usuario_puede_ver_listado_categorias()
    {
        $this->actingAs($this->user);

        $response = $this->get(route("ingredientes.categoria.index"));
        $response->assertViewIs("ingredientes.categorias.index");
    }


    /**
     * @group categoria-ingrediente
     */
    public function test_usuario_puede_ver_categoria()
    {
        $this->actingAs($this->user);

        $categoria = CategoriaIngrediente::factory()->create(["user_id"=>$this->user->id]);

        $response = $this->get(route("ingredientes.categoria.show", compact("categoria")));
        $response->assertViewIs("ingredientes.categorias.show");
        $response->assertViewHas("categoria");
    }


    /**
     * @group categoria-ingrediente
     */
    public function test_usuario_puede_crear_categoria()
    {
        $this->actingAs($this->user);

        $ruta_create = route("ingredientes.categoria.create");
        $ruta_store = route("ingredientes.categoria.store");
        $ruta_index = route("ingredientes.categoria.index");

        $response = $this->get($ruta_create);
        $response->assertViewIs("ingredientes.categorias.create");

        $formData = $this->getFormData();

        $response = $this->post($ruta_store, $formData);
        $response->assertRedirect($ruta_index);
        $this->assertEquals(1, CategoriaIngrediente::all()->count());

        $categoria = CategoriaIngrediente::find(1);
        $this->assertNotNull($categoria);
        $this->assertTrue($this->comparaCategoria($categoria, $formData));
    }


    /**
     * @group categoria-ingrediente
     */
    public function test_usuario_puede_editar_categoria()
    {
        $this->actingAs($this->user);

        $categoria = CategoriaIngrediente::factory()->create(["user_id"=>$this->user->id]);

        $ruta_edit = route("ingredientes.categoria.edit", compact("categoria"));
        $ruta_update = route("ingredientes.categoria.update", compact("categoria"));
        $ruta_index = route("ingredientes.categoria.index");

        $response = $this->get($ruta_edit);
        $response->assertViewIs("ingredientes.categorias.edit");
        $response->assertViewHas("categoria");

        $formData = $this->getFormData();
        $response = $this->put($ruta_update, $formData);
        $response->assertRedirect($ruta_index);

        $categoria = CategoriaIngrediente::find(1);
        $this->assertNotNull($categoria);

        $this->assertTrue($this->comparaCategoria($categoria, $formData));
    }


    /**
     * @group categoria-ingrediente
     */
    public function test_usuario_puede_eliminar_categoria()
    {
        $this->actingAs($this->user);
        
        $categoria = CategoriaIngrediente::factory()->create(["user_id"=>$this->user->id]);

        $ruta_borrar = route("ingredientes.categoria.destroy", compact("categoria"));
        $ruta_index = route("ingredientes.categoria.index");

        $response = $this->delete($ruta_borrar);
        $response->assertRedirect($ruta_index);

        $this->assertEquals(0, CategoriaIngrediente::all()->count());
    }




    private function getFormData($overrides = [])
    {
        $categoria = CategoriaIngrediente::factory()->make(["user_id"=>$this->user->id]);

        return array_merge([
            'nombre' => $categoria->nombre,
            'descripcion' => $categoria->descripcion,
            'categoria'=>'',
        ], $overrides);
    }


    private function comparaCategoria(CategoriaIngrediente $categoria, array $data)
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
