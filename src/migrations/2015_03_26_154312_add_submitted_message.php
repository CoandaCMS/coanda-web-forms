<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubmittedMessage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('coanda_webforms', function ($table) {

            $table->text('submitted_message')->after('post_submit_handler_data');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('coanda_webforms', function ($table) {

            $table->dropColumn('submitted_message');

        });
	}

}
