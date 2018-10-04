<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitingCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visiting_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id',false, true)->length(10);
            $table->integer('store_id',false, true)->length(10);
            $table->string('last_name',50)->nullable();
            $table->string('first_name',50)->nullable();
            $table->string('last_name_kana',50)->nullable();
            $table->string('first_name_kana',50)->nullable();
            $table->datetime('birthday');
            $table->string('postcode',8)->nullable();
            $table->string('address1',50)->nullable();
            $table->string('address2',50)->nullable();
            $table->string('mobile_phone',13)->nullable();
            $table->string('phone',15)->nullable();
            $table->string('fax',15)->nullable();
            $table->string('email',255)->nullable();
            $table->string('office_name',50)->nullable();
            $table->string('office_postcode',50)->nullable();
            $table->string('office_address1',50)->nullable();
            $table->string('office_address2',50)->nullable();
            $table->tinyInteger('question1_1_status')->nullable();
            $table->text('question1_1_detail');
            $table->tinyInteger('question2_1_1_status')->nullable();
            $table->tinyInteger('question2_1_2_status')->nullable();
            $table->tinyInteger('question2_1_3_status')->nullable();
            $table->tinyInteger('question2_1_4_status')->nullable();
            $table->tinyInteger('question2_1_5_status')->nullable();
            $table->tinyInteger('question2_1_6_status')->nullable();
            $table->text('question2_1_6_detail');
            $table->tinyInteger('question2_2_1_status')->nullable();
            $table->tinyInteger('question2_2_2_status')->nullable();
            $table->tinyInteger('question2_2_3_status')->nullable();
            $table->tinyInteger('question2_2_4_status')->nullable();
            $table->tinyInteger('question2_2_5_status')->nullable();
            $table->tinyInteger('question2_2_6_status')->nullable();
            $table->text('question2_2_6_detail');
            $table->tinyInteger('question2_3_1_status')->nullable();
            $table->tinyInteger('question2_3_2_status')->nullable();
            $table->tinyInteger('question2_3_3_status')->nullable();
            $table->tinyInteger('question2_3_4_status')->nullable();
            $table->tinyInteger('question2_3_5_status')->nullable();       
            $table->text('question2_3_5_detail');
            $table->tinyInteger('question2_4_1_status')->nullable();
            $table->tinyInteger('question2_4_2_status')->nullable();
            $table->tinyInteger('question2_4_3_status')->nullable();
            $table->tinyInteger('question2_4_4_status')->nullable();
            $table->tinyInteger('question2_4_5_status')->nullable();
            $table->tinyInteger('question2_4_6_status')->nullable();
            $table->text('question2_4_6_detail');
            $table->text('question3_1_detail');
            $table->tinyInteger('question3_2_1_status')->nullable();
            $table->tinyInteger('question3_2_2_status')->nullable();
            $table->tinyInteger('question3_2_3_status')->nullable();
            $table->text('question4_detail');
            $table->tinyInteger('question5_1_status')->nullable();
            $table->tinyInteger('question5_2_status')->nullable();
            $table->tinyInteger('question5_3_status')->nullable();
            $table->tinyInteger('question5_4_status')->nullable();
            $table->text('question5_4_detail');
            $table->tinyInteger('question5_5_status')->nullable();
            $table->tinyInteger('question5_7_status')->nullable();
            $table->tinyInteger('question5_8_status')->nullable();
            $table->tinyInteger('question5_9_status')->nullable();
            $table->tinyInteger('question5_10_status')->nullable();
            $table->tinyInteger('question5_11_status')->nullable();
            $table->text('question5_11_detail');
            $table->text('question6_detail');
            $table->text('question7_detail');
            $table->datetime('question8_1_1_date')->nullable();
            $table->tinyInteger('question8_1_2_status')->nullable();
            $table->string('question8_2_1_num',2)->nullable();
            $table->string('question8_2_2_num',2)->nullable();
            $table->string('question8_2_3_num',2)->nullable();
            $table->string('question8_2_4_num',2)->nullable();
            $table->string('question8_3_1_num',2)->nullable();
            $table->tinyInteger('question8_3_1_status')->nullable();
            $table->tinyInteger('question8_3_2_status')->nullable();
            $table->tinyInteger('question8_3_3_status')->nullable();
            $table->tinyInteger('question8_3_4_status')->nullable();
            $table->tinyInteger('question8_3_5_status')->nullable();
            $table->tinyInteger('question8_3_6_status')->nullable();
            $table->tinyInteger('question8_3_7_status')->nullable();
            $table->tinyInteger('question8_3_8_status')->nullable();
            $table->tinyInteger('question8_3_9_status')->nullable();
            $table->tinyInteger('question8_4_1_status')->nullable();
            $table->tinyInteger('question8_4_2_status')->nullable();
            $table->tinyInteger('question8_4_3_status')->nullable();
            $table->tinyInteger('question8_5_1_status')->nullable();
            $table->tinyInteger('question8_5_2_status')->nullable();
            $table->string('question8_6_1_num',5)->nullable();
            $table->string('question8_6_2_num',5)->nullable();
            $table->string('question8_7_1_num',5)->nullable();
            $table->string('question8_7_2_num',5)->nullable();
            $table->tinyInteger('question8_8_1_status')->nullable();
            $table->string('question8_8_2_num',2)->nullable();
            $table->string('question8_8_3_num',2)->nullable();
            $table->tinyInteger('question8_9_1_status')->nullable();
            $table->tinyInteger('question8_9_2_status')->nullable();
            $table->tinyInteger('question8_9_3_status')->nullable();
            $table->text('question8_10_1_detail');
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
        Schema::drop('visiting_cards');
    }
}
