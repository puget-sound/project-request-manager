<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhaseStatusToProjectsSprintsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projects_sprints', function(Blueprint $table)
		{
			// add phase and status to project sprint
			$table->integer('project_sprint_phase_id');
			$table->integer('project_sprint_status_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('projects_sprints', function(Blueprint $table)
		{
			//
		});
	}

}
