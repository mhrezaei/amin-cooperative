<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Providers\ValidationServiceProvider;


class UserSaveRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true; //security checked in the middleware.
		//return user()->is_an('admin');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$input = $this->all();

		return [
			'id'         => 'numeric',
			'name_first' => 'required',
			'name_last'  => 'required',
			'mobile'     => 'phone:mobile',
			'home_tel'   => 'phone:fixed',
		];
	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'id'       => 'ed|numeric',
			'mobile'   => 'ed',
			'home_tel' => "ed",
		]);

		return $purified;

	}

}
