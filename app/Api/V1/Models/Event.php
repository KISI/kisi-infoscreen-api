<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	protected $table = 'events';
	public $timestamps = true;
	protected $casts = [
		'start' => 'dateTime',
		'end' => 'dateTime',
		'hasEnd' => 'boolean',
		'title' => 'string',
		'location' => 'string',
		'featured' => 'boolean',
		'livestream' => 'boolean'
	];

}