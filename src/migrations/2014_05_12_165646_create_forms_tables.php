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
		Schema::create('webforms', function ($table) {

			$table->increments('id');
			$table->string('name');

			$table->timestamps();

		});

		Schema::create('webformfields', function ($table) {

			$table->increments('id');
			$table->integer('webform_id');
			$table->integer('order');
			$table->integer('columns');
			$table->boolean('required');
			
			$table->string('label');
			$table->string('type');
			$table->text('type_data');

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
		Schema::drop('webforms');
		Schema::drop('webformfields');
	}

}
