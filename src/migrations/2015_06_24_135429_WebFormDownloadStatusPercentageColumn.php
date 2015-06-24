<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebFormDownloadStatusPercentageColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('coanda_webform_downloads', function($table)
		{
		    $table->decimal('status_percentage', 3, 0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('coanda_webform_downloads', function($table)
		{
			$table->dropColumn('status_percentage');
		});
	}

}
