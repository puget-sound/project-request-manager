<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Users;

class ConsolidateAdminDevToRole extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('users', function($table)
		{
				// Create new column for role
				$table->tinyInteger('role');
		});

		// Get records from old column.
		$admin_results = Users::where('admin', '=', 1)->get();

		// Loop through admin users and assign admin role
		foreach($admin_results as $admin_result)
		{
				$admin_result['role'] = 2;
				$admin_result->save();
		}

		$dev_results = Users::where('dev', '=', 1)->get();

		// Loop through dev users and assign dev role
		foreach($dev_results as $dev_result)
		{
				$dev_result['role'] = 1;
				$dev_result->save();
		}

		// Delete old columns.
		Schema::table('users', function($table)
		{
				$table->dropColumn('admin');
				$table->dropColumn('dev');
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
