<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostSubmitHandlerDataToForm extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('webforms', function($table)
		{
		    $table->text('post_submit_handler_data')->after('name');

		});

		Schema::table('webformsubmissions', function ($table) {

			$table->boolean('post_submit_handler_executed')->after('slug');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('webforms', function($table)
		{
		    $table->dropColumn('post_submit_handler_data');

		});

		Schema::table('webformsubmissions', function ($table) {

			$table->dropColumn('post_submit_handler_executed');

		});
	}

}
