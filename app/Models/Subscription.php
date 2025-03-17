<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    //
    use HasFactory;

    protected $fillable = ['subscription_type_id','title','logo','price','status'];
    protected $casts = ['status'=>'integer'];



    public function features()
    {
        return $this->hasMany(SubscriptionFeature::class,'subscription_id','id');
    }

    public function subscription_type()
    {
        return $this->belongsTo(SubscriptionType::class,'subscription_type_id','id');
    }

    public function user_subscriptions()
    {
        return $this->hasMany(UserSubscription::class,'subscription_id','id');
    }
}
