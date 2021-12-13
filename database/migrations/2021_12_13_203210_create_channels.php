<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("channels", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("youtube_id");
            $table->string("name", 500);
            $table->string("avatar", 500);
            $table->text("data");
            $table->index("youtube_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("channels");
    }
}
