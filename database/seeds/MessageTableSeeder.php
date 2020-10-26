<?php

use Illuminate\Database\Seeder;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $message = [
            ["id" => 1, "user_id" => "1", "channel_id" => "1", "content" => "Hello there", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => null],
            ["id" => 2, "user_id" => "2", "channel_id" => "1", "content" => "hi )", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => null],
            ["id" => 3, "user_id" => "1", "channel_id" => "1", "content" => "You remember about that thing ?", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => null],
            ["id" => 4, "user_id" => "2", "channel_id" => "1", "content" => "What exactly ?", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => null],
            ["id" => 5, "user_id" => "2", "channel_id" => "1", "content" => "aaa yes i remember", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => null],
            ["id" => 6, "user_id" => "2", "channel_id" => "2", "content" => "Here is nothing ...", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => null],
            ["id" => 7, "user_id" => "1", "channel_id" => "2", "content" => "yea", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => null],
            ["id" => 8, "user_id" => "2", "channel_id" => "2", "content" => "hey", "created_at" => "2020-01-07 12:46:49", "updated_at" => "2020-01-07 12:46:49", "deleted_at" => "2020-01-07 12:46:49"],
        ];
        App\Message::insert($message);
    }
}
