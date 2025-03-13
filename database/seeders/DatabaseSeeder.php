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
        $gateways = [
            // PayPal Seeder
            [
                'form_id' => 0,
                'code' => 101,
                'name' => 'Paypal',
                'alias' => 'Paypal',
                'image' => '663a38d7b455d1715091671.png',
                'status' => 1,
                'gateway_parameters' => '{"paypal_email":{"title":"PayPal Email","global":true,"value":"sb-owud61543012@business.example.com"}}',
                'supported_currencies' => '{"AUD":"AUD","BRL":"BRL","CAD":"CAD","CZK":"CZK","DKK":"DKK","EUR":"EUR","HKD":"HKD","HUF":"HUF","INR":"INR","ILS":"ILS","JPY":"JPY","MYR":"MYR","MXN":"MXN","TWD":"TWD","NZD":"NZD","NOK":"NOK","PHP":"PHP","PLN":"PLN","GBP":"GBP","RUB":"RUB","SGD":"SGD","SEK":"SEK","CHF":"CHF","THB":"THB","USD":"$"}',
                'crypto' => 0,
                'extra' => NULL,
                'description' => NULL,
            ],
            [
                'form_id' => 0,
                'code' => 113,
                'name' => 'Paypal Express',
                'alias' => 'PaypalSdk',
                'image' => '663a38ed101a61715091693.png',
                'status' => 1,
                'gateway_parameters' => '{"clientId":{"title":"Paypal Client ID","global":true,"value":"Ae0-tixtSV7DvLwIh3Bmu7JvHrjh5EfGdXr_cEklKAVjjezRZ747BxKILiBdzlKKyp-W8W_T7CKH1Ken"},"clientSecret":{"title":"Client Secret","global":true,"value":"EOhbvHZgFNO21soQJT1L9Q00M3rK6PIEsdiTgXRBt2gtGtxwRer5JvKnVUGNU5oE63fFnjnYY7hq3HBA"}}',
                'supported_currencies' => '{"AUD":"AUD","BRL":"BRL","CAD":"CAD","CZK":"CZK","DKK":"DKK","EUR":"EUR","HKD":"HKD","HUF":"HUF","INR":"INR","ILS":"ILS","JPY":"JPY","MYR":"MYR","MXN":"MXN","TWD":"TWD","NZD":"NZD","NOK":"NOK","PHP":"PHP","PLN":"PLN","GBP":"GBP","RUB":"RUB","SGD":"SGD","SEK":"SEK","CHF":"CHF","THB":"THB","USD":"$"}',
                'crypto' => 0,
                'extra' => NULL,
                'description' => NULL,
            ],

            // Stripe Seeder
            [
                'form_id' => 0,
                'code' => 103,
                'name' => 'Stripe Hosted',
                'alias' => 'Stripe',
                'image' => '663a39861cb9d1715091846.png',
                'status' => 1,
                'gateway_parameters' => '{"secret_key":{"title":"Secret Key","global":true,"value":"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG"},"publishable_key":{"title":"PUBLISHABLE KEY","global":true,"value":"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM"}}',
                'supported_currencies' => '{"USD":"USD","AUD":"AUD","BRL":"BRL","CAD":"CAD","CHF":"CHF","DKK":"DKK","EUR":"EUR","GBP":"GBP","HKD":"HKD","INR":"INR","JPY":"JPY","MXN":"MXN","MYR":"MYR","NOK":"NOK","NZD":"NZD","PLN":"PLN","SEK":"SEK","SGD":"SGD"}',
                'crypto' => 0,
                'extra' => NULL,
                'description' => NULL,
            ],
            [
                'form_id' => 0,
                'code' => 111,
                'name' => 'Stripe Storefront',
                'alias' => 'StripeJs',
                'image' => '663a3995417171715091861.png',
                'status' => 1,
                'gateway_parameters' => '{"secret_key":{"title":"Secret Key","global":true,"value":"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG"},"publishable_key":{"title":"PUBLISHABLE KEY","global":true,"value":"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM"}}',
                'supported_currencies' => '{"USD":"USD","AUD":"AUD","BRL":"BRL","CAD":"CAD","CHF":"CHF","DKK":"DKK","EUR":"EUR","GBP":"GBP","HKD":"HKD","INR":"INR","JPY":"JPY","MXN":"MXN","MYR":"MYR","NOK":"NOK","NZD":"NZD","PLN":"PLN","SEK":"SEK","SGD":"SGD"}',
                'crypto' => 0,
                'extra' => NULL,
                'description' => NULL,
            ],
            [
                'form_id' => 0,
                'code' => 114,
                'name' => 'Stripe Checkout',
                'alias' => 'StripeV3',
                'image' => '663a39afb519f1715091887.png',
                'status' => 1,
                'gateway_parameters' => '{"secret_key":{"title":"Secret Key","global":true,"value":"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG"},"publishable_key":{"title":"PUBLISHABLE KEY","global":true,"value":"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM"},"end_point":{"title":"End Point Secret","global":true,"value":"whsec_lUmit1gtxwKTveLnSe88xCSDdnPOt8g5"}}',
                'supported_currencies' => '{"USD":"USD","AUD":"AUD","BRL":"BRL","CAD":"CAD","CHF":"CHF","DKK":"DKK","EUR":"EUR","GBP":"GBP","HKD":"HKD","INR":"INR","JPY":"JPY","MXN":"MXN","MYR":"MYR","NOK":"NOK","NZD":"NZD","PLN":"PLN","SEK":"SEK","SGD":"SGD"}',
                'crypto' => 0,
                'extra' => '{"webhook":{"title": "Webhook Endpoint","value":"ipn.StripeV3"}}',
                'description' => NULL,
            ],
        ];

        // Insert the data into the gateways table
        DB::table('gateways')->insert($gateways);

    }
}
