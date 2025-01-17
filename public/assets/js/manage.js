/**
 * Created by jafar on 7/6/2016 AD.
 */
$( document ).ready( function() {
	sidebarInitiate();
// 	heyCheck() ;
});

function rowHide($table_id , $model_id)
{
	if($table_id == 'auto')
		var $table_selector = ' .tableGrid'  ;
	else
		var $table_selector = '#'+$table_id ;

	var $row_selector = $table_selector + ' #tr-' + $model_id ;
	$($row_selector).slideUp() ;
	tabReload();
}

function heyCheck()
{
	// Last Run...
	in_server = false ;
	now = new Date() ;
	heyChecked = parseInt(localStorage.getItem('heyChecked'));
	if(now.getTime() - heyChecked > 1000*60*10) {
		in_server = true ;
		localStorage.setItem('heyChecked' , now.getTime());
	}

	// Check Process
	if(in_server) {
		$.ajax({
			url:url('manage/heyCheck') ,
			dataType: "json",
			cache: false
		})
		.done(function(result) {
			forms_log(result);
			localStorage.setItem( 'heyCheck' , result.ok);
		});
	}

	// Action...
	if(localStorage.getItem('heyCheck') == 'false') {
		loginAlert('on');
	}
	else {
		loginAlert('off') ;
	}

	setTimeout('heyCheck()' , 1000) ;
// 	forms_log( "heyCheck #" + localStorage.getItem('heyChecked') + "; in_server:" + in_server + "; result: " + localStorage.getItem('heyCheck') );
}

function loginAlert(mood)
{
	$alert = $('#divHeyCheck') ;
	if(mood=='on') {
		$alert.fadeIn('fast');
	}
	else {
		$alert.fadeOut('fast');
	}
}

function rowUpdate($table_id , $model_id)
{
	if($table_id == 'auto')
		var $table_selector = ' .tableGrid'  ;
	else
		var $table_selector = '#'+$table_id ;

	if($model_id=='0') {
		if($($table_selector).length) {
			forms_delaiedPageRefresh(1);
		}
	}
	else {
		var $row_selector = $table_selector + ' #tr-' + $model_id ;
		var $url = $($row_selector+' .refresh ').html() ;
		var $counter = $($row_selector + ' .-rowCounter ').html() ;
		$($row_selector).addClass('loading') ;
		$.ajax({
					url: $url,
					cache: false ,
				})
				.done(function (html) {
					$($row_selector).html(html);
					$($row_selector).removeClass('loading') ;
					$($row_selector + ' .-rowCounter ').html($counter) ;
				});
	}
}

function tabReload()
{
	var url = $("#divTab .refresh").html() ;
	var $tab_div = $("#divTab");

	if(!url) {
		return ;
	}

	$tab_div.addClass('loading');

	forms_log(url);
	$.ajax({
				url:url ,
				cache: false
			})
			.done(function(html) {
				$tab_div.html(html);
				$tab_div.removeClass('loading');
			});
}

function divReload( div_id )
{
	var $div = $("#" + div_id) ;
	var url = $("#" + div_id + " .refresh").html() ;

	if(!url) {
		return ;
	}

	$div.addClass('loading') ;
	forms_log(url) ;
	$.ajax({
		url: url ,
		cache: false
	}).done(function(html)
	{
		$div.html(html);
		$div.removeClass('loading');
	});
}

function masterModal($url,$size)
{
	//Preparetions...
	if(!$size) $size = 'lg' ;
	var $modal_selector = '#masterModal-' + $size ;

	//Form Load...
	$($modal_selector + ' .modal-content').html('<div class="modal-wait">...</div>').load($url , function() {
		$('.selectpicker').selectpicker();
	});
	$($modal_selector).modal() ;


}
function modalForm($modal_id , $item_id , $parent_id)
{
	//Preparetions...
	if(!$parent_id) $parent_id='0' ;
	var $modal_selector = '#' + $modal_id ;
	var $form_selector = $modal_selector + ' form ' ;
//	var $url = $($form_selector+'._0').html().replace('-id-',$item_id).replace('-parent-',$parent_id);

	//Form Placement...
//	if($item_id=='0')
//		$($modal_selector + '-title').html($($form_selector+'._2').html());
//	else
//		$($modal_selector + '-title').html($($form_selector+'._1').html());

	//Form Load...
//	$($form_selector + 'div.modal-body').html('....').load($url , function() {
//		$('.selectpicker').selectpicker();
//	});
	$($modal_selector).modal() ;

}


function search($form_id)
{
	var $input = $('#'+$form_id+ ' input[name=key]');
	var $key   = $input.val() ;
	var $url   = $('#'+$form_id).attr('action').replace('-key-',$key);

	if(!$key) return false ;
	window.location = $url ;
	return false ;
}

function gridSelector($mood , $id)
{
	switch($mood) {
		case 'tr' :
			$('#gridSelector-'+$id).prop('checked', !$('#gridSelector-'+$id).is(":checked"));

		case 'selector' :
			if ($('#gridSelector-'+$id).is(":checked"))
				$('#tr-'+$id).addClass('warning');
			else
				$('#tr-'+$id).removeClass('warning');
			gridSelector('buttonActivator');
			break;

		case 'all' :
			if($('#gridSelector-all').is(':checked')) {
				$('.gridSelector').prop('checked', true);
				$('tr.grid').addClass('warning');
			}
			else {
				$('.gridSelector').prop('checked', false);
				$('tr.grid').removeClass('warning');
			}
			gridSelector('buttonActivator');
			break;

		case 'count':
			var $count = 0 ;
			$(".gridSelector:checked").each(function () {
				$count++ ;
			});
			return $count ;

		case 'get' :
			var $list = '';
			var $count = 0 ;
			$(".gridSelector:checked").each(function () {
				$id = $(this).attr('data-value');
				$list += $id+',';
				$count++ ;
			});
			$('input[name=ids]').val($list);
			$('#txtCount').val(forms_pd($count + ' مورد '));
			break ;

		case 'buttonActivator' :
			if(gridSelector('count')>0)
				$('#action0').prop('disabled', false);
			else
				$('#action0').prop('disabled', true);
	}
}

function posttypeFeatures($feature)
{
	var $button = $("#lblFeature-"+$feature) ;
	var $input = $("#txtFeatures");
	var $meta = $("#txtMeta");
	var $fields_array = available_features[$feature][2];
	var $fields = '' ;

	$fields_array.forEach( function($item) {
		$fields += $item + ", " ;
	});

	forms_log($fields);

	if($input.val().indexOf($feature)>=0){
		$input.val($input.val().replaceAll($feature , ''));
		$button.css('opacity','0.5');
		$meta.val( $meta.val().replaceAll($fields , '') ) ;
	}
	else {
		$input.val($input.val() + ' ' + $feature + ' ');
		$button.css('opacity','0.9');
		$meta.val( $meta.val() + $fields ) ;
	}

	/*-----------------------------------------------
	 | Features that must be together ...
	 */
	if($feature=='full_history' && $input.val().indexOf('full_history')>=0 && $input.val().indexOf('history_system')<0) {
		posttypeFeatures('history_system');
	}
	if($feature=='history_system' && $input.val().indexOf('full_history')>=0 && $input.val().indexOf('history_system')<0) {
		posttypeFeatures('full_history');
	}

	if($feature=='basket' && $input.val().indexOf('basket')>=0 && $input.val().indexOf('price')<0) {
		posttypeFeatures('price');
	}
	if($feature=='price' && $input.val().indexOf('basket')>=0 && $input.val().indexOf('price')<0) {
		posttypeFeatures('basket');
	}


}


function downstreamPhotoSelected($input_selector)
{
	$($input_selector).val($($input_selector).val().replace(url(),''));
}

function downstreamPhotoPreview($input_selector)
{
	$url = $($input_selector).val() ;
	if($url)
		window.open(url($url)) ;
}


function sidebarToggle($speed)
{
	if(!$speed) $speed = 0 ;
	$current_sitation = localStorage.getItem('sidebar') ;
	if(!$current_sitation) $current_sitation = "shown" ;

	if($current_sitation=="shown") {
		//hide command:
		$(".sidebar").hide();
		$("#sidebarHandle").removeClass('fa-chevron-right').addClass('fa-chevron-left');
		localStorage.setItem('sidebar' , 'hidden');
		$("#page-wrapper").animate({
			"margin-right":0,
		},$speed);
	}
	else {
		//show command:
		$("#page-wrapper").animate({
			"margin-right":200,
		},$speed , function() {
			$(".sidebar").show();
			$("#sidebarHandle").removeClass('fa-chevron-left').addClass('fa-chevron-right');
		});
		localStorage.setItem('sidebar' , 'shown');
	}

	return localStorage.getItem('sidebar') ;
}

function sidebarInitiate()
{
	$current_sitation = localStorage.getItem('sidebar') ;
	if($current_sitation=='hidden') {
		localStorage.setItem('sidebar' , 'shown');
		return sidebarToggle(0);
	}
}

function drawingProgress(now_processed)
{
	//Hide/Show Elements...
	var $bar = $('#divProgress') ;
	$('.-progressHide').parent().hide();
	$bar.parent().show();

	//Progress Effect...
	var current_value = parseInt($bar.attr('aria-valuenow')) ;
	var total_numbers = parseInt($bar.attr('aria-valuemax'));
	var new_value = current_value + now_processed ;
	var percent = (new_value * 100 / total_numbers) ;
	if(percent>100) percent = 100 ;
	$bar.attr('aria-valuenow' , new_value).css('width' , percent.toString() + "%");

	//Next Stage...
	$("#btnPrepare").click() ;
}

function drawingRandom(max)
{
	var $input = $("#txtDrawingGuess");

	for($i=1 ; $i<100 ; $i++) {
		setTimeout( function() {
			var random_number = Math.floor(Math.random() * (max)) + 1 ;
			$input.val(forms_pd(random_number.toString()));
		},10*$i) ;
	}
	setTimeout( function() {
		$("#btnSubmit").click() ;
	} ,11*$i);

}

function drawingDelete(key_number , post_id)
{
	$.ajax({
		url: url("manage/club/save/draw_delete/"+key_number),
		cache: false
	})
		.done(function (html) {
			divReload( 'divWinnersTable' ) ;
			rowUpdate('tblPosts',post_id)
		});
}