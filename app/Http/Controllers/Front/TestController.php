<?php

namespace App\Http\Controllers\Front;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\jDateTime;


class TestController extends Controller
{
	public function postsConverter()
	{
		$posts = Post::all();
		foreach($posts as $post) {
			if($post->meta('text') or $post->meta('abstract')) {
				$post->text      = $post->meta('text');
				$post->abstract  = $post->meta('abstract');
				$post->starts_at = $post->meta('start_time');
				$post->ends_at   = $post->meta('end_time');
				$post->updateMeta([
					'text'     => false,
					'abstract' => false,
				]);
				$post->save();
			}
		}

		return "DONE :D";
	}

	public function index()
	{
		$array = [687, 29, 42, 60, 1384, 1097, 88, 59, 724];
		Receipt::whereIn('user_id', $array)->delete();
		User::cacheRefreshAll();

		return ":)";
	}

	public function convert()
	{
		$records = DB::table('_records')->where('done','0')->limit(300)->get();
		$count = 0 ;

		foreach($records as $record) {
			$account = Account::find($record->MemberID);
			$date    = jDateTime::createCarbonFromFormat("Ymd", $record->Date)->toDateTimeString();

			if($record->MemberID==0) {
				$type = 'cost' ;
			}
			elseif($record->Credit != 0) {
				$type  = "capital";
			}
			elseif($record->Prest != 0) {
				$type  = "normal_loan";
			}
			elseif($record->Urgent != 0) {
				$type  = "urgent_loan";
			}
			else {
				echo "Strange: " ;
				ss($record) ;
				echo "------" ;
				continue;
			}
			$ok = Transaction::store([
				'id'          => "0",
				'user_id'     => $type=='cost'? 0 : $account->user_id ,
				'account_id'  => $type=='cost'? 0 : $account->id,
				'date'        => $date,
				'amount'      => $record->Credit + $record->Prest + $record->Urgent,
				'type'        => $type,
				'description' => pd($record->Event),
			     'deleted_at' => $record->Active? null : $date ,
			     'deleted_by' => $record->Active? '0' : '1',
			]);

			if($ok) {
				$count++ ;
				DB::table('_records')->where('id',$record->ID)->update(['done' => "1" ,]);
			}
			else {
				dd($record);
			}
		}


		return $count . " done! , last_id: ".$record->ID ;

	}

	public function updater($id)
	{
		return Transaction::where('account_id', $id)->where('type', 'credit')->sum('amount');
		$model = Account::find($id) ;
		$model->cacheUpdate() ;
		ss($model->toArray()) ;
	}
}
