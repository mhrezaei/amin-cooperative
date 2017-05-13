<?php

namespace App\Models;

use App\Providers\DummyServiceProvider;
use App\Traits\PermitsTrait;
use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
	use Notifiable, TahaModelTrait, PermitsTrait, SoftDeletes;

	public static $meta_fields     = [
		'preferences',
		'home_tel',
	];
	public static $search_fields   = ['name_first', 'name_last', 'email', 'mobile'];
	public static $required_fields = ['name_first', 'name_last', 'code_melli', 'mobile', 'home_tel', 'birth_date', 'gender', 'marital'];
	protected     $guarded         = ['id'];
	protected     $hidden          = ['password', 'remember_token'];
	protected     $casts           = [
		'meta' => 'array',
	];


	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/
	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withPivot('permissions', 'deleted_at')->withTimestamps();
	}

	public function accounts()
	{
		return $this->hasMany('App\Models\Account');
	}

	public function transactions()
	{
		return $this->hasMany('App\Models\Transaction');
	}

	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheUpdate()
	{
		$this->capital     = Transaction::where('user_id', $this->id)->where('type', 'capital')->sum('amount');
		$this->normal_loan = Transaction::where('user_id', $this->id)->where('type', 'normal_loan')->sum('amount');
		$this->urgent_loan = Transaction::where('user_id', $this->id)->where('type', 'urgent_loan')->sum('amount');

		return $this->suppressMeta()->update();

	}


	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function selector($parameters = [])
	{
		extract(array_normalize($parameters, [
			'id'       => "0",
			'role'     => "member",
			'criteria' => "actives",
			'search'   => "",
		]));

		/*-----------------------------------------------
		| Process Roles...
		*/
		if($role == 'all') {
			$table         = User::where('id', '>', '0');
			$related_table = false;
		}
		else {
			$table         = Role::findBySlug($role)->users();
			$related_table = true;
		}

		/*-----------------------------------------------
		| Exclude Developers ...
		*/
		if(!user()->isDeveloper()) {
			$table = $table->whereNotIn('email', ['admin@yasnateam.com']);
		}

		/*-----------------------------------------------
		| Process id ...
		*/
		if($id > 0) {
			$table = $table->where('users.id', $id);
		}


		/*-----------------------------------------------
		| Process Criteria ...
		*/
		switch ($criteria) {
			case 'all' :
				//nothing to do :)
				break;

			case 'actives':
				if($related_table) {
					$table = $table->wherePivot('deleted_at', null);
				}
				else {
					//nothing to do <~~
				}
				break;

			case 'banned':
				if($related_table) {
					$table = $table->wherePivot('deleted_at', '!=', null);
				}
				else {
					$table = $table->onlyTrashed();
				}
				break;

			case 'bin' :
				if($related_table) {
					$table = $table->wherePivot('deleted_at', '!=', null);
				}
				else {
					$table = $table->onlyTrashed();
				}
				break;

			default:
				if($related_table) {
					$table = $table->where('users.id', '0');
				}
				else {
					$table = $table->where('id', 0);
				}

		}

		/*-----------------------------------------------
		| Process Search ...
		*/
		$table = $table->whereRaw(self::searchRawQuery($search));


		/*-----------------------------------------------
		| Return  ...
		*/

		return $table;
	}


	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

	public function getLoginEnabledAttribute()
	{
		return boolval($this->mobile and $this->password)  ;
	}

	public function getProfileLinkAttribute()
	{
		return "manage/users/browse/all/search?id=$this->id&searched=1";
	}


	public function getMobileMaskedAttribute()
	{
		$string = $this->mobile;
		if(strlen($string) == 11) {
			return substr($string, 7) . ' ••• ' . substr($string, 0, 4);
		}

		return $string;
	}

	public function getMobileFormattedAttribute()
	{
		$string = $this->mobile;
		if(strlen($string) == 11) {
			return substr($string, 7) . ' - ' . substr($string, 4, 3) . ' - ' . substr($string, 0, 4);
		}

		return $string;
	}


	public function getFullNameAttribute()
	{
		if($this->exists) {
			$full_name = $this->name_first . ' ' . $this->name_last;
			if($this->nickname) {
				$full_name .= " ($this->nickname) ";
			}

			return $full_name;
		}
		else {
			return trans('people.deleted_user');
		}
	}

	public function getStatusAttribute()
	{
		$request_role = $this->getAndResetAsRole();
		if($request_role) {
			if(!$this->as($request_role)->includeDisabled()->hasRole()) {
				return 'without';
			}
			elseif($this->enabled()) {
				return 'active';
			}
			else {
				return 'blocked';
			}
		}
		else {
			if(!$this->trashed()) {
				return 'active';
			}
			else {
				return 'blocked';
			}
		}

	}

	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/

	public function preference($slug)
	{
		$this->spreadMeta();
		$preferences = array_normalize($this->preferences, [
			'max_rows_per_page' => "50",
		]);

		return $preferences[ $slug ];
	}

	public function canEdit()
	{
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other Users ...
		*/
		if(!$request_role or $request_role == 'admin') {
			return user()->is_a('superadmin');
		}
		else {
			return Role::checkManagePermission($request_role, 'edit');
		}
	}

	public function canDelete()
	{
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');

	}

	public function canBin()
	{
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');

	}


	public function canPermit()
	{
		return $this->isDeveloper() ;
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if($this->trashed()) {
			return false;
		}
		if($this->id == user()->id) {
			return false;
		}

		if($request_role) {
			if($this->as($request_role)->disabled()) {
				return false;
			}
			$role = Role::findBySlug($request_role);
			if(!$role or !$role->has_modules) {
				return false;
			}
		}

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');
	}


	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function rolesTable()
	{
		return Role::all();
	}

	public function roleStatusCombo()
	{
		return [
			['', trans('people.without_role')],
			['active', trans('forms.status_text.active')],
			['blocked', trans('forms.status_text.blocked')],
		];

	}

	public function membersCombo()
	{
		return Self::selector([
			'role' => "member",
		])->orderBy('name_last')->get()
			;
	}

}
