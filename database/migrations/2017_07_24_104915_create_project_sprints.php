<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Projects;
use App\Sprints;

class CreateProjectSprints extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('projects_sprints', function(Blueprint $table)
			{
				$table->engine = 'InnoDB';
				$table->integer('projects_id')->unsigned()->index();
	      //$table->foreign('projects_id')->references('id')
	      //      ->on('requests')->onDelete('cascade');

	      $table->integer('sprints_id')->unsigned()->index();
	      //$table->foreign('sprints_id')->references('id')
	      //      ->on('sprints')->onDelete('cascade');

				$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
				$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			});

			// Get projects with sprint
			$sprint_results = Projects::whereNotNull('sprint')->get();

			// Loop through projects
			foreach($sprint_results as $sprint_result)
			{
					$sprint = Sprints::where('sprintNumber', '=', $sprint_result->sprint)->first();
					$sprint_result->sprints()->attach($sprint->id);
					//$sprint_result['sprint'] = NULL;
					$sprint_result->save();
			}

			// Delete old columns.
			Schema::table('requests', function($table)
			{
					$table->dropColumn('sprint');
			});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			Schema::drop('projects_sprints');
		}

	}
