<?php

namespace Tests\Feature\CRUD;

use App\Models\Ingrediente;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class IngredientesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @var Authenticatable */
    private $admin;

    /** @var Authenticatable */
    private $user;

    private $imageDir;

    public function setUp() : void
    {
        parent::setUp();

        $this->seed([
            UserSeeder::class,
            PermissionSeeder::class,
        ]);

        $this->admin = User::find(1);
        $this->user = User::factory()->create();
        $this->user->assignRole(Role::findByName("Cliente"));

        $this->imageDir = "users/" . $this->user->id . '/ingredientes/';
        Storage::fake(config('filesystems.default'));
        Storage::makeDirectory($this->imageDir);
    }


    /**
     * @group ingredientes
     */
    public function test_usuario_puede_ver_listado_ingredientes()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('ingredientes.index'));
        $response->assertViewIs("ingredientes.index");
        $response->assertViewHas(["ingredientes", "categorias"]);
    }

    /**
     * @group ingredientes
     */
    public function test_usuario_puede_ver_ingrediente()
    {
        $test = $this->actingAs($this->user);

        $ingredientePrivado = Ingrediente::factory()->create(["user_id"=>$this->user->id]);
        $ingredientePublico = Ingrediente::factory()->create(["user_id"=>NULL, "publicado" => 1]);

        $ruta_ing_privado = route("ingredientes.show", ["ingrediente" => $ingredientePrivado]);
        $ruta_ing_publico = route("ingredientes.show", ["ingrediente" => $ingredientePublico]);

        // La ruta ingredientes.show debe devolver la vista
        $response = $test->get($ruta_ing_privado);
        $response->assertViewIs("ingredientes.show");

        $response = $test->get($ruta_ing_publico);
        $response->assertViewIs("ingredientes.show");
    }

    /**
     * @group ingredientes
     */
    public function test_usuario_puede_crear_ingrediente()
    {
        $this->actingAs($this->user);

        $ruta_create = route("ingredientes.create");
        $ruta_store  = route("ingredientes.store");
        $ruta_index  = route("ingredientes.index");

        $response = $this->get($ruta_create);
        $response->assertViewIs("ingredientes.create");

        $response = $this->post($ruta_store, $this->getFormData(["user_id"=>$this->user->id]));
        $response->assertRedirect($ruta_index);
        $this->assertEquals(1, Ingrediente::count());
        $this->assertFileExists(Storage::path(Ingrediente::find(1)->imagen));
    }

    /**
     * @group ingredientes
     */
    public function test_usuario_puede_editar_ingrediente()
    {
        $this->actingAs($this->user);
        
        $ingrediente = Ingrediente::factory()->create(["user_id" => $this->user->id]);

        $ruta_edit = route("ingredientes.edit", ["ingrediente"=>$ingrediente]);
        $ruta_update = route("ingredientes.update", ["ingrediente"=>$ingrediente]);
        $ruta_index = route("ingredientes.index");

        $response = $this->get($ruta_edit);
        $response->assertViewIs("ingredientes.edit");
        $response->assertViewHas("ingrediente");

        $formData =  $this->getFormData(["user_id"=>$this->user->id]);

        $response = $this->put($ruta_update, $formData);
        $response->assertRedirect($ruta_index);

        $this->assertEquals(1, Ingrediente::count());

        $ingredienteBD = Ingrediente::find($ingrediente->id);
        $this->assertNotNull($ingredienteBD);

        $this->assertTrue($ingrediente->is($ingredienteBD));

        $this->assertTrue($this->comparaIngrediente($ingredienteBD, $formData));
        $this->assertFileExists(Storage::path($ingredienteBD->imagen));
    }

    /**
     * @group ingredientes
     */
    public function test_usuario_puede_eliminar_ingrediente()
    {
        $this->actingAs($this->user);

        $ingrediente = Ingrediente::factory()->create([
            "user_id" => $this->user->id,
            "imagen" => $this->getNuevaRutaImagen()
        ]);

        $this->assertEquals(1, Ingrediente::count());
        $this->assertFileExists(Storage::path($ingrediente->imagen));

        $response = $this->delete(route('ingredientes.destroy', ["ingrediente" => $ingrediente]));
        $response->assertRedirect(route("ingredientes.index"));

        $this->assertEquals(0, Ingrediente::count());
        $this->assertFileDoesNotExist(Storage::path($ingrediente->imagen));
    }

    /**
     * @group ingredientes
     */
    public function test_admin_puede_ver_ingrediente()
    {
        $this->actingAs($this->admin);

        $ingredientePrivado = Ingrediente::factory()->create(["user_id"=>$this->user->id]);
        $ingredientePublico = Ingrediente::factory()->create(["user_id"=>NULL]);

        $ruta_ing_privado = route("ingredientes.show", ["ingrediente" => $ingredientePrivado]);
        $ruta_ing_publico = route("ingredientes.show", ["ingrediente" => $ingredientePublico]);

        $response = $this->get($ruta_ing_privado);
        $response->assertViewIs("ingredientes.show");

        $response = $this->get($ruta_ing_publico);
        $response->assertViewIs("ingredientes.show");
    }

    /**
     * @group ingredientes
     */
    public function test_admin_puede_crear_ingrediente_publico()
    {
        $ruta_index = route("ingredientes.index");
        $ruta_create = route("ingredientes.create");
        $ruta_store = route("ingredientes.store");

        $this->actingAs($this->admin);

        $response = $this->get($ruta_create);
        $response->assertViewIs("ingredientes.create");

        
        $formData = $this->getFormData(["user_id" => NULL]);
        
        $response = $this->post($ruta_store, $formData);    
        $response->assertRedirect($ruta_index);

        $this->assertEquals(1, Ingrediente::count());
        
        $ingrediente = Ingrediente::find(1);
        $this->assertNotNull($ingrediente);

        $this->assertTrue($this->comparaIngrediente($ingrediente, $formData));
        $this->assertFileExists(Storage::path($ingrediente->imagen));
    }

    /**
     * @group ingredientes
     */
    public function test_admin_puede_editar_ingrediente_publico()
    {
        $this->actingAs($this->admin);
        
        $ingrediente = Ingrediente::factory()->create(["user_id" => NULL]);

        $ruta_edit = route("ingredientes.edit", ["ingrediente"=>$ingrediente]);
        $ruta_update = route("ingredientes.update", ["ingrediente"=>$ingrediente]);
        $ruta_index = route("ingredientes.index");

        $response = $this->get($ruta_edit);
        $response->assertViewIs("ingredientes.edit");
        $response->assertViewHas("ingrediente");

        $formData =  $this->getFormData(["user_id" => NULL]);

        $response = $this->put($ruta_update, $formData);
        $response->assertRedirect($ruta_index);

        $this->assertEquals(1, Ingrediente::count());

        $ingredienteBD = Ingrediente::find($ingrediente->id);
        $this->assertNotNull($ingredienteBD);

        $this->assertTrue($ingrediente->is($ingredienteBD));

        $this->assertTrue($this->comparaIngrediente($ingredienteBD, $formData));
        $this->assertFileExists(Storage::path($ingredienteBD->imagen));
    }

    /**
     * @group ingredientes
     */
    public function test_admin_puede_eliminar_ingrediente_publico()
    {
        $this->actingAs($this->admin);

        $ingrediente = Ingrediente::factory()->create([
            "user_id"=>NULL,
            "imagen" => $this->getNuevaRutaImagen()
        ]);

        $this->assertEquals(1, Ingrediente::count());
        $this->assertFileExists(Storage::path($ingrediente->imagen));

        $response = $this->delete(route('ingredientes.destroy', ["ingrediente" => $ingrediente]));
        $response->assertRedirect(route("ingredientes.index"));

        $this->assertEquals(0, Ingrediente::count());
        $this->assertFileDoesNotExist(Storage::path($ingrediente->imagen));
    }


    // TODO: Hacer algun test mas de validación 


    
    private function getFormData($overrides = [])
    {
        /** @var Ingrediente */ $ingrediente = Ingrediente::factory()->make();

        // TODO: Arreglar lo que pasa con imagen
        return array_merge([
            "nombre"                => $ingrediente->nombre,
            "descripcion"           => $ingrediente->descripcion,
            "marca"                 => $ingrediente->marca,
            "barcode"               => $ingrediente->barcode,
            "imagen"                => UploadedFile::fake()->image("ingrediente.jpg"), // para usar image hay que activar en php.ini la extension gd
            "url"                   => $ingrediente->url,
            "calorias"              => $ingrediente->calorias,
            "fat_total"             => $ingrediente->fat_total,
            "fat_saturadas"         => $ingrediente->fat_saturadas,
            "fat_poliinsaturadas"   => $ingrediente->fat_poliinsaturadas,
            "fat_monoinsaturadas"   => $ingrediente->fat_monoinsaturadas,
            "fat_trans"             => $ingrediente->fat_trans,
            "colesterol"            => $ingrediente->colesterol,
            "sodio"                 => $ingrediente->sodio,
            "potasio"               => $ingrediente->potasio,
            "fibra"                 => $ingrediente->fibra,
            "carb_total"            => $ingrediente->carb_total,
            "carb_azucar"           => $ingrediente->carb_azucar,
            "proteina"              => $ingrediente->proteina,
            "categoria"             => $ingrediente->categoria,
            "publicado"             => $ingrediente->publicado,
        ], $overrides);
    }

    private function comparaIngrediente (Ingrediente $ingrediente, array $data)
    {
        $res = true;

        foreach ($data as $key => $value) {
            if ($key == 'imagen'){
                continue;
            }

            if ($key == 'categoria'){
                $key = 'cat_id';
            }   
            
            if($ingrediente->$key != $value){
                if($key != "user_id" && $ingrediente->user_id != NULL){
                    $res = false;
                    break;
                }
            }

            
        }

        return $res;
    }

    private function getNuevaRutaImagen()
    {
        return $this->imageDir . $this->faker->file('/tmp',Storage::path($this->imageDir),false);
    }
}
