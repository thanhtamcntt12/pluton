<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeaconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beacons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);
            $table->uuid('uuid');
            $table->integer('major',false, false)->length(10)->nullable();
            $table->integer('minor',false, false)->length(10)->nullable();
            $table->boolean('type',1);
            $table->integer('guide_id',false, false)->length(10)->nullable();
            $table->integer('rev',false, false)->length(10)->nullable();
            $table->boolean('is_deleted',1);
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
        Schema::dropIfExists('beacons');
    }
}
