<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->tinyInteger('user_type');
            $table->integer('type_3_diagnosis');
            $table->integer('type_12_diagnosis');
            $table->integer('type_12_intent_diagnosis');
            $table->integer('type_12_surface_diagnosis');
            $table->integer('type_60_diagnosis');
            $table->tinyInteger('is_deleted')->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('diagnoses');
    }
}
