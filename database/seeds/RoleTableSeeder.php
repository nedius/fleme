<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ["id" => 1, "name" => "Admin", "slug" => "admin", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 2, "name" => "Moderator", "slug" => "moderator", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 3, "name" => "User", "slug" => "user", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
        ];
        App\Role::insert($roles);
    }
}
