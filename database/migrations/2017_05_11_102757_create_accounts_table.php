<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function (Blueprint $table) {
			$table->increments('id');

			$table->unsignedInteger('user_id')->index()  ;
			$table->integer('account_no')->index() ;
			$table->string('title')->index() ;
			$table->text('note');

			$table->float('capital', 15, 2);
			$table->float('normal_loan', 15, 2);
			$table->float('urgent_loan' , 15 , 2);

			$table->longText('meta')->nullable();
			$table->timestamp('opened_at')->nullable() ;

			$table->timestamps();
			$table->softDeletes();
			$table->unsignedInteger('created_by')->default(0)->index();
			$table->unsignedInteger('updated_by')->default(0);
			$table->unsignedInteger('deleted_by')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('accounts');
	}
}
