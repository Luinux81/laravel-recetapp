<?php

namespace Tests\Unit;

use App\Helpers\Tools;
use App\Models\Asset;
use App\Models\CategoriaIngrediente;
use App\Models\CategoriaReceta;
use App\Models\Ingrediente;
use App\Models\Receta;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;

class ToolsTest extends TestCase
{
    use RefreshDatabase;

    /** @var Authenticatable */
    private $admin;

    /** @var Authenticatable */
    private $user;


    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UserSeeder::class,
            PermissionSeeder::class,
        ]);

        $this->admin = User::where('id',1)->first();
        $this->user = User::factory()->create();
        $this->user->assignRole(Role::findByName("Cliente"));
    }


    public function badValues() : array
    {
        $ing1 = new Ingrediente();
        $ing1->user_id = NULL;
        $ing2 = new Ingrediente();
        $ing2->user_id = 5;

        $rec1 = new Receta();
        $rec1->user_id = NULL;
        $rec2 = new Receta();
        $rec2->user_id = 5;

        $cat1 = new CategoriaReceta();
        $cat1->user_id = NULL;
        $cat2 = new CategoriaReceta();
        $cat2->user_id = 5;

        $cati1 = new CategoriaIngrediente();
        $cati1->user_id = NULL;
        $cati2 = new CategoriaIngrediente();
        $cati2->user_id = 5;       

        return [
            [$ing1],
            [$ing2],
            [$rec1],
            [$rec2],
            [$cat1],
            [$cat2],
            [$cati1],
            [$cati2],
        ];
    }


    public function test_check_or_fail_con_permisos(){
        
        $this->check( $this->admin, Ingrediente::factory()->make(["user_id" => NULL]) );
        
        $this->check( $this->admin, Receta::factory()->make(["user_id" => NULL]) );

        $this->check( $this->admin, CategoriaIngrediente::factory()->make(["user_id" => NULL]) );

        $this->check( $this->admin, CategoriaReceta::factory()->make(["user_id" => NULL]) );


        $this->actingAs($this->user);

        $this->assertTrue(Tools::checkOrFail(Ingrediente::factory()->make(["user_id" => $this->user->id])));
        $this->assertTrue(Tools::checkOrFail(Ingrediente::factory()->make(["user_id" => NULL]), "public_index"));

        $this->assertTrue(Tools::checkOrFail(Receta::factory()->make(["user_id" => $this->user->id])));
        $this->assertTrue(Tools::checkOrFail(Receta::factory()->make(["user_id" => NULL]), "public_index"));

        $this->assertTrue(Tools::checkOrFail(CategoriaIngrediente::factory()->make(["user_id" => $this->user->id])));
        $this->assertTrue(Tools::checkOrFail(CategoriaIngrediente::factory()->make(["user_id" => NULL]), "public_index"));

        $this->assertTrue(Tools::checkOrFail(CategoriaReceta::factory()->make(["user_id" => $this->user->id])));
        $this->assertTrue(Tools::checkOrFail(CategoriaReceta::factory()->make(["user_id" => $this->user->id]), "public_index"));


        $asset = new Asset();
        $this->expectExceptionCode(400);
        Tools::checkOrFail($asset,"public_index");
    }


    /**
     *
     * @dataProvider badValues
     */
    public function test_check_or_fail_sin_permisos($badValue)
    {
        $this->actingAs($this->user);

        $this->expectExceptionCode(401);
        Tools::checkOrFail($badValue);
    }


    private function check(User $user, object $model){
        $this->actingAs($user)->assertTrue(Tools::checkOrFail($model,"public_index"));
        $this->actingAs($user)->assertTrue(Tools::checkOrFail($model,"public_create"));
        $this->actingAs($user)->assertTrue(Tools::checkOrFail($model,"public_edit"));
        $this->actingAs($user)->assertTrue(Tools::checkOrFail($model,"public_destroy"));
    }
}
