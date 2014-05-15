<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormSubmissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('webformsubmissions', function ($table) {

			$table->increments('id');

			$table->integer('page_id');
			$table->integer('version_number');

			$table->timestamps();

		});

		Schema::create('webformsubmissionfields', function ($table) {

			$table->increments('id');

			$table->integer('submission_id');
			$table->integer('field_id');
			$table->string('type');
			$table->string('label');
			$table->text('field_data');

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
		Schema::drop('webformsubmissions');
		Schema::drop('webformsubmissionfields');
	}

}
