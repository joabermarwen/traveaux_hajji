<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\JobPost;
use App\Models\Language;
use App\Models\Page;
use App\Models\SubCategory;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Gateway\PaypalSdk\ProcessController as PaypalSdkController;
use App\Http\Controllers\Gateway\StripeV3\ProcessController as StripeV3Controller;
class SiteController extends Controller
{
    //
    public function index(){
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }
        $categories = Category::active()->orderBy('name')->get();
        $keywords   = JobPost::approved()->groupBy('category_id')->with('category')->selectRaw('count(*) as count, category_id')->orderBy('count', 'desc')->take(4)->get();
        $pageTitle = 'Home';
        $sections = Page::where('tempname',activeTemplate())->where('slug','/')->first();
        $seoContents = $sections->seo_content??'';
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle','sections','seoContents','seoImage','categories','keywords'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',activeTemplate())->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle','sections','seoContents','seoImage'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname',activeTemplate())->where('slug','contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact',compact('pageTitle','user','sections','seoContents','seoImage'));
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('slug',$slug)->where('data_keys','policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages',$seoContents->image,getFileSize('seo'),true) : null;
        return view('Template::policy',compact('policy','pageTitle','seoContents','seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogDetails($slug){
        $blog = Frontend::where('slug',$slug)->where('data_keys','blog.element')->firstOrFail();
        $blogs                             = Frontend::where('data_keys', 'blog.element')->where('slug', '!=', $slug)->orderBy('id', 'desc')->take(5)->get();
        $pageTitle = $blog->data_values->title;
        $seoContents = $blog->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('blog',$seoContents->image,getFileSize('seo'),true) : null;
        return view('Template::blog_details',compact('blog','blogs','pageTitle','seoContents','seoImage'));
    }

    public function blogs()
    {
        $pageTitle = "Blogs";
        $blogs     = Frontend::where('data_keys', 'blog.element')->orderBy('id', 'desc')->paginate(getPaginate(9));
        $sections  = Page::where('tempname', activeTemplate())->where('slug', 'blog')->first();
        return view('Template::blogs', compact('pageTitle', 'blogs', 'sections'));
    }


    public function cookieAccept(){
        Cookie::queue('gdpr_cookie',gs('site_name') , 43200);
    }

    public function cookiePolicy(){
        $cookieContent = Frontend::where('data_keys','cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE,404);
        $pageTitle = 'Cookie Policy';
        $cookie = Frontend::where('data_keys','cookie.data')->first();
        return view('Template::cookie',compact('pageTitle','cookie'));
    }

    public function placeholderImage($size = null){
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if(gs('maintenance_mode') == Status::DISABLE){
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys','maintenance.data')->first();
        return view('Template::maintenance',compact('pageTitle','maintenance'));
    }

    public function allJobs()
    {
        $pageTitle  = "All Jobs";
        $jobs       = JobPost::approved()->where('vacancy_available', '>', 0)->orderBy('id', 'desc')->paginate(getPaginate(9));
        $categories = Category::active()->orderBy('name')->take(20)->get();
        return view('Template::job.index', compact('pageTitle', 'jobs', 'categories'));
    }

    public function jobDetails($id, $title)
    {
        $pageTitle = 'Job Details';
        $job       = JobPost::approved()->where('id', $id)->firstOrFail();

        $seoContents['keywords']           = explode(' ', $job->title) ?? [];
        $seoContents['social_title']       = $job->title;
        $seoContents['description']        = strLimit(strip_tags($job->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($job->description), 150);
        $seoContents['image']              = getImage('assets/images/job/' . @$job->attachment, '550x400');
        $seoContents['image_size']         = '600x400';

        return view('Template::job.details', compact('pageTitle', 'job', 'seoContents'));
    }

    public function subcategories($id, $title)
    {
        $pageTitle = ucwords(str_replace('-', ' ', $title));
        $category  = Category::active()->withCount('subcategory as subcategory')->findOrFail($id);
        if (!$category->subcategory) {
            return to_route('category.jobs', ['name' => slug($category->name), 'id' => $category->id]);
        }

        $subCategories = SubCategory::active()->where('category_id', $id)->with('posts')->withCount([
            'posts as jobApprove' => function ($jobPost) {
                $jobPost->approved();
            },
        ])->paginate(getPaginate());

        return view('Template::subcategories', compact('pageTitle', 'subCategories'));
    }

    public function categories()
    {
        $pageTitle  = "All Categories";
        $categories = Category::active()->with('jobPosts')->orderBy('name')->paginate(getPaginate());
        return view('Template::categories', compact('pageTitle', 'categories'));
    }

    public function subcategoryJobs($id, $name)
    {

        $pageTitle  = ucwords(str_replace('-', ' ', $name));
        $jobs       = JobPost::approved()->where('subcategory_id', $id)->orderBy('id', 'desc')->paginate(getPaginate());
        $categories = Category::featured()->orderBy('name')->get();
        return view('Template::job.index', compact('pageTitle', 'jobs', 'categories'));
    }

    public function categoryJobs($id, $name)
    {
        $pageTitle  = ucwords(str_replace('-', ' ', $name));
        $jobs       = JobPost::approved()->where('category_id', $id)->orderBy('id', 'desc')->paginate(getPaginate());
        $categories = Category::featured()->orderBy('name')->get();
        return view('Template::job.index', compact('pageTitle', 'jobs', 'categories'));
    }

    public function sortJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|string|in:today,monthly,weekly',
            'sort' => 'nullable|string|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all(),
            ]);
        }

        $date       = $request->get('date');
        $sort       = $request->get('sort');
        $categoryId = $request->get('category_id');

        $jobs = JobPost::query();

        if ($request->ajax()) {
            $jobs = $this->filterJob($jobs, $categoryId, $sort, $date);
        }
        $jobs = $jobs->approved()->paginate(getPaginate(9));
        return view('Template::partials.jobs', compact('jobs'));
    }

    public function jobSearch()
    {
        $category  = request()->category;
        $search    = request()->search;
        $pageTitle = "Search Result";
        $jobs      = JobPost::query();

        if ($search) {
            $jobs = $jobs->where('title', 'LIKE', '%' . $search . '%');
        }

        if ($category) {
            $jobs = $jobs->where('category_id', $category);
        }

        $jobs       = $jobs->approved()->paginate(getPaginate());
        $categories = Category::active()->get();
        return view('Template::job.index', compact('jobs', 'categories', 'pageTitle'));
    }

    protected function filterJob($jobs, $categoryId, $sort, $date)
    {

        if ($categoryId && !in_array('all', $categoryId)) {
            $jobs = $jobs->whereIn('category_id', $categoryId);
        }
        if ($sort) {
            $jobs = $jobs->orderBy('rate', $sort);
        }
        if ($date) {
            if ($date == 'today') {
                $jobs = $jobs->whereDate('created_at', Carbon::today()->format('Y-m-d H:i:s'));
            }
            if ($date == 'weekly') {
                $jobs = $jobs->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d H:i:s'), Carbon::now()->endOfWeek()->format('Y-m-d H:i:s')]);
            }
            if ($date == 'monthly') {
                $jobs = $jobs->whereDate('created_at', '>=', Carbon::now()->subDay(30)->format('Y-m-d H:i:s'));
            }
        }
        return $jobs;
    }

    public function subscriptions()
    {
        $pageTitle  = trans_case("Subscriptions");
        $subscription_types = SubscriptionType::with('subscriptions')->whereHas('subscriptions')->select('id','type','validity')->get();
        $subscriptions = Subscription::with(['features','subscription_type'])->latest()->select(['id','subscription_type_id','title','logo','price'])->where('status',1)->paginate(getPaginate());
        return view('Template::subscription',compact('pageTitle','subscription_types','subscriptions'));
    }
    public function filter_subscriptions(Request $request)
    {

        $type_id = $request->type_id;
        if ($type_id == 'all') {
            $subscriptions = Subscription::with(['features','subscription_type'])
                ->latest()
                ->select(['id','subscription_type_id','title','logo','price'])
                ->where('status',1)
                ->paginate(18);
        }else {
            $subscriptions = Subscription::with(['features','subscription_type'])
                ->latest()->select(['id','subscription_type_id','title','logo','price'])
                ->where('subscription_type_id',$type_id)
                ->where('status',1)
                ->get();
        }
        return $subscriptions->count() >= 1 ? view('templates.basic.subscription-box', compact(['subscriptions','type_id']))->render() :  response()->json(['status' => 'nothing']);
    }
    public function buy_subscription(Request $request){

        if($request->has('subscription_id')) {
            $user = Auth::user();
            $subscription_details = Subscription::with('subscription_type:id,validity')->select(['id','subscription_type_id','price'])
                ->where('id',$request->subscription_id)
                ->where('status','1')->first();
            if($subscription_details) {
                $expire_date = Carbon::now()->addDays($subscription_details?->subscription_type?->validity);
                $title = __('Buy Subscription');
                $total = $subscription_details->price;

                $name = $user->first_name.' '.$user->last_name;
                $email = $user->email;
                $user_type = $user->user_type == 1 ? 'client' : 'freelancer';
                $payment_status = $request->selected_payment_gateway === 'wallet' ? 'complete' : 'pending';
                $status = $request->selected_payment_gateway === 'wallet' ? 1 : 0;
                session()->put('user_id',$user->id);
                session()->put('user_type',$user_type);
                $buy_subscription = UserSubscription::create([
                    'user_id' => $user->id,
                    'subscription_id' => $subscription_details->id,
                    'price' => $total,
                    'expire_date' => $expire_date,
                    'payment_gateway' => $request->selected_payment_gateway,
                ]);
                if($request->selected_payment_gateway==='Stripe Checkout'){

                    $gateway = Gateway::automatic()->where('alias','StripeV3')->firstOrFail();

                    $deposit = new Deposit();
                    $deposit->user_id = $user->id;
                    $deposit->amount = $total;
                    $deposit->final_amount = $total;
                    $deposit->method_currency = 'EUR'; // Change currency as needed
                    $deposit->status = Status::PAYMENT_INITIATE;
                    $deposit->method_code = $gateway->code;
                    $deposit->payment_gateway = $request->selected_payment_gateway; // Assuming Stripe
                    $deposit->subscription_id = $buy_subscription->id;
                    $deposit->save();
                    $response= StripeV3Controller::process($deposit);
                    $response = json_decode($response, true);
                    if (!empty($response['session']['url'])) {
                        return redirect($response['session']['url']);
                    }
                    // $response = $this->createStripeSession($deposit);
                    // return redirect($response['checkout_url']);
                }else if($request->selected_payment_gateway==='Paypal Express'){
                    $gateway = Gateway::automatic()->where('alias','PaypalSdk')->firstOrFail();

                    $deposit = new Deposit();
                    $deposit->user_id = $user->id;
                    $deposit->amount = $total;
                    $deposit->final_amount = $total;
                    $deposit->method_currency = 'EUR'; // Change currency as needed
                    $deposit->status = Status::PAYMENT_INITIATE;
                    $deposit->method_code = $gateway->code;
                    $deposit->payment_gateway = $request->selected_payment_gateway; // Assuming Stripe
                    $deposit->subscription_id = $buy_subscription->id;
                    $deposit->save();
                    $response = PaypalSdkController::process($deposit);
                    $response = json_decode($response, true);
                    if (!empty($response['redirect_url'])) {
                        return redirect($response['redirect_url']);
                    }
                }
            }
        }
    }
    private function createStripeSession($deposit)
    {
        $StripeAcc = Gateway::automatic()->where('alias','StripeV3')->firstOrFail();
        $gateway_parameter = json_decode($StripeAcc->gateway_parameters);


        // Set Stripe API Key
        \Stripe\Stripe::setApiKey($gateway_parameter->secret_key->value);

        try {
            // Create a new Stripe Checkout session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'unit_amount' => round($deposit->final_amount, 2) * 100,  // Convert to cents
                        'currency' => 'EUR',  // You can change the currency as needed
                        'product_data' => [
                            'name' => 'Subscription Payment',  // Subscription details
                            'description' => 'Payment for subscription',
                            'images' => [siteLogo()],
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'cancel_url' => route('payment.cancel'),  // Route to handle canceled payments
                'success_url' => route('payment.success', ['session_id' => '{CHECKOUT_SESSION_ID}']),  // Route to handle successful payments
            ]);

            // Save Stripe session ID in the deposit record for later reference
            $deposit->btc_walet = $session->id;
            $deposit->save();

            // Return the checkout session URL
            return [
                'checkout_url' => $session->url
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle any Stripe API errors
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
