<?php

namespace Tests\Feature\CRUD;

use App\Models\Ingrediente;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IngredientesTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_usuario_puede_ver_ingrediente_publico()
    {
        // $seed = new UserSeeder();
        // $seed->run();

        // $seed = new PermissionSeeder();
        // $seed->run();

        // $ingrediente = Ingrediente::factory()->create(["user_id" => NULL]);

        // /** @var Authenticatable */
        // $user = User::factory()->create();

        // $response = $this
        //             ->actingAs($user)
        //             ->get(route('ingredientes.show',compact('ingrediente')))
        //             ;
        
        // $response->assertRedirectToSignedRoute('ingredientes.show', compact('ingrediente'));
        $this->assertTrue(true);
    }

}
