<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration {

	public function up()
	{
		Schema::create('shifts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->time('from_time');
			$table->time('to_time');
		});
	}

	public function down()
	{
		Schema::drop('shifts');
	}
}