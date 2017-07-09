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
        $users = factory(App\User::class, 100)->create();
        $user = $users->first();
        $user->update([
            'email' => '353856593@qq.com',
            'name' => 'xiaomiao',
            'password' => bcrypt('123456'),
        ]);
    }
}
