@include('manage.frame.widgets.grid-rowHeader' , [
	'refresh_url' => "manage/accounts/update/$model->id"
])

{{--
|--------------------------------------------------------------------------
| Properties
|--------------------------------------------------------------------------
|
--}}

<td>
	@include("manage.frame.widgets.grid-text" , [
		'icon' => "hashtag" ,
		'text' => pd($model->account_no),
		'link' => $model->canEdit()? 'modal:manage/accounts/act/-id-/edit' : '',
		'size' => "18" ,
	]     )
	@include("manage.frame.widgets.grid-tiny" , [
		'text' => $model->user->full_name,
		'link' => "url:manage/accounts/all/".$model->user_id ,
		'icon' => "user-o" ,
		'size' => "10" ,
	]     )
	@include("manage.frame.widgets.grid-text" , [
		'text' => "($model->title)",
		'condition' => $model->title ,
		'class' => "mv10" ,
		'color' => "gray" ,
		'size' => "10" ,
	]     )
</td>

{{--
|--------------------------------------------------------------------------
| Balance
|--------------------------------------------------------------------------
|
--}}
<td>
	{{ pd(number_format($model->capital)) }}
</td>
<td>
	{{ pd(number_format($model->normal_loan)) }}
</td>
<td>
	{{ pd(number_format($model->urgent_loan)) }}
</td>

{{--
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
|
--}}
@include("manage.frame.widgets.grid-actionCol" , [ "actions" => [
	['eye' , trans('forms.button.show_details') , 'modal:manage/accounts/act/-id-/show' , $model->canView()],
	['pencil' , trans('forms.button.edit') , "modal:manage/accounts/act/-id-/edit" , $model->canEdit()],

	['trash-o' , trans('forms.button.soft_delete') , "modal:manage/accounts/act/-id-/delete" , $model->canDelete() ] ,
	['recycle' , trans('forms.button.undelete') , "modal:manage/accounts/act/-id-/undelete" , $model->canBin() ],
//	['times' , trans('forms.button.hard_delete') , "modal:manage/accounts/act/-id-/destroy" , $model->canBin() ],
]])
