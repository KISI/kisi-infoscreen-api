<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	protected $table = 'events';
	public $timestamps = true;
	protected $casts = [
		'start' => 'time',
		'end' => 'time',
		'hasEnd' => 'boolean',
		'title' => 'string',
		'dest' => 'string',
		'featured' => 'boolean',
		'livestream' => 'boolean'
	];

}