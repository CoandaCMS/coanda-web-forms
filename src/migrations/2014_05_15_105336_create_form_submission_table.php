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
		Schema::create('coanda_webformsubmissions', function ($table) {

			$table->increments('id');

			$table->integer('form_id');
			$table->integer('page_id');
            $table->boolean('post_submit_handler_executed');

			$table->timestamps();

		});

		Schema::create('coanda_webformsubmissionfields', function ($table) {

			$table->increments('id');

			$table->integer('submission_id');
			$table->integer('field_id');
			$table->string('type');
			$table->string('identifier');
            $table->text('label');
			$table->text('field_data')->nullable();

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
		Schema::drop('coanda_webformsubmissions');
		Schema::drop('coanda_webformsubmissionfields');
	}

}
