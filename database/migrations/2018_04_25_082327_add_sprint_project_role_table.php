<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSprintProjectRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('sprint_project_role', function(Blueprint $table)
		{
			$table->increments('id');
			//store category name
			$table->string('name', '200')->nullable();
		});

		// Insert initial categories
		DB::table('sprint_project_role')->insert(
				array(
						'name' => 'Assigned To'
				)
		);
		DB::table('sprint_project_role')->insert(
				array(
						'name' => 'Support'
				)
		);
		DB::table('sprint_project_role')->insert(
				array(
						'name' => 'Security'
				)
		);
		DB::table('sprint_project_role')->insert(
				array(
						'name' => 'Test Manager'
				)
		);
		DB::table('sprint_project_role')->insert(
				array(
						'name' => 'Installer'
				)
		);
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
