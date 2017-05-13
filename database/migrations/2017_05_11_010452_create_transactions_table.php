<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id')->index()  ;
			$table->unsignedInteger('account_id')->index()  ;

			$table->timestamp('date')->index() ;
			$table->float('amount',15,2) ;
			$table->string('type',20)->index() ;

			$table->text('description') ;
			$table->text('note');
			$table->longText('meta')->nullable();

			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('created_by')->default(0)->index();
			$table->unsignedInteger('updated_by')->default(0);
			$table->unsignedInteger('deleted_by')->default(0);

			//$table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade') ;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('transactions');
	}
}
