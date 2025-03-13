<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('pages')->insert([
            [
                'name' => 'HOME',
                'slug' => '/',
                'tempname' => 'templates.basic.',
                'secs' => json_encode(["counter", "overview", "top_freelancer", "job_post", "job_category", "blog"]),
                'seo_content' => null,
                'is_default' => 1,
            ],
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'tempname' => 'templates.basic.',
                'secs' => null,
                'seo_content' => null,
                'is_default' => 1,
            ],
            [
                'name' => 'Contact',
                'slug' => 'contact',
                'tempname' => 'templates.basic.',
                'secs' => null,
                'seo_content' => null,
                'is_default' => 1,
            ],
            [
                'name' => 'FAQs',
                'slug' => 'faq',
                'tempname' => 'templates.basic.',
                'secs' => json_encode(["faq"]),
                'seo_content' => null,
                'is_default' => 0,
            ],
        ]);
        DB::table('general_settings')->insert([
            'site_name'        => 'On Traveaux',
            'cur_text'         => 'EUR',
            'cur_sym'          => '€',
            'email_from'       => 'info@viserlab.com',
            'email_from_name'  => null,
            'email_template'   => '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> ... (Your email template HTML here) ...',
            'sms_template'     => 'hi {{fullname}} ({{username}}), {{message}}',
            'sms_from'         => 'ViserAdmin',
            'push_title'       => null,
            'push_template'    => null,
            'base_color'       => 'FF5851',
            'mail_config'      => json_encode(["name" => "php"]),
            'sms_config'       => json_encode([
                "name" => "nexmo",
                "clickatell" => ["api_key" => "----------------"],
                "infobip" => ["username" => "------------8888888", "password" => "-----------------"],
                "message_bird" => ["api_key" => "-------------------"],
                "nexmo" => ["api_key" => "----------------------", "api_secret" => "----------------------"],
                "sms_broadcast" => ["username" => "----------------------", "password" => "-----------------------------"],
                "twilio" => ["account_sid" => "-----------------------", "auth_token" => "---------------------------", "from" => "----------------------"],
                "text_magic" => ["username" => "-----------------------", "apiv2_key" => "-------------------------------"],
                "custom" => [
                    "method" => "get",
                    "url" => "https://hostname/demo-api-v1",
                    "headers" => ["name" => ["api_key"], "value" => ["test_api 555"]],
                    "body" => ["name" => ["from_number"], "value" => ["5657545757"]]
                ]
            ]),
            'firebase_config'  => null,
            'global_shortcodes'=> json_encode([
                "site_name" => "Name of your site",
                "site_currency" => "Currency of your site",
                "currency_symbol" => "Symbol of currency"
            ]),
            'approve_job'      => 0,
            'ev'               => 0,
            'en'               => 1,
            'sv'               => 0,
            'sn'               => 0,
            'pn'               => 0,
            'kv'               => 0,
            'multi_language'   => 0,
            'force_ssl'        => 0,
            'in_app_payment'   => 0,
            'maintenance_mode' => 0,
            'secure_password'  => 0,
            'agree'            => 0,
            'registration'     => 1,
            'active_template'  => 'basic',
            'system_customized'=> 0,
            'paginate_number'  => 20,
            'currency_format'  => 1,
            'available_version'=> '3.0',
        ]);
        Admin::create([
            'name'=>'Super Admin',
            'email'=>'admin@gmail.com',
            'username'=>'admin',
            'password'=>Hash::make('admin123456')
        ]);
    }
}
