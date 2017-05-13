@include('templates.modal.start' , [
	'form_url' => url('manage/accounts/save/'),
	'modal_title' => $model->id? trans('banking.edit_account')  : trans('banking.create_account'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
	]])


	@include('forms.input' , [
		'name' => 'account_no',
		'value' => pd($model->account_no) ,
		'class' => 'form-required form-default' ,
	])

	@include("forms.datepicker" , [
		'name' => "opened_at",
		'value' => $model->opened_at ,
		'class' => "form-required" ,
	]     )

	@include("forms.select" , [
		'name' => "user_id",
		'label' => trans('validation.attributes.beneficiary') ,
		'options' => $model->membersCombo() ,
		'blank_value' => "0" ,
		'caption_field' => "full_name" ,
		'value' => $model->user_id ,
		'search' => true ,
	]     )

	@include('forms.input' , [
	    'name' => 'title',
	    'hint' => trans('forms.general.optional') ,
	    'value' => $model->title
	])

	@include("forms.textarea" , [
		'name' => "note",
		'value' => $model->note ,
		'class' => "form-autoSize" ,
	]     )

	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.save'),
		'shape' => 'success',
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