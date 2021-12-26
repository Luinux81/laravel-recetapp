<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lista de roles
        $admin   = Role::create(['name'=>'Admin']);
        $empleado   = Role::create(['name'=>'Empleado']);
        $cliente = Role::create(['name'=>'Cliente']);

        // Lista de permisos
        Permission::create(['name'=>'permisos_index']);
        Permission::create(['name'=>'permisos_create']);
        Permission::create(['name'=>'permisos_edit']);
        Permission::create(['name'=>'permisos_destroy']);

        Permission::create(['name'=>'public_index']);
        Permission::create(['name'=>'public_create']);
        Permission::create(['name'=>'public_edit']);
        Permission::create(['name'=>'public_destroy']);

        Permission::create(['name'=>'seeder_save']);

        Permission::create(['name'=>'private_access']);

        // Asignación de permisos a roles
        $admin->givePermissionTo([
            'permisos_index',
            'permisos_create',
            'permisos_edit',
            'permisos_destroy',
            'public_index',
            'public_create',
            'public_edit',
            'public_destroy',
            'seeder_save',
            'private_access',
        ]);

        $empleado->givePermissionTo([
            'public_index',
        ]);

        $cliente->givePermissionTo([
            'public_index',
        ]);

        $user = User::find(1);
        $user->assignRole($admin);
    }
}
