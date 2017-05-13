<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Providers\ValidationServiceProvider;


class AccountSaveRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return user()->as('admin')->can('accounts.edit') ;
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
			'account_no' => "required|unique:accounts,account_no,".$input['id'],
			'opened_at'  => 'required|date|after:'.setting('min_date')->gain(),
		];
	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'id'         => 'ed|numeric',
			'account_no' => 'ed',
			'opened_at'  => "date",
		]);

		return $purified;

	}

}
