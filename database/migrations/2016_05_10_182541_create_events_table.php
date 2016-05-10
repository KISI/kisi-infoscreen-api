<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	public function up()
	{
		Schema::create('events', function(Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->dateTime('start');
			$table->dateTime('end');
			$table->boolean('hasEnd');
			$table->string('title');
			$table->string('location');
			$table->boolean('featured');
			$table->boolean('livestream');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('events');
	}
}