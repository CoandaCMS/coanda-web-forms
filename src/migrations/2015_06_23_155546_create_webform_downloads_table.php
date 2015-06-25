<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebformDownloadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('coanda_webform_downloads', function ($table) {

            $table->increments('id');
            $table->integer('webform_id');
            $table->integer('status');
            $table->string('filename');

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
        Schema::drop('coanda_webform_downloads');
    }

}
