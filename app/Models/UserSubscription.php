<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSubscription extends Model
{
    //
    use HasFactory;

    protected $fillable = ['user_id','subscription_id','price','expire_date','payment_gateway','payment_status','status','transaction_id','manual_payment_image'];
    protected $casts = ['status'=>'integer'];



    public function subscription()
    {
        return $this->belongsTo(Subscription::class,'subscription_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
