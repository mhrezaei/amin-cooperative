<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Self_;


class Account extends Model
{
	use TahaModelTrait, SoftDeletes;
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

	public function transactions()
	{
		return $this->hasMany('App\Models\Transaction');
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getNoAttribute()
	{
		$full_title = trans("banking.account_no_folan", [
			"folan" => pd($this->account_no),
		]);

		return $full_title;
	}

	public function getFullTitleAttribute()
	{
		return $this->no . ' ' . trans("banking.in_the_name_of_folan", [
				"folan" => $this->user->full_name,
			]);
	}


	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheRegenerate()
	{
		$this->user->cacheUpdate();
	}

	public function cacheUpdate()
	{
		$this->capital     = Transaction::where('account_id', $this->id)->where('type', 'capital')->sum('amount');
		$this->normal_loan = Transaction::where('account_id', $this->id)->where('type', 'normal_loan')->sum('amount');
		$this->urgent_loan = Transaction::where('account_id', $this->id)->where('type', 'urgent_loan')->sum('amount');

		return $this->suppressMeta()->update();
	}

	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	| 
	*/
	public static function selector($para = [])
	{
		$para = array_normalize($para, [
			'user'     => 0,
			'criteria' => "actives",
			'number'   => "0",
		]);

		$table = self::where('id', '>', '0');

		/*-----------------------------------------------
		| Easy Things ...
		*/
		if($para['user']) {
			$table->where('user_id', $para['user']);
		}
		if($para['number']) {
			$table->where('account_number', $para['number']);
		}

		/*-----------------------------------------------
		| Criteria ...
		*/
		switch ($para['criteria']) {
			case 'all' :
				//nothing to do :)
				break;

			case 'all_with_trashed':
				$table->withTrashed();
				break;

			case 'actives':
				break;

			case 'bin' :
				$table->onlyTrashed();
				break;

			default:
				$table->where('id', 0);

		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $table;


	}

	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/
	public function canEdit()
	{
		return boolval(!$this->trashed() and user()->as('admin')->can('accounts.edit'));
	}

	public function canView()
	{
		return true;
		//return boolval(user()->as('admin')->can('accounts.view') or user()->is_a('member'));
	}

	public function canDelete()
	{
		return boolval(!$this->trashed() and user()->as('admin')->can('accounts.delete'));
	}

	public function canBin()
	{
		return boolval($this->trashed() and user()->as('admin')->can('accounts.bin'));
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function membersCombo()
	{
		$user = new User();

		return $user->membersCombo();
	}

	public function suggestNewAccountNo()
	{
		return self::withTrashed()->max('account_no') + 1;
	}


}
