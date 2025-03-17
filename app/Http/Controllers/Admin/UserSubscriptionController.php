<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class UserSubscriptionController extends Controller
{
    //
    public function index(){
        $pageTitle = 'User Subscription';
        $all_subscriptions = UserSubscription::whereHas('user')->with('subscription:id,subscription_type_id','user:id,user_type,email,first_name')->latest()->paginate(getPaginate());
        $active_subscription = UserSubscription::whereHas('user')->where('status',1)->count();
        $inactive_subscription = UserSubscription::whereHas('user')->where('status',0)->count();
        $manual_subscription = UserSubscription::whereHas('user')->where('payment_gateway','manual_payment')->count();
        return view('admin.subscription.user.index',compact('pageTitle','all_subscriptions','active_subscription','inactive_subscription','manual_subscription'));
    }
}
