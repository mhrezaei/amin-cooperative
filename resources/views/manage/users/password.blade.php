@include('templates.modal.start' , [
	'form_url' => url('manage/users/save/password'),
	'no_validation' => true ,
	'modal_title' => trans('people.commands.set_credentials'),
])
<div class='modal-body'>

	@include('forms.hiddens' , ['fields' => [
		['id' , isset($model)? $model->id : '0'],
	]])


	@include('forms.input' , [
		'name' => '',
		'label' => trans('validation.attributes.name_first'),
		'value' => $model->full_name ,
		'extra' => 'disabled' ,
	])

	@include('forms.input' , [
		'name' => 'mobile',
		'hint' => trans('people.used_as_username') ,
		'class' => 'form-required ltr form-default' ,
		'value' => $model->mobile ,
	])

	@include('forms.input' , [
		'name' => 'new_password',
		'value' => rand(10000000 , 99999999),
		'class' => 'form-required ltr ' . ($model->mobile? 'form-default' : '') ,
		'hint' => trans('people.form.password_hint')
	])
	
	{{--@include("forms.note" , [--}}
		{{--'text' => trans('people.form.login_enabled_hint'),--}}
		{{--'shape' => "info" ,--}}
	{{--]     )--}}

	@include('forms.group-start')

	{{--@include('forms.check' , [--}}
		{{--'name' => 'sms_notify',--}}
		{{--'label' => trans('people.form.notify-with-sms'),--}}
		{{--'value' => 1,--}}
		{{--])--}}

	@include('forms.group-end')


	@include('forms.group-start')

	@include('forms.button' , [
		'label' => trans('forms.button.save'),
		'shape' => 'success',
		'value' => "save" ,
		'type' => 'submit' ,
	])
	@include("forms.button" , [
		'label' => trans('people.commands.restrict_access'),
		'shape' => "danger" ,
		'type' => "submit" ,
		'value' => "restrict" ,
		'condition' => $model->login_enabled ,
	]     )
	@include('forms.button' , [
		'label' => trans('forms.button.cancel'),
		'shape' => 'link',
		'link' => '$(".modal").modal("hide")',
	])

	@include('forms.group-end')

	@include('forms.feed')

</div>
@include('templates.modal.end')