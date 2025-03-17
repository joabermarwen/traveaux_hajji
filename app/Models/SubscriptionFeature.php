<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionFeature extends Model
{
    //
    use HasFactory;

    protected $fillable = ['subscription_id','feature','status'];
}
