<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration {

	public function up()
	{
		Schema::create('reservations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('status')->default('1');
			$table->integer('doctor_id')->unsigned();
			$table->integer('patient_id')->unsigned();
			$table->integer('appoinment_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('reservations');
	}
}