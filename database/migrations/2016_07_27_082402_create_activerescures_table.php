
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActiverescuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activerescuers', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('rescuee_id')->unsigned();
            $table->longText('rescuers_ids');
            $table->string('emergency_type');
            $table->longText('emergency_ids');
            $table->timestamps();
            $table->foreign('rescuee_id')
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
    public function down()
    {
        Schema::table('activerescuers', function (Blueprint $table) {
            $table->dropForeign('activerescuers_rescuee_id_foreign');
        });
        Schema::drop('activerescuers');
    }
}

