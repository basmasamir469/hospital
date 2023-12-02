<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration {

	public function up()
	{
		Schema::create('appointments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->date('appointment_date');
			$table->time('appointment_time');
			$table->integer('appointment_status')->default('1');
			$table->bigInteger('doctor_id')->unsigned();
			$table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');

		});
	}

	public function down()
	{
		Schema::drop('appointments');
	}
}