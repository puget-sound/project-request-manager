<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Projects;

class SetCompletedProjectsSprintStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//set last sprint status to complete for all completed projects
		$projects = Projects::where('status', '=', 6)->get();

		foreach($projects as $project)
		{
			// set project's last sprint status to 'complete'
			$last_sprint = $project->sprints()->latest()->first();
			$project->sprints()->updateExistingPivot($last_sprint['id'], ['project_sprint_status_id' => 1]);
			$project->save();
		}
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
