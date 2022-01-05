<?php

namespace Tests\Feature\API\v1;

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
            PermissionSeeder::class
        ]);

        $this->admin = User::find(1);
        $this->user = User::factory()->create();

        $this->receta = Receta::factory()->create(["user_id"=>$this->user->id]);

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
    public function test_usuario_puede_ver_pasos_receta()
    {
        $this->actingAs($this->user);

        $ruta = route("api.v1.recetas.paso.index", ["receta"=> $this->receta->id]);

        $response = $this->get($ruta)->decodeResponseJson();

        $response->assertStructure([
                '*' => [
                    'id',
                    'receta_id',                    
                    'orden',
                    'texto',
                    'media_assets',
                ]
            ]);

        $this->assertEquals(3, $response->count());
    }


    /** @group pasos_receta */
    public function test_usuario_puede_ver_paso_receta()
    {
        $this->actingAs($this->user);

        $receta = $this->receta;
        $paso = $receta->pasos()->first();

        $ruta = route("api.v1.recetas.paso.show", compact("receta", "paso"));

        $response = $this->get($ruta);
        $response->assertOk();

        $response
            ->decodeResponseJson()
            ->assertStructure([
                'id',
                'receta_id',                    
                'orden',
                'texto',
                'media_assets',
            ]);
    }


    /** @group pasos_receta */
    public function test_usuario_puede_crear_paso_receta()
    {
        $this->actingAs($this->user);

        $ruta = route("api.v1.recetas.paso.store", ["receta" => $this->receta->id]);

        $data = $this->getFormData();

        $response = $this->post($ruta, $data);
        $response->assertCreated();
        $this->assertEquals(4, $this->receta->pasos()->count());

        $response->decodeResponseJson()->assertStructure([
            'id',
            'receta_id',
            'orden',
            'texto'
        ]);


        $this->assertTrue($this->comparaPasoReceta(PasoReceta::find($response->json()["id"]), $data));

        $this->assertTrue($this->pasosEnOrden($this->receta));
    }


    /** @group pasos_receta */
    public function test_usuario_puede_editar_paso_receta()
    {
        $this->actingAs($this->user);

        $receta = $this->receta;
        $paso = $receta->pasos()->first();
        $ruta = route("api.v1.recetas.paso.update", compact("receta", "paso"));


        $data = $this->getFormData();

        $response = $this->put($ruta, $data);
        $response->assertOk();

        $response->decodeResponseJson()->assertStructure([
            'id',
            'receta_id',
            'orden',
            'texto'
        ]);

        $this->assertTrue($this->comparaPasoReceta(PasoReceta::find($response->json()["id"]), $data));

        $this->assertTrue($this->pasosEnOrden($this->receta));
    }


    /** @group pasos_receta */
    public function test_usuario_puede_eliminar_paso_receta()
    {
        $this->actingAs($this->user);

        $receta = $this->receta;
        $paso = $receta->pasos()->first();
        $ruta = route("api.v1.recetas.paso.destroy", compact("receta", "paso"));

        $response = $this->delete($ruta);
        $response->assertOk();

        $this->assertEquals(2, $receta->pasos()->count());
        $this->assertTrue($this->pasosEnOrden($this->receta));
    }





    private function getFormData($overrrides = [])
    {
        $paso = PasoReceta::factory()->make();

        return array_merge([
            "orden" => $paso->orden,
            "texto" => $paso->texto
        ], $overrrides );
    }


    private function comparaPasoReceta(PasoReceta $paso, $data)
    {
        if ($paso->orden != $data["orden"]) return false;
        if ($paso->texto != $data["texto"]) return false;

        return true;
    }

    private function pasosEnOrden(Receta $receta)
    {
        $pasos = $receta->pasos()->orderBy('orden')->get();
        $res = true;
        $i = 1;

        foreach ($pasos as $paso) {
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
