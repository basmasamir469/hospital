<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->foreign('department_id')->references('id')->on('departments')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('reservations', function(Blueprint $table) {
			$table->foreign('doctor_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('reservations', function(Blueprint $table) {
			$table->foreign('patient_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('reservations', function(Blueprint $table) {
			$table->foreign('appoinment_id')->references('id')->on('appointments')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('user_shift', function(Blueprint $table) {
			$table->foreign('doctor_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('user_shift', function(Blueprint $table) {
			$table->foreign('shift_id')->references('id')->on('shifts')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('department_translations', function(Blueprint $table) {
			$table->foreign('department_id')->references('id')->on('departments')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->foreign('reservation_id')->references('id')->on('reservations')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('notification_translations', function(Blueprint $table) {
			$table->foreign('notification_id')->references('id')->on('notifications')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropForeign('users_department_id_foreign');
		});
		Schema::table('reservations', function(Blueprint $table) {
			$table->dropForeign('reservations_doctor_id_foreign');
		});
		Schema::table('reservations', function(Blueprint $table) {
			$table->dropForeign('reservations_patient_id_foreign');
		});
		Schema::table('reservations', function(Blueprint $table) {
			$table->dropForeign('reservations_appoinment_id_foreign');
		});
		Schema::table('doctor_shift', function(Blueprint $table) {
			$table->dropForeign('doctor_shift_doctor_id_foreign');
		});
		Schema::table('doctor_shift', function(Blueprint $table) {
			$table->dropForeign('doctor_shift_shift_id_foreign');
		});
		Schema::table('department_translations', function(Blueprint $table) {
			$table->dropForeign('department_translations_department_id_foreign');
		});
		Schema::table('notifications', function(Blueprint $table) {
			$table->dropForeign('notifications_reservation_id_foreign');
		});
		Schema::table('notification_translations', function(Blueprint $table) {
			$table->dropForeign('notification_translations_notification_id_foreign');
		});
	}
}