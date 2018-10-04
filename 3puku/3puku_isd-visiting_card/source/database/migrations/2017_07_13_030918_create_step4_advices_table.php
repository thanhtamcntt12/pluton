<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStep4AdvicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step4_advices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_3_diagnosis')->nullable();
            $table->integer('type_12_intent_diagnosis')->nullable();
            $table->text('type_3_advice')->nullable();
            $table->text('type_12_advice')->nullable();
            $table->tinyInteger('is_deleted');
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
        Schema::drop('step4_advices');
    }
}
