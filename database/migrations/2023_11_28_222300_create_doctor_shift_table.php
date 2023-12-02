<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorShiftTable extends Migration {

	public function up()
	{
		Schema::create('user_shift', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->BigInteger('doctor_id')->unsigned();
			$table->integer('shift_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('doctor_shift');
	}
}