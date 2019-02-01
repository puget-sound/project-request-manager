<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddErpReportFieldsToRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('requests', function(Blueprint $table)
		{
			//store ERP description, hide Y/N, category
			$table->text('brief_description')->nullable();
			$table->boolean('hide_from_reports')->default(false);
			$table->integer('erp_report_category_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('requests', function(Blueprint $table)
		{
			//
		});
	}

}
