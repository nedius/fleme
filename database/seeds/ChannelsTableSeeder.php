<?php

use Illuminate\Database\Seeder;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channels = [
            ["id" => 1, "name" => "General", "type" => "text", "position" => 0, "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
            ["id" => 2, "name" => "Second", "type" => "text", "position" => 1, "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49"],
        ];
        App\Channel::insert($channels);
    }
}
