<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySubAdReview extends Model
{
    use HasFactory;

    protected $fillable=[
        'stars',
        'companySubAdId',
        'userId',
        'comment',
    ];
}
