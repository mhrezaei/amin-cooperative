<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\AccountSaveRequest;
use App\Models\Account;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AccountsController extends Controller
{
	use ManageControllerTrait;
	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	public function __construct()
	{
		$this->page[0] = ['accounts', trans('banking.accounts')];

		$this->Model = new Account();
		//$this->Model->setSelectorPara([
		//	'role' => "admin",
		//]);

		$this->browse_handle = 'selector';
		$this->view_folder   = 'manage.accounts';
	}

	public function browse($request_tab = 'actives', $request_user = 0)
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(user()->is_not_an('admin') and user()->as('member')->cant('accounts')) {
			return view('errors.503');
		}
		if(!in_array($request_tab, ['actives', 'bin'])) {
			return view('errors.404');
		}
		/*-----------------------------------------------
		| Revealing the User...
		*/
		if($request_user > 0) {
			$user = User::find($request_user);
			if(!$user or $user->is_not_a('member')) {
				return view('errors.404');
			}
		}
		else {
			$user            = new User();
			$user->full_name = trans('forms.general.all');
		}

		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page = [
			'0' => ['accounts/browse', trans('banking.accounts'), 'accounts/browse'],
			'1' => [$request_tab, trans("banking.criteria.$request_tab"), "accounts/browse/$request_tab"],
		];
		if($user->id) {
			$page[2] = [$request_user, $user->full_name, "accounts/browse/$request_tab/$request_user"];
		}

		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'user'     => $request_user,
			'criteria' => $request_tab,
		];

		$models = Account::selector($selector_switches)->orderBy('opened_at', 'desc')->orderBy('id', 'desc')->paginate(100);
		$db     = $this->Model;

		/*-----------------------------------------------
		| Views ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'request_tab', 'request_user', 'user'));

	}

	public function create($user_id)
	{
		if($user_id) {
			$user = User::find($user_id);
			if(!$user or $user->is_not_a('member')) {
				return view('errors.m410');
			}
		}

		$model             = new Account();
		$model->user_id    = $user_id;
		$model->account_no = $model->suggestNewAccountNo();
		$model->opened_at  = '2001/03/21 20:30:00';

		return view("manage.accounts.edit", compact('model'));

	}

	public function save(AccountSaveRequest $request)
	{
		/*-----------------------------------------------
		| Find Model ...
		*/
		if($request->id) {
			$model = Account::find($request->id);
			if(!$model) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
		}

		/*-----------------------------------------------
		| Request User Id ...
		*/
		if($request->user_id) {
			$user = User::find($request->user_id);
		}
		if(!isset($user) or !$user) {
			return $this->jsonFeedback(trans('validation.exists', ['attribute' => trans('validation.attributes.beneficiary'),]));
		}

		/*-----------------------------------------------
		| Save and Feedback...
		*/

		return $this->jsonAjaxSaveFeedback(Account::store($request), [
			'success_callback' => "rowUpdate('tblAccounts','$request->id')",
		]);


	}

	public function delete(Request $request)
	{
		$account = Account::find($request->id);
		if(!$account or !$account->canDelete()) {
			return $this->jsonFeedback(trans('validation.http.Error503'));
		}

		return $this->jsonAjaxSaveFeedback($account->delete(), [
			'success_callback' => "rowHide('tblAccounts','$request->id')",
		]);

	}

	public function undelete(Request $request)
	{
		$account = Account::onlyTrashed()->find($request->id);
		if(!$account or !$account->canBin()) {
			return $this->jsonFeedback(trans('validation.http.Error503'));
		}

		return $this->jsonAjaxSaveFeedback($account->restore(), [
			'success_callback' => "rowHide('tblAccounts','$request->id')",
		]);

	}


}
