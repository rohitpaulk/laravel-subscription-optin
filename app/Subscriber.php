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

	protected $fillable = ['first_name', 'last_name', 'email'];

	public function full_name() {
		return $this->first_name . " " . $this->last_name;
	}

	public function verification_link($root_url) {
		return $root_url . "/verify?email=" . $this->email . "&nonce=" . $this->nonce;
	}

}
