<?php
// @TODO: Delete this file

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		/*--------------------------------------------------------------------------
		| Users ...
		*/
		DB::table('users')->insert([
			[
				'mobile' => "09122835030",
				'email' => "admin@yasnateam.com",
				'name_first' => "ادمین",
				'name_last' => "یسنا",
				'password' => bcrypt('1'),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			]
		]);

		/*-----------------------------------------------
		| Settings ...
		*/
		DB::table('settings')->insert([
			[
				'slug' => "site_title",
				'title' => "عنوان سایت",
				'category' => "upstream",
				'data_type' => "text",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('یسناوب') ,
				'default_value' => 'تعاونی قرض‌الحسنه امین' ,
				'developers_only' => 1,
				'is_resident' => 1,
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "site_locales",
				'title' => "زبان‌های سایت",
				'category' => "upstream",
				'data_type' => "array",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('fa') ,
				'default_value' => "fa" ,
				'developers_only' => 1,
				'is_resident' => 1,
//				'is_sensitive' => "true",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "site_activeness",
				'title' => "فعالیت سایت",
				'category' => "template",
				'data_type' => "boolean",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('1') ,
				'default_value' => '1' ,
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "1",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "overall_activeness",
				'title' => "فعالیت هسته‌ی سایت",
				'category' => "upstream",
				'data_type' => "boolean",
				'default_value' => '1' ,
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('1') ,
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "ssl_available",
				'title' => "آمادگی اس‌اس‌ال",
				'category' => "upstream",
				'data_type' => "boolean",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('0') ,
				'default_value' => '0' ,
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "currency",
				'title' => "واحد پول",
				'category' => "template",
				'data_type' => "text",
				'default_value' => 'تومان' ,
				'developers_only' => 0,
				'is_resident' => "1",
				'is_localized' => "1",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
		]);

		/*-----------------------------------------------
		| Roles ...
		*/
		DB::table('roles')->insert([
			[
				'slug' => "admin",
				'title' => "مدیر",
				'plural_title' => "مدیران",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
				'modules' => json_encode([
						'posts' => ['create','edit','publish','report','delete','bin'] ,
				]),
			],
			[
				'slug' => "member",
				'title' => "عضو",
				'plural_title' => "اعضا",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
				'modules' => null,
			],
		]);

		DB::table('role_user')->insert([
			[
				'user_id' => 1,
				'role_id' => 1,
				'permissions' => "super",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
		]);



	}
}
