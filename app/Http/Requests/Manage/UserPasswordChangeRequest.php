<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;


class UserPasswordChangeRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$command = $this->all()['_submit'];
		if($command == 'restrict') {
			return [];
		}
		else {
			return [
				'mobile'   => "required|phone:mobile",
				'new_password' => 'required|min:8|max:60',
			];
		}

	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'password' => '',
			'mobile'   => "ed",
		]);

		return $purified;

	}

}
