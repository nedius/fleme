<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_user', function (Blueprint $table) {
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::table("channel_user", function($table) {
            $table->primary(["channel_id", "user_id"]);
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("channel_id")->references("id")->on("channels");
            // $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            // $table->foreign("channel_id")->references("id")->on("channels")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_user');
    }
}
