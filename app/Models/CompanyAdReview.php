<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAdReview extends Model
{
    use HasFactory; 


    protected $fillable=[
        'stars',
        'company_ad_id',
        'user_id',
        'comment',
    ];
}
