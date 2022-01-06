<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivityLastStream extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("channels", function (Blueprint $table) {
            $table->boolean("online")->default(0);
            $table->text("online_streams")->default("[]");
            $table->timestamp("last_stream_date")->nullable();
            $table->text("last_streams")->default("[]");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("channels", function (Blueprint $table) {
            //
        });
    }
}
