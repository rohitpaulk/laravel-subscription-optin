<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model {

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
	    'verified' => 'boolean',
	];

}
