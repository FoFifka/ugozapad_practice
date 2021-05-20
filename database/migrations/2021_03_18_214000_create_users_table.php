<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('patronymic')->nullable();
            $table->unsignedInteger('gender_id');
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->string('password');
            $table->string('email')->unique();
            $table->unsignedInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups');
            $table->unsignedInteger('permission_id')->default(1);
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedInteger('mark_id')->nullable();
            $table->foreign('mark_id')->references('id')->on('marks');
            $table->text('about_me')->nullable();
            $table->string('avatar')->default('default_img/default-avatar.jpg');
            $table->text('api_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
