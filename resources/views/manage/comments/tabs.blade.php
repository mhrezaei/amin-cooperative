@include('manage.frame.use.tabs' , [
//	'refresh_url' => str_replace('?page=1',null, str_replace('posts/' , 'posts/tab_update/' , $models->url(1)   ))  ,
	'current' =>  $page[1][0]."/$request_user" ,
	'tabs' => [
		["actives/$request_user" , trans('banking.criteria.actives')],
		["bin/$request_user" , trans('manage.tabs.bin') ],// , $db->counterC('bin')],
//		["$locale/search" , trans('forms.button.search')],
	] ,
])

