<?php
return [
	'person'           => "نفر",
	'people'           => "اشخاص",
	'modules'          => "ماژول‌ها",
	'deleted_user'     => "یک کاربر سابق",
	'particular_user'  => "یک کاربر خاص",
	'user'             => "شخص",
	'users'            => "اشخاص",
	'site_users'       => "اشخاص",
	'user_role'        => "نقش",
	'without_role'     => "بدون نقش",
	'login_enabled'    => "قادر به لاگین",
	'used_as_username' => "به عنوان نام کاربری (username) هنگام ورود استفاده می‌شود.",

	'commands' => [
		'restrict_access' => "قطع دسترسی" ,
		'permit'          => "سطح دسترسی",
		'change_password' => "تغییر گذرواژه",
		'set_credentials' => "نصب یا تغییر گذرواژه",
		'block'           => "مسدودسازی",
		'unblock'         => "رفع مسدودی",
		'login_as'        => "لاگین به جای ایشان",
		'create_new_user' => "افزودن :role_title جدید",
		'all_users'       => "همه اشخاص",

	],

	'admins' => [
		'admin_type'      => "منصب مدیریت",
		'super_admin'     => "مدیر کل",
		'ordinary_admin'  => "مدیر عملیات",
		'developer'       => "برنامه‌نویس",
		'superAdmin_hint' => 'مدیر کل علاوه بر دسترسی‌های داده‌شده، می‌تواند به تنظیمات سایت و اطلاعات مدیران دیگر دست‌رسی داشته باشد.',
	],

	"form" => [
		"deleted_person"              => '[؟]',
		'user_deleted'                => "حساب این کاربر پاک شده است.",
		"notify-with-email"           => 'به کاربر از طریق ایمیل اطلاع‌رسانی شود.',
		"notify-with-sms"             => 'به کاربر از طریق پیامک اطلاع‌رسانی شود.',
		"notify"                      => 'به کاربر از طریق پیامک و ایمیل اطلاع‌رسانی شود.',
		"will-be-notified"            => 'به کاربر از طریق پیامک و ایمیل اطلاع‌رسانی می‌شود.',
		"default_password"            => 'شماره‌ی تلفن همراه به عنوان گذرواژه در نظر گرفته می‌شود و کاربر در اولین ورود ملزم به تغییر خواهد بود.',
		"hard_delete_notice"          => 'این حذف غیر قابل بازگشت خواهد بود.',
		'delete_notice_when_has_role' => "تمام نقش‌های این کاربر از دسترس خارج خواهند شد.",
		"password_hint"               => 'حداقل هشت کاراکتر حساس به کوچکی و بزرگی حروف. تمام ارقام به انگلیسی تبدیل می‌شوند.',
		'password_change_sms'         => "رمز عبور شما در سایت :site_title تغییر یافت: :new_password",
		'as_a'                        => "به عنوان :role_title",
		'now_without'                 => "اکنون چنین نقشی ندارد.",
		'now_active'                  => "اکنون در این نقش فعال است.",
		'now_blocked'                 => "اکنون این نقش را دارد، ولی مسدود است.",
		'detach_this_role'            => "حذف این نقش",
		'attach_this_role'            => "افزودن این نقش",
		'recover_password'            => "بازیابی گذرواژه",
		'send_password_reset_link'    => "ارسال لینک بازیابی گذرواژه",
		'have_a_code'                 => "کد بازیابی دارم",
		'check_password_token'        => "بررسی کد",
		'login_enabled_hint' => "برای لاگین، شماره موبایل و گذرواژه لازم است." ,
	],

	'criteria' => [
		'all'     => "همه",
		'actives' => "فعال‌ها",
		'pending' => "منتظر تأیید",
		'banned'  => "مسدودها",
		'bin'     => "زباله‌دان",
	],

	"edu_level_full"  => [
		'0' => 'نامشخص',
		'1' => 'پایین‌تر از دیپلم متوسطه',
		'2' => 'دیپلم متوسطه',
		'3' => 'کاردانی',
		'4' => 'کارشناسی',
		'5' => 'کارشناسی ارشد',
		'6' => 'دکترا و بالاتر',
	],
	"edu_level_short" => [ //short form of education
	                       '0' => ' نامشخص',
	                       '1' => 'زیر دیپلم',
	                       '2' => 'دیپلم',
	                       '3' => 'کاردانی',
	                       '4' => 'کارشناسی',
	                       '5' => 'ارشد',
	                       '6' => 'دکترا',
	],
	"gender"          => [
		'1' => 'آقا',
		'2' => 'خانم',
		'3' => 'سایر',
	],
];