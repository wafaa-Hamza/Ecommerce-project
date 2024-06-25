<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $superAdmin = Role::create(['name' => 'super_admin']);
        $adminRole = Role::create(['name' => 'over_view']);
        $adminRole = Role::create(['name' => 'category']);
        $adminRole = Role::create(['name' => 'product']);
        $adminRole = Role::create(['name' => 'order']);
        $adminRole = Role::create(['name' => 'stock']);
        $messageRole = Role::create(['name' => 'message']);
        $adminRole = Role::create(['name' => 'shipping']);
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole = Role::create(['name' => 'setting']);
        $clientRole = Role::create(['name' => 'client']);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'phone' => '0123456789',
            'email_verified_at' => now()
        ]);

        $client = User::create([
            'name' => 'Kimo',
            'email' => 'kimogewaly@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '0123456789',
            'email_verified_at' => now()
        ]);

        $technical = User::create([
            'name' => 'Technical Support',
            'email' => 'technical_support@boutique.com',
            'password' => bcrypt('12345678'),
            'phone' => '0123456789',
            'email_verified_at' => now()
        ]);

        $admin->assignRole($superAdmin);
        $client->assignRole($superAdmin);
        $technical->assignRole($messageRole);

        // $users = User::factory(100)->create();
        // $users->each(function (User $user) use($adminRole){
        //     $user->assignRole($adminRole);
        // });

        // $users = User::factory(100)->create();
        // $users->each(function (User $user) use($clientRole){
        //     $user->assignRole($clientRole);
        // });
    }
}
