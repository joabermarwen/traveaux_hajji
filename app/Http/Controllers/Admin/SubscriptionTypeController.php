<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class SubscriptionTypeController extends Controller
{
    //
    public function index(){
        $pageTitle  = trans_case("All Subscription Types");
        $types = SubscriptionType::latest()->paginate(getPaginate());
        return view('admin.subscription.type.index',compact('types','pageTitle'));
    }
    public function create(){
        $pageTitle = trans_case("Add Subscription Type");
        return view('admin.subscription.type.create',compact('pageTitle'));
    }
    public function store(Request $request){
        $request->validate([
            'type'=> 'required|unique:subscription_types|max:191',
            'validity'=> 'required|integer|between:7,365'
        ],
            [
                'validity.between' => __('Validity must be a number between 7 to 365 days'),
            ]);
        SubscriptionType::create([
            'type' => $request->type,
            'validity' => $request->validity,
        ]);
        $notify[] = ['success', 'Subscription Type Created Successfully'];
        return back()->withNotify($notify);
    }

    public function edit($id){
        $pageTitle = trans_case("Edit Subscription Type");
        $type = SubscriptionType::findOrFail($id);
        return view('admin.subscription.type.edit',compact('pageTitle','type'));
    }
    public function update(Request $request,$id){
        $request->validate([
            'type'=> 'required|unique:subscription_types,type,'.$id,
            'validity'=> 'required|integer|between:7,365'
        ],
            [
                'validity.between' => __('Validity must be a number between 7 to 365 days'),
            ]);
        SubscriptionType::findOrFail($id)->update([
            'type' => $request->type,
            'validity' => $request->validity,
        ]);
        $notify[] = ['success', 'Subscription Type Updated Successfully'];
        return back()->withNotify($notify);
    }
    public function delete($id){
        SubscriptionType::findOrFail($id)->delete();
        $notify[] = ['success', 'Subscription Type Deleted Successfully'];
        return back()->withNotify($notify);
    }
}
