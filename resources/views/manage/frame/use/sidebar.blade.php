@include('manage.frame.widgets.sidebar-link' , [
	'icon' => 'dashboard' ,
	'caption' => trans('manage.dashboard') ,
	'link' => 'index' ,
])

@include("manage.frame.widgets.sidebar-link" , [
	'icon' => "map-o",
	'caption' => trans('banking.accounts') ,
	'link' => "accounts/browse" ,
]     )

@include("manage.frame.widgets.sidebar-link" , [
	'icon'    => "address-book",
	'caption' => trans('people.site_users'),
	'link'    => "users/browse/all",
]     )


{{--
|--------------------------------------------------------------------------
| Automatic Users Menu
|--------------------------------------------------------------------------
|
--}}

{{--@foreach(Manage::sidebarUsersMenu() as $item)--}}
	{{--@include("manage.frame.widgets.sidebar-link" , $item)--}}
{{--@endforeach--}}

{{--
|--------------------------------------------------------------------------
| Folded Settings
|--------------------------------------------------------------------------
|
--}}

@include("manage.frame.widgets.sidebar-link" , [
	'icon' => "cogs",
	'link' => "jafarz",
	'sub_menus' => Manage::sidebarSettingsMenu() ,
	'caption' => trans('settings.site_settings'),
])
