<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsfeedsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('newsfeeds', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('newsfeed_type');
            $table->integer('user_id')->unsigned();
            $table->longText('group_id');
            $table->integer('countryid')->unsigned();
            $table->integer('areaid')->unsigned();
            $table->string('news_title');
            $table->longText('news');
            $table->string('image_filename');
            $table->string('image_extension');
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('newsfeeds', function (Blueprint $table) {
            $table->dropForeign('newsfeeds_user_id_foreign');
        });

        Schema::drop('newsfeeds');
    }

}
