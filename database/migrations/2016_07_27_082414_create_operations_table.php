<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('active_rescuers_id')->unsigned();
            $table->integer('rescuer_id')->unsigned();
            $table->dateTime('finished_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('rescuer_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('active_rescuers_id')
                ->references('id')
                ->on('activerescuers')
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
        Schema::table('operations', function (Blueprint $table) {
            $table->dropForeign('operations_rescuee_id_foreign');
            $table->dropForeign('operations_rescuer_id_foreign');
            $table->dropForeign('operations_active_rescuers_id_foreign');
        });
        Schema::drop('operations');
    }
}