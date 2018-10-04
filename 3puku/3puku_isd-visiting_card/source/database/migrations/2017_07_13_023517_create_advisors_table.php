<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('last_name',50);
            $table->string('first_name',50);
            $table->string('last_name_kana',50);
            $table->string('first_name_kana',50);
            $table->string('email',255);
            $table->string('password',255);
            $table->string('login_id',50)->nullable()->unique();
            $table->text('note')->nullable();
            $table->tinyInteger('is_deleted');
            $table->string('api_token')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('advisors');
    }
}
