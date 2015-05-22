<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnableRecaptchaToForm extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('coanda_webforms', function ($table) {

            $table->boolean('enable_recaptcha')->after('submitted_message');

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

            $table->dropColumn('enable_recaptcha');

        });
	}

}
