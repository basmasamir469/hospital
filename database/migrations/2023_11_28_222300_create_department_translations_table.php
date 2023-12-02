<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentTranslationsTable extends Migration {

	public function up()
	{
		Schema::create('department_translations', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('locale')->index();
			$table->integer('department_id')->unsigned();
			$table->unique(['department_id', 'locale']);

		});
	}

	public function down()
	{
		Schema::drop('department_translations');
	}
}