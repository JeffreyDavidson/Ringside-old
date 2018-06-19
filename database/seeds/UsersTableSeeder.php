<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class)->states('super-admin')->create([
            'name' => 'Jeffrey Davidson',
            'email' => 'jeffrey@example.com',
            'password' => bcrypt('secret'),
        ]);

        factory(App\Models\User::class, 2)->states('super-admin')->create();
        factory(App\Models\User::class, 5)->states('admin')->create();
        factory(App\Models\User::class, 2)->states('editor')->create();
        factory(App\Models\User::class, 20)->states('user')->create();
    }
}
