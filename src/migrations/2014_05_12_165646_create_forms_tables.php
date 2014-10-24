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
		Schema::create('coanda_webforms', function ($table) {

			$table->increments('id');
			$table->string('name');
            $table->text('post_submit_handler_data');

			$table->timestamps();

		});

		Schema::create('coanda_webformfields', function ($table) {

			$table->increments('id');
			$table->integer('webform_id');
			$table->integer('order');
			$table->integer('columns');
			$table->boolean('required');

            $table->string('identifier');
			$table->text('label');
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
		Schema::drop('coanda_webforms');
		Schema::drop('coanda_webformfields');
	}

}
