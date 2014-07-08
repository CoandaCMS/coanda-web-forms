<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveVersionSlugFieldsFormForm extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('webformsubmissions', function ($table) {

			$table->dropColumn('version');
			$table->dropColumn('slug');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('webformsubmissions', function ($table) {

			$table->integer('version');
			$table->string('slug');

		});
	}

}
