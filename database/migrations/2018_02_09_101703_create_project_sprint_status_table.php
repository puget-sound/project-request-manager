<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSprintStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_sprint_status', function(Blueprint $table)
		{
			$table->increments('id');
			// store status name
			$table->string('name', '200')->nullable();
		});

		// Insert initial status list
		DB::table('project_sprint_status')->insert(
        array(
            'name' => 'Complete'
        )
    );
    DB::table('project_sprint_status')->insert(
        array(
            'name' => 'Analysis Continues'
        )
    );
		DB::table('project_sprint_status')->insert(
        array(
            'name' => 'Development Continues'
        )
    );
		DB::table('project_sprint_status')->insert(
        array(
            'name' => 'Development Complete'
        )
    );
		DB::table('project_sprint_status')->insert(
        array(
            'name' => 'Testing Continues'
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
		Schema::drop('project_sprint_status');
	}

}
