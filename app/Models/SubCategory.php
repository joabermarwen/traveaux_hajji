<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    //
    use GlobalStatus;

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function posts()
	{
		return $this->hasMany(JobPost::class, 'subcategory_id');
	}
}
