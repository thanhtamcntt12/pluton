<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guides', function (Blueprint $table) {
            $table->increments('id');
            $table->string('panaromic_photo_noon',50)->nullable();
            $table->string('panaromic_photo_night',50)->nullable();
            $table->string('guide_voice_ja',50)->nullable();
            $table->string('guide_text_ja',50)->nullable();
            $table->string('landscape_ja',50)->nullable();
            $table->string('tag_ja',50)->nullable();
            $table->string('guide_voice_en',50)->nullable();
            $table->string('guide_text_en',50)->nullable();
            $table->string('landscape_en',50)->nullable();
            $table->string('tag_en',50)->nullable();
            $table->string('guide_voice_cn_simplified',50)->nullable();
            $table->string('guide_text_cn_simplified',50)->nullable();
            $table->string('landscape_cn_simplified',50)->nullable();
            $table->string('tag_cn_simplified',50)->nullable();
            $table->string('guide_voice_cn_traditional',50)->nullable();
            $table->string('guide_text_cn_traditional',50)->nullable();
            $table->string('landscape_cn_traditional',50)->nullable();
            $table->string('tag_cn_traditional',50)->nullable();
            $table->string('guide_voice_kr',50)->nullable();
            $table->string('guide_text_kr',50)->nullable();
            $table->string('landscape_kr',50)->nullable();
            $table->string('tag_kr',50)->nullable();
            $table->string('guide_voice_es',50)->nullable();
            $table->string('guide_text_es',50)->nullable();
            $table->string('landscape_es',50)->nullable();
            $table->string('tag_es',50)->nullable();
            $table->string('guide_voice_gf',50)->nullable();
            $table->string('guide_text_gf',50)->nullable();
            $table->string('landscape_gf',50)->nullable();
            $table->string('tag_gf',50)->nullable();
            $table->string('guide_voice_it',50)->nullable();
            $table->string('guide_text_it',50)->nullable();
            $table->string('landscape_it',50)->nullable();
            $table->string('tag_it',50)->nullable();
            $table->string('guide_voice_de',50)->nullable();
            $table->string('guide_text_de',50)->nullable();
            $table->string('landscape_de',50)->nullable();
            $table->string('tag_de',50)->nullable();
            $table->string('guide_voice_ru',50)->nullable();
            $table->string('guide_text_ru',50)->nullable();
            $table->string('landscape_ru',50)->nullable();
            $table->string('tag_ru',50)->nullable();
            $table->string('guide_voice_th',50)->nullable();
            $table->string('guide_text_th',50)->nullable();
            $table->string('landscape_th',50)->nullable();
            $table->string('tag_th',50)->nullable();
            $table->string('guide_voice_vn',50)->nullable();
            $table->string('guide_text_vn',50)->nullable();
            $table->string('landscape_vn',50)->nullable();
            $table->string('tag_vn',50)->nullable();
            $table->string('guide_voice_id',50)->nullable();
            $table->string('guide_text_id',50)->nullable();
            $table->string('landscape_id',50)->nullable();
            $table->string('tag_id',50)->nullable();
            $table->boolean('status');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('guides');
    }
}
