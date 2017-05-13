@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/'),
	'modal_title' => $model->id? trans('forms.button.edit')  : trans('people.commands.create_new_user' , ['role_title' => trans('people.user'),]),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , $model->id ],
		['_role_to_be_attached' , 'member']
	]])

	@include('forms.input' , [
		'name' => 'name_first',
		'value' => $model->name_first ,
		'class' => 'form-required form-default' ,
	])

	@include('forms.input' , [
	    'name' => 'name_last',
	    'class' => 'form-required',
	    'value' => $model->name_last
	])

	{{--@include("forms.datepicker" , [--}}
		{{--'name' => "register_date",--}}
		{{--'value' => $model->register_date ,--}}
	{{--]     )--}}

	@include('forms.input' , [
	    'name' => 'nickname',
	    'value' => $model->nickname
	])

	@include('forms.input' , [
		'name' => 'mobile',
		'class' => 'ltr',
		'value' => $model->mobile ,
	])

	@include('forms.input' , [
		'name' => 'home_tel',
		'class' => 'ltr',
		'value' => $model->mobile ,
	])
	
	@include("forms.select" , [
		'name' => "sponsor_id",
		'label' => trans('validation.attributes.sponsor') ,
		'options' => $model->membersCombo() ,
		'blank_value' => "" ,
		'caption_field' => "full_name" ,
		'value' => $model->sponsor_id ,
		'search' => true ,
	]     )
	
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