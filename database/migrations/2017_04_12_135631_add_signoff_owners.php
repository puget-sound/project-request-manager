<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignoffOwners extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('project_owners', function(Blueprint $table)
		{
			//store LiquidPlanner client ID
			$table->string('signoff_owner', '255')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
