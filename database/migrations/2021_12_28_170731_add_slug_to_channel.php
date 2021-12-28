<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToChannel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("channels", function (Blueprint $table) {
            $table->string("slug", 500)->nullable();
            $table->index("slug");

            $table->string("country")->nullable();
            $table->index("country");
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
