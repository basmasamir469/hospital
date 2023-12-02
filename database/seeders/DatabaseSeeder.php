<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(DepartmentSeeder::class);
        $admin = User::create([
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'mobile'   => '01234567890',
            'password' => Hash::make(12345678),
        ]);
        $role = Role::where('name','admin')->first();
        $admin->assignRole($role);


    }
}
