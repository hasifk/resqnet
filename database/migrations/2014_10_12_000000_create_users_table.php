<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('membership_no');
            $table->string('firstname');
            $table->string('lastname');
            $table->date('dob');
            $table->integer('country_id')->unsigned();
            $table->string('rescuer_type');
            $table->integer('area_id')->unsigned();
            $table->string('displayname');
            $table->string('jurisdiction');
            $table->integer('dept_id')->unsigned();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->longText('current_medical_conditions');
            $table->longText('prior_medical_conditions');
            $table->longText('allergies');
            $table->integer('phone');
            $table->string('subscription_id');
            $table->string('subscription_info');
            $table->string('subscription_plan');
            $table->timestamp('subscription_ends_at');
            $table->string('confirmation_code');
            $table->boolean('confirmed')->default(config('access.users.confirm_email') ? false : true);
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
