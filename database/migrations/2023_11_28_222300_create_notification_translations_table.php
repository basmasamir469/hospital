<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('notification_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('notification_id')->unsigned();
			$table->string('locale')->index();
			$table->string('title');
			$table->text('description');
			$table->unique(['notification_id', 'locale']);
		});
	}

	public function down()
	{
		Schema::drop('notification_translations');
	}
}