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
        Permission::create(['name' => 'user.uploadeprofile']);

        //permission   //product
        Permission::create(['name' => 'product.index']);
        Permission::create(['name' => 'product.store']);
        Permission::create(['name' => 'product.show']);
        Permission::create(['name' => 'product.update']);
        Permission::create(['name' => 'product.delete']);
        Permission::create(['name' => 'product.restore']);
        Permission::create(['name' => 'product,uploadeimage']);

        //permission   //Rule
        Permission::create(['name' => 'rule.index']);
        Permission::create(['name' => 'rule.store']);
        Permission::create(['name' => 'rule.update']);
        Permission::create(['name' => 'rule.destroy']);
        Permission::create(['name' => 'rule.restore']);

        //permission   //Blog
        Permission::create(['name' => 'blog.index']);
        Permission::create(['name' => 'blog.store']);
        Permission::create(['name' => 'blog.show']);
        Permission::create(['name' => 'blog.update']);
        Permission::create(['name' => 'blog.destroy']);
        Permission::create(['name' => 'blog.restore']);
        Permission::create(['name' => 'bloge.uploadeimage']); 
        //total removal from the data base Route
        Permission::create(['name' => 'blog.forcedelete']);
    }
}
