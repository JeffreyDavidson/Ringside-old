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
            'email' => "jeffrey@example.com",
            'password' => bcrypt('secret'),
        ]);
    }
}
