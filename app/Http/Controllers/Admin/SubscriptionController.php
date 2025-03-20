<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionFeature;
use App\Models\SubscriptionType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    //
    public function index()
    {
        $pageTitle  = trans_case("All Subscriptions");
        $all_subscriptions = Subscription::with('subscription_type')->latest()->paginate(getPaginate());
        return view('admin.subscription.index',compact('pageTitle','all_subscriptions'));
    }
    public function create()
    {
        $pageTitle = trans_case("Add Subscription");
        $all_types = SubscriptionType::all_types();
        return view('admin.subscription.create',compact('pageTitle','all_types'));
    }
    public function store(Request $request)
    {


            $request->validate([
                'type'=> 'required',
                'title'=> ['required',Rule::unique('subscriptions')->where(fn ($query) => $query->where('subscription_type_id', request()->type)),'max:191'],
                'price'=> 'required|gt:0',
                'logo'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:1024|dimensions:max_width=50,max_height=50',
                'feature'=> 'required|array',
                'status'=> 'nullable|array',
            ]);
            $logo = null;
            if ($request->file('logo')) {
                $request->validate([
                    'logo'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:1024|dimensions:max_width=50,max_height=50',
                ]);
                $logo=fileUploader($request->logo, getFilePath('subscription'), getFileSize('subscription'));
            }

            DB::beginTransaction();
            try {
                $subscription = Subscription::create([
                    'subscription_type_id' => $request->type,
                    'title' => $request->title,
                    'price' => $request->price,
                    'logo' => $logo,
                ]);
                $arr = [];
                foreach($request->feature as $key => $attr) {
                    $arr[] = [
                        'subscription_id' => $subscription->id,
                        'feature' => $request->feature[$key] ?? '',
                        'status' => $request->status[$key] ?? 'off',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                $data = Validator::make($arr,["*.feature" => "required"]);
                $data->validated();
                SubscriptionFeature::insert($arr);
                $message = trans_case('New Subscription Successfully Added');
                DB::commit();
                $notify[] = ["success", $message];
                return back()->withNotify($notify);
            }catch(Exception $e){}
    }
    public function edit($id){
        $pageTitle = trans_case("Edit Subscription");
        $subscription = Subscription::with('subscription_type')->findOrFail($id);
        $all_types = SubscriptionType::all_types();
        return view('admin.subscription.edit',compact('pageTitle','subscription','all_types'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'type'=> 'required',
            'title'=> ['required',Rule::unique('subscriptions')->ignore($id)->where(fn ($query) => $query->where('subscription_type_id', request()->type)),'max:191'],
            'price'=> 'required|gt:0',
            'logo'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:1024|dimensions:max_width=50,max_height=50',
            'feature'=> 'required|array',
            'status'=> 'nullable|array',
        ]);
        $logo = null;
        if ($request->file('logo')) {
            $request->validate([
                'logo'=>'nullable|mimes:jpg,jpeg,png,bmp,tiff,svg|max:1024|dimensions:max_width=50,max_height=50',
            ]);
            $old = Subscription::findOrFail($id)->logo;
            $logo=fileUploader($request->logo, getFilePath('subscription'), getFileSize('subscription'), $old);
        }

        DB::beginTransaction();
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->update([
                'subscription_type_id' => $request->type,
                'title' => $request->title,
                'price' => $request->price,
                'logo' => $logo,

            ]);

            $arr = [];
            foreach($request->feature as $key => $attr) {
                $arr[] = [
                    'subscription_id' => $subscription->id,
                    'feature' => $request->feature[$key] ?? '',
                    'status' => $request->status[$key] ?? 'off',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
            $data = Validator::make($arr,["*.feature" => "required"]);
            $data->validated();
            SubscriptionFeature::where('subscription_id', $subscription->id)->delete();
            SubscriptionFeature::insert($arr);
            $message = trans_case('Subscription Successfully Updated');
            DB::commit();
            $notify[] = ["success", $message];
            return back()->withNotify($notify);

        }catch(Exception $e){}
    }
    public function delete($id){
        Subscription::findOrFail($id)->delete();
        $notify[] = ['success', 'Subscription Deleted Successfully'];
        return back()->withNotify($notify);
    }

}
