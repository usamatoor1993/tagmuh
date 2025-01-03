<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySubAdReview extends Model
{
    use HasFactory;

    protected $fillable=[
        'stars',
        'company_sub_ad_id',
        'user_id',
        'comment',
    ];
}
