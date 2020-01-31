<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoMetaTagValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_meta_tag_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('seo_setting_id');
            $table->unsignedBigInteger('seo_meta_tag_id');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_meta_tag_values');
    }
}
