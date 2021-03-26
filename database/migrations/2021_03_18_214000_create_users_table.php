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
            $table->string('login')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('patronymic')->nullable();
            $table->string('password');
            $table->unsignedInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups');
            $table->unsignedInteger('permission_id')->default(1);
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unsignedInteger('companies_id')->nullable();
            $table->foreign('companies_id')->references('id')->on('companies');
            $table->string('avatar')->default(rand(0, 100) > 50 ? 'images/default-avatar.jpg' : 'images/default-avatar2.jpg');
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
