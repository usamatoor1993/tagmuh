<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable=[
       'user_id',
       'name',
       'email',
       'address',
       'store_hours',
       'category',
       'reels',
       'web_link',
       'profile_photo',
       'cover_photo',
       'is_verified',
       'likes',
       'dislikes',
       'rating',
       'is_selected',
       'event_permission',
       'start_time',
       'end_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function employee()
    {
        return $this->hasMany(User::class,'company_id');
    }
    public function portfolio()
    {
        return $this->hasMany(Portfolio::class,'company_id');
    }
    public function category()
    {
        return $this->belongsTo(Service::class,'category');
    }

    public function company_ad()
    {
        return $this->hasMany(CompanyAd::class,'company_id');
    }

    public function company_sub_ad()
    {
        return $this->hasMany(CompanySubAd::class,'company_id');
    }
    
}
