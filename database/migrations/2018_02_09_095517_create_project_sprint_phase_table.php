<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectSprintPhaseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_sprint_phase', function(Blueprint $table)
		{
			$table->increments('id');
			// store phase name
			$table->string('name', '200')->nullable();
		});

		// Insert initial phases
    DB::table('project_sprint_phase')->insert(
        array(
            'name' => 'Analysis'
        )
    );
		DB::table('project_sprint_phase')->insert(
        array(
            'name' => 'Design'
        )
    );
		DB::table('project_sprint_phase')->insert(
        array(
            'name' => 'Development'
        )
    );
    DB::table('project_sprint_phase')->insert(
        array(
            'name' => 'Testing'
        )
    );
		DB::table('project_sprint_phase')->insert(
        array(
            'name' => 'Configuration'
        )
    );
		DB::table('project_sprint_phase')->insert(
        array(
            'name' => 'Upgrade'
        )
    );
		DB::table('project_sprint_phase')->insert(
        array(
            'name' => 'Deployment'
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
		Schema::drop('project_sprint_phase');
	}

}
