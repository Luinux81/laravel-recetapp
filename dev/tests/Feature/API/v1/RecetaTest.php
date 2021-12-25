<?php

namespace Tests\Feature\API\v1;

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


    public function test_usuario_puede_ver_listado()
    {
        $this->actingAs($this->user);
        
        Receta::factory()->count(3)->create(["user_id"=>$this->user->id]);
        Receta::factory()->create(["user_id"=>$this->user->id]);
        
        $ruta = route("api.v1.recetas.index");

        $reponse = $this->get($ruta);
        $reponse->assertOk();

        $response = $this->get($ruta)->decodeResponseJson();

        $response->assertStructure([
            '*' => [
                'id',
                'user_id',
                'cat_id',
                'nombre',
                'descripcion',
                'raciones',
                'tiempo',
                'imagen',
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
                //'deleted_at',
                'created_at',
                'updated_at',
            ]
        ]);

        $this->assertEquals(4, $response->count());
    }



    public function test_usuario_puede_ver_receta()
    {
        $this->actingAs($this->user);

        $receta = Receta::factory()->create(["user_id" => $this->user->id]);

        $ruta = route("api.v1.recetas.show", compact("receta"));

        $response = $this->get($ruta);
        $response->assertOk();

        $response
            ->decodeResponseJson()
            ->assertStructure([
                'id',
                'user_id',
                'cat_id',
                'nombre',
                'descripcion',
                'raciones',
                'tiempo',
                'imagen',
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
                'created_at',
                'updated_at',
        ]);

        $this->assertTrue($this->comparaReceta($receta, $response->json()));
    }


    public function test_usuario_puede_crear_receta()
    {
        $this->actingAs($this->user);

        $ruta = route("api.v1.recetas.store");

        $data = $this->getFormData();

        $response = $this->post($ruta, $data);
        $response->assertCreated();
        $this->assertEquals(1, Receta::all()->count());

        $receta = Receta::find(1);
        $this->assertNotNull($receta);
        $this->assertTrue($this->comparaReceta($receta, $data));
    }


    public function test_usuario_puede_editar_receta()
    {
        $this->actingAs($this->user);

        $receta = Receta::factory()->create(["user_id"=>$this->user->id]);
        $ruta = route("api.v1.recetas.update", compact("receta"));

        $data = $this->getFormData();

        $response = $this->put($ruta, $data);
        $response->assertOk();

        $receta = Receta::find(1);
        $this->assertNotNull($receta);
        $this->assertTrue($this->comparaReceta($receta, $data));
    }


    Public function test_usuario_puede_eliminar_receta()
    {
        $this->actingAs($this->user);

        $receta = Receta::factory()->create(["user_id"=>$this->user->id]);

        $ruta = route("api.v1.recetas.destroy", compact("receta"));

        $response = $this->delete($ruta);
        $response->assertOk();
        $this->assertEquals(0, Receta::all()->count());

    }


    private function getFormData($overrides = [])
    {
        $receta = Receta::factory()->make();

        return array_merge([
            'categoria' => $receta->cat_id,
            'nombre' => $receta->nombre,
            'descripcion' => $receta->descripcion,
            'raciones' => $receta->raciones,
            'tiempo' => $receta->tiempo,
            //'imagen' => $receta->imagen,
            'calorias' => $receta->calorias,
            // 'fat_saturadas' => $receta->fat_saturadas,
            // 'fat_poliinsaturadas' => $receta->fat_poliinsaturadas,
            // 'fat_monoinsaturadas' => $receta->fat_monoinsaturadas,
            // 'fat_trans' => $receta->fat_trans,
            // 'colesterol' => $receta->colesterol,
            // 'sodio' => $receta->sodio,
            // 'potasio' => $receta->potasio,
            // 'fibra' => $receta->fibra,
            // 'carb_total' => $receta->carb_total,
            // 'carb_azucar' => $receta->carb_azucar,
            // 'proteina' => $receta->proteina,
            // //'deleted_at' => $receta->,
            //'created_at' => $receta->created_at,
            //'updated_at' => $receta->updated_at,
        ], $overrides);
    }



    private function comparaReceta(Receta $receta, array $data) : bool
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
                    if($receta->$key != $value){
                    $res = false;
                    break;
                    }
                }
            }
        }

        return $res;
    }
}
