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
        //AdminRole
        $admin = Role::where('name', 'Admin')->exists();
        if (!$admin) {
            $admin = Role::create(['name' => 'Admin']);
        }
        //userRole
        $user = Role  ::where('name', 'User')->exists();
        if (!$user) {
            $user = Role::create(['name' => 'User']);
        }


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
        Permission::create(['name' => 'blog.uploadimage']);
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
        Permission::create(['name' => 'comment.show']);
        Permission::create(['name' => 'comment.update']);
        Permission::create(['name' => 'comment.destroy']);
        Permission::create(['name' => 'comment.restore']);
        Permission::create(['name' => 'comment.approve']);
        Permission::create(['name' => 'comment.reject']);

        //permission   //PasswordChange
        Permission::create(['name' => 'PasswordChange']);

        //permission   //PasswordReset
        Permission::create(['name' => 'PasswordReset']);

        //Discount_code   //permission
        Permission::create(['name' => 'discount.index']);
        Permission::create(['name' => 'discount.store']);
        Permission::create(['name' => 'discount.update']);
        Permission::create(['name' => 'discount.destroy']);
        Permission::create(['name' => 'discount_give']);

        //Emrgency_contact   //permission
        Permission::create(['name' => 'emrgency.index']);
        Permission::create(['name' => 'emrgency.store']);
        Permission::create(['name' => 'emrgency.update']);
        Permission::create(['name' => 'emrgency.destroy']);

        //Newslatter   //Permission
        Permission::create(['name' => 'news.index']);
        Permission::create(['name' => 'news.store']);

        //Passenger   //Permission
        Permission::create(['name' => 'passenger.index']);
        Permission::create(['name' => 'passenger.store']);
        Permission::create(['name' => 'passenger.update']);
        Permission::create(['name' => 'passenger.destroy']);

        //Reservation   //Permission
        Permission::create(['name' => 'reservation.index']);
        Permission::create(['name' => 'reservation.store']);
        Permission::create(['name' => 'reservation.update']);
        Permission::create(['name' => 'reservation.destroy']);

        //Ticket  //Permission
        Permission::create(['name' => 'ticket.index']);
        Permission::create(['name' => 'ticket.store']);
        Permission::create(['name' => 'ticket.update']);
        Permission::create(['name' => 'ticket.destroy']);
        Permission::create(['name' => 'ticket.response']);

        //Tickett   //ermission   //بلیط
        Permission::create(['name' => 'tickett.index']);

        //Report    //Permission
        Permission::create(['name' => 'index.report']);
        Permission::create(['name' => 'show.report']);

       

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
            'product.uploadimage',
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
            'comment.show',
            // 'order.index',
            // 'order.store',
            // 'order.update',
            // 'order.destroy',
            // 'order.show',
            'PasswordReset',
            'discount.index',
            'discount.store',
            'discount.update',
            'discount.destroy',
            'emrgency.index',
            'emrgency.store',
            'emrgency.update',
            'emrgency.destroy',
            'news.index',
            'news.store',
            'passenger.index',
            'passenger.store',
            'passenger.update',
            'passenger.destroy',
            'reservation.index',
            'reservation.store',
            'reservation.update',
            'reservation.destroy',
            'ticket.index',
            'ticket.store',
            'ticket.update',
            'ticket.destroy',
            'ticket.response',
            'tickett.index',
            'discount_give',
            'comment.approve',
            'comment.reject',
            'index.report',
            'show.report',
        ]);

        //give permission to user
        $user->syncPermissions([
            'product.index',
            'user.update',
            'user.me',
            'user.uploadprofile',
            'comment.store',
            // 'order.store',
            // 'order.destroy',
            'PasswordReset',
            'emrgency.store',
            'emrgency.update',
            'news.store',
            'passenger.store',
            'passenger.update',
            'passenger.destroy',
            'reservation.store',
            'reservation.destroy',
            'ticket.store',
        ]);


        $omid = User::create([
            'first_name' => 'omid',
            'last_name' => 'asgari',
            'phone_number' => '09339244978',
            'age' => '22',
            'national_code' => '4120791807',
            'email' => 'omidasgary966@gmail.com',
            'password' => '11111111',
        ]);
        $omid->assignRole('Admin');
    }
}
