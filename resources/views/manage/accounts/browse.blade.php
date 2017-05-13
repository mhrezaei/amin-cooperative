@extends('manage.frame.use.0')

@section('section')
	<div id="divTab">
		@include('manage.accounts.tabs')
	</div>

	@include("manage.frame.widgets.toolbar" , [
		'subtitle_view' => "manage.accounts.browse-subtitle" ,
		'buttons' => [
			[
				'target' => "modal:manage/accounts/create/$user->id",
				'type' => "success",
				'caption' => trans('banking.create_account') ,
				'icon' => "plus-circle",
			],
		],
	])

	@include("manage.frame.widgets.grid" , [
		'table_id' => "tblAccounts",
		'row_view' => "manage.accounts.browse-row",
		'handle' => "selector",
		'headings' => [
			trans('validation.attributes.properties'),
			trans('banking.capital'),
			trans('banking.normal_loan'),
			trans('banking.urgent_loan'),
			trans('forms.button.action')
		],
	])

@endsection



