<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddErpReportCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_report_category', function(Blueprint $table)
		{
			$table->increments('id');
			//store category name
			$table->string('name', '200')->nullable();
		});

		// Insert initial categories
    DB::table('erp_report_category')->insert(
        array(
            'name' => 'General'
        )
    );
		DB::table('erp_report_category')->insert(
        array(
            'name' => 'Cascade'
        )
    );
		DB::table('erp_report_category')->insert(
        array(
            'name' => 'Infrastructure'
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
		Schema::drop('erp_category');
	}

}
