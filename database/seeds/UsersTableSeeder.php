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
        $users = [
            ["id" => 1, "name" => "admin", "image" => "/storage/images/users/1.png", "email" => "admin@admin.com", "password" => '$2y$10$eDWv2pVtwNXtxPpzWWGaCuQC0B.KJqc6DUJei5e8p9zfr25gecYFG', "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 2, "name" => "user", "image" => "/storage/images/users/2.png", "email" => "user@user.com", "password" => '$2y$10$eDWv2pVtwNXtxPpzWWGaCuQC0B.KJqc6DUJei5e8p9zfr25gecYFG', "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"]
        ];
        App\User::insert($users);
    }
}
