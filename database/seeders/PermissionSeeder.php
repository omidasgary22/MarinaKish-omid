<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission as ContractsPermission;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
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
        Permission::create(['name' => 'user.me']);
        Permission::create(['name' => 'user.uploadprofile']);

        //permission   //product
        Permission::create(['name' => 'product.index']);
        Permission::create(['name' => 'product.store']);
        Permission::create(['name' => 'product.show']);
        Permission::create(['name' => 'product.update']);
        Permission::create(['name' => 'product.delete']);
        Permission::create(['name' => 'product.restore']);
        Permission::create(['name' => 'product.uploadimage']);

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
        Permission::create(['name' => 'bloge.uploadimage']);
        //total removal from the data base Route
        Permission::create(['name' => 'blog.forcedelete']);

        //permissin   //FAQ
        Permission::create(['name' => 'faq.index']);
        Permission::create(['name' => 'faq.store']);
        Permission::create(['name' => 'faq.update']);
        Permission::create(['name' => 'faq.delete']);
        Permission::create(['name' => 'faq.restore']);

        //Permission   //comment
        Permission::create(['name' => 'comment.index']);
        Permission::create(['name' => 'comment.store']);
        Permission::create(['name' => 'comment.update']);
        Permission::create(['name' => 'comment.destroy']);
        Permission::create(['name' => 'comment.restore']);

        //permission   //order
        Permission::create(['name' => 'order.index']);
        Permission::create(['name' => 'order.store']);
        Permission::create(['name' => 'order.update']);
        Permission::create(['name' => 'order.destroy']);
        Permission::create(['name' => 'order.show']);

        //permission   //PasswordChange
        Permission::create(['name' => 'PasswordChange']);


        //give permission to admin
        $admin->syncPermissions([
            'user.index',
            'user.update',
            'user.delete',
            'user.restore',
            'user.me',
            'user.uploadprofile',
            'PasswordChange',
            'product.index',
            'product.store',
            'product.show',
            'product.update',
            'product.delete',
            'product.restore',
            'product.uploadeimage',
            'rule.index',
            'rule.store',
            'rule.update',
            'rule.destroy',
            'rule.restore',
            'blog.index',
            'blog.store',
            'blog.show',
            'blog.update',
            'blog.destroy',
            'blog.restore',
            'blog.uploadimage',
            'blog.forcedelete',
            'faq.index',
            'faq.store',
            'faq.update',
            'faq.delete',
            'faq.restore',
            'comment.index',
            'comment.store',
            'comment.update',
            'comment.destroy',
            'comment.restore',
            'order.index',
            'order.store',
            'order.update',
            'order.destroy',
            'order.show',
        ]);

        //give permission to user
        $user->syncPermissions([
            'user.update',
            'user.me',
            'user.uplodprofile',
            'comment.store',
            'order.store',
            'order.delete',
        ]);
    }
}
