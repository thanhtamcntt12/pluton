<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateType3DiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_3_diagnoses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_3_diagnosis_id');
            $table->string('type_3_diagnosis_name');
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
        Schema::drop('type_3_diagnoses');
    }
}
