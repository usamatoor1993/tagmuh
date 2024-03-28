<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySubAd extends Model
{
    use HasFactory;

    protected $fillale = [
        'images',
        'productName',
        'totalProduct',
        'description',
        'companyAdId',
        'companyId',
        'userId',

    ];
}
