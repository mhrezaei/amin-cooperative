@include('templates.modal.start' , [
	'form_url' => url('manage/accounts/save/delete'),
	'modal_title' => trans('forms.button.soft_delete'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])

	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.title'),
		'value' => $model->full_title ,
		'extra' => 'disabled' ,
	])
	
	@include("forms.note" , [
		'text' => trans('banking.account_delete_notice'),
		'shape' => "danger",
	])

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.soft_delete'),
		'shape' => 'danger',
		'type' => 'submit' ,
	])
	@include('forms.button' , [
		'label' => trans('forms.button.cancel'),
		'shape' => 'link',
		'link' => '$(".modal").modal("hide")',
	])

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')