<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class, 50)->create();
        $user = $users->first();
        $user->update([
            'email' => '353856593@qq.com',
            'name' => 'xiaomiao',
            'password' => bcrypt('123456'),
        ]);
    }
}
