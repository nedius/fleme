<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ["id" => 1, "name" => "Read Messages", "slug" => "read-messages", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 2, "name" => "Write Messages", "slug" => "write-messages", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 3, "name" => "Edit Messages", "slug" => "edit-messages", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 4, "name" => "Delete Messages", "slug" => "delete-messages", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 5, "name" => "Edit Others Messages", "slug" => "edit-others-messages", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 6, "name" => "Delete Others Messages", "slug" => "delete-others-messages", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 7, "name" => "Create Channel", "slug" => "create-channel", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 8, "name" => "Edit Channel", "slug" => "edit-channel", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 9, "name" => "Delete Channel", "slug" => "delete-channel", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 10, "name" => "Edit Roles", "slug" => "edit-roles", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 11, "name" => "Create DM Channels", "slug" => "create-dm-channel", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
        ];
        App\Permission::insert($permissions);
    }
}
