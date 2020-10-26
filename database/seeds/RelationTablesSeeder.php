<?php

use Illuminate\Database\Seeder;

class RelationTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channel_user = [
            ["channel_id" => 1, "user_id" => 1],
            ["channel_id" => 1, "user_id" => 2],
            ["channel_id" => 2, "user_id" => 1],
            ["channel_id" => 2, "user_id" => 2],
        ];
        $permission_role = [
            ["permission_id" => 1, "role_id" => 1],
            ["permission_id" => 2, "role_id" => 1],
            ["permission_id" => 3, "role_id" => 1],
            ["permission_id" => 4, "role_id" => 1],
            ["permission_id" => 5, "role_id" => 1],
            ["permission_id" => 6, "role_id" => 1],
            ["permission_id" => 7, "role_id" => 1],
            ["permission_id" => 8, "role_id" => 1],
            ["permission_id" => 9, "role_id" => 1],
            ["permission_id" => 10, "role_id" => 1],
            ["permission_id" => 11, "role_id" => 1],

            ["permission_id" => 1, "role_id" => 2],
            ["permission_id" => 2, "role_id" => 2],
            ["permission_id" => 3, "role_id" => 2],
            ["permission_id" => 4, "role_id" => 2],
            ["permission_id" => 6, "role_id" => 2],
            ["permission_id" => 11, "role_id" => 2],

            ["permission_id" => 1, "role_id" => 3],
            ["permission_id" => 2, "role_id" => 3],
            ["permission_id" => 3, "role_id" => 3],
            ["permission_id" => 4, "role_id" => 3],
            ["permission_id" => 11, "role_id" => 3],
        ];
        $user_role = [
            ["user_id" => 1, "role_id" => 1],
            ["user_id" => 2, "role_id" => 3],
        ];
        
        DB::table("channel_user")->insert($channel_user);
        DB::table("permission_role")->insert($permission_role); 
        DB::table("user_role")->insert($user_role);
    }
}
