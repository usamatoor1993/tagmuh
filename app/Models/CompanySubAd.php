<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySubAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'images',
        'product_name',
        'total_product',
        'price',
        'description',
        'company_ad_id',
        'company_id',
        'user_id',
        'rating',
    ];
}
