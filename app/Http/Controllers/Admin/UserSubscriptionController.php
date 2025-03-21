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

        $query = UserSubscription::whereHas('user')->with([
            'subscription:id,subscription_type_id',
            'subscription.subscription_type',
            'user:id,email,firstname,lastname'
        ]);

        // Apply search filter before pagination
        if (request('search')) {
            $query->whereHas('user', function ($q) {
                $q->where('email', 'like', '%' . request('search') . '%')
                  ->orWhere('username', 'like', '%' . request('search') . '%');
            });
        }

        // Apply pagination
        $all_subscriptions = $query->latest()->paginate(getPaginate());
        $active_subscription = UserSubscription::whereHas('user')->where('status',1)->count();
        $inactive_subscription = UserSubscription::whereHas('user')->where('status',0)->count();
        $manual_subscription = UserSubscription::whereHas('user')->where('payment_gateway','manual_payment')->count();
        return view('admin.subscription.user.index',compact('pageTitle','all_subscriptions','active_subscription','inactive_subscription','manual_subscription'));
    }
    public function updateStatus($id){
        $sub = UserSubscription::findOrFail($id);
        $sub->status=!$sub->status;
        $sub->save();
        $message= trans_case('Subscription status updated for this user');
        $notify[] = ["success", $message];
        return back()->withNotify($notify);
    }
}
