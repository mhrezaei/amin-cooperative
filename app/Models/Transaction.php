<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
	use TahaModelTrait;

	public static $meta_fields = [
		'notes',
	];
	protected     $guarded     = ['id'];
	protected     $casts       = [
		'meta' => 'array',
	];

	/*
	|--------------------------------------------------------------------------
	| Relationship
	|--------------------------------------------------------------------------
	|
	*/

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function account()
	{
		return $this->belongsTo('App\Models\Account');
	}


	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheRegenerate()
	{
		if($this->user_id) {
			$this->user->cacheUpdate();
		}
		if($this->account_id) {
			$this->account->cacheUpdate();
		}
	}

}
