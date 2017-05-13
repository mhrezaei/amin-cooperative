@if($user->exists)

	@include("manage.frame.widgets.grid-text" , [
		'text' => $user->full_name,
		'icon' => "user-o" ,
		'link' => "urlN:".$user->profile_link ,
	]     )

@endif