<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

<<<<<<< HEAD
=======
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

>>>>>>> 315cc16c0b22309447497a0584b4df3ab55431d3
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
<<<<<<< HEAD
        	'name' => 'Luis Fax',
        	'phone' => '3511159550',
        	'email' => 'luisfaax@gmail.com',
        	'profile' => 'ADMIN',
        	'status' => 'ACTIVE',
        	'password' => bcrypt('123')
        ]);
        User::create([
        	'name' => 'Melisa Albahat',
        	'phone' => '3549873214',
        	'email' => 'melisa@gmail.com',
        	'profile' => 'EMPLOYEE',
        	'status' => 'ACTIVE',
        	'password' => bcrypt('123')
        ]);
=======
            'name' => 'Luis Fax',
            'phone' => '3511159550',
            'email' => 'luisfaax@gmail.com',
            'profile' => 'ADMIN',
            'status' => 'ACTIVE',
            'password' => bcrypt('123')
        ]);
        User::create([
            'name' => 'Melisa Albahat',
            'phone' => '3549873214',
            'email' => 'melisa@gmail.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVE',
            'password' => bcrypt('123')
        ]);

        // crear role Administrador
        $admin    = Role::create(['name' => 'Admin']);

        // crear permisos componente categories
        Permission::create(['name' => 'Category_Create']);
        Permission::create(['name' => 'Category_Search']);
        Permission::create(['name' => 'Category_Update']);
        Permission::create(['name' => 'Category_Destroy']);

        // asignar permisos al rol Admin sobre categories
        $admin->givePermissionTo(['Category_Create', 'Category_Search', 'Category_Update', 'Category_Destroy']);

        // asignar role Admin al usuario Luis Fax
        $uAdmin = User::find(1);
        $uAdmin->assignRole('Admin');
>>>>>>> 315cc16c0b22309447497a0584b4df3ab55431d3
    }
}
