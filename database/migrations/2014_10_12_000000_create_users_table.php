<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('email')->nullable()->index();
			$table->string('mobile')->nullable()->index();
			$table->string('name_first')->nullable();
			$table->string('name_last')->nullable();
			$table->string('nickname')->nullable() ;
			$table->timestamp('register_date')->nullable() ;
			$table->text('note');

			$table->float('capital', 15, 2);
			$table->float('normal_loan', 15, 2);
			$table->float('urgent_loan' , 15 , 2);

			$table->unsignedInteger('sponsor_id')->nullable()->index() ;

			$table->string('password')->nullable();
			$table->boolean('password_force_change')->default(0);
			$table->longText('meta')->nullable();
			$table->rememberToken();
			$table->timestamps();
			$table->softDeletes();
			$table->timestamp('published_at')->nullable();
			$table->unsignedInteger('created_by')->default(0)->index();
			$table->unsignedInteger('updated_by')->default(0);
			$table->unsignedInteger('deleted_by')->default(0);
			$table->unsignedInteger('published_by')->default(0);

			$table->index(['name_last', 'name_first']);
			$table->index('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
