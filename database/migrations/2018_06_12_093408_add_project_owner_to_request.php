<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectOwnerToRequest extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('sprint_project_role_assignment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('priority')->default(0);
			$table->integer('sprint_project_role_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('projects_id')->nullable();
			$table->integer('sprint_id')->nullable();
		});


		DB::table('sprint_project_role_assignment')->insert(
			array(
					'user_id' => '12',
					'priority' => '1',
					'sprint_project_role_id' => '1',
					'projects_id' => '622',
					'sprint_id' => '24'
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
		Schema::drop('sprint_project_role_assignment');
	}

}
