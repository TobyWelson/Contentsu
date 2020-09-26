<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents_posts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('user_id');
            $table->string('title', 255);
            $table->string('category', 2);
            $table->unsignedInteger('view_count');
            $table->string('url', 900);
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents_posts');
    }
}
