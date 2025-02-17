<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAd extends Model
{
    use HasFactory;
    protected $fillable = [
        'ac_service',
        'images',
        'business_name',
        'business_website',
        'business_location',
        'business_phone_number',
        'business_email',
        'business_description',
        'company_id',
        'user_id',
        'status',
        'rating',
        'price',
    ];

    public function subAd()
    {
        return $this->hasMany(CompanySubAd::class, 'company_ad_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
}
