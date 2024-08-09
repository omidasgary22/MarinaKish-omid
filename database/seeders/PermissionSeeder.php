<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission as ContractsPermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Role
        $admin = Role::create(['name' => 'Admin']);
        $user = Role::create(['name' => 'user']);

        //permission   //user
        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.update']);
        Permission::create(['name' => 'user.delete']);
        Permission::create(['name' => 'user.restore']);
        Permission::create(['name' => 'me']);

        //permission   //product
        Permission::create(['name' => 'product.index']);
        Permission::create(['name' => 'product.store']);
        Permission::create(['name' => 'product.show']);
        Permission::create(['name' => 'product.update']);
        Permission::create(['name' => 'product.delete']);
        Permission::create(['name' => 'product.restore']);

       
    }
}
