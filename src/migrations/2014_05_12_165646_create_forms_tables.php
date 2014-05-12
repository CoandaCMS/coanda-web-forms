<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('webformfields', function ($table) {

			$table->increments('id');

			$table->integer('page_id');
			$table->integer('version_number');

			$table->string('label');
			$table->boolean('required');

			$table->string('type');
			$table->text('type_data');

			$table->integer('order');

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
		Schema::drop('webformfields');
	}

}
