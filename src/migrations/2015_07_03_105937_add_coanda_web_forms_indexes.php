<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoandaWebFormsIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('coanda_webformsubmissions', function($table) {
			$table->index('form_id');
		});

		Schema::table('coanda_webformsubmissionfields', function($table) {
			$table->index('submission_id');
			// $table->index('label'); // can't do this
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('coanda_webformsubmissionfields', function($table) {
			$table->dropIndex('form_id');
		});

		Schema::table('coanda_webformsubmissionfields', function($table) {
			$table->dropIndex('submission_id');
			// $table->dropIndex('label'); // can't do this
		});
	}

}
