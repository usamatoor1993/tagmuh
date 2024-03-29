<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAd extends Model
{
    use HasFactory;
    protected $fillable = [
        'acService',
        'images',
        'businessName',
        'businessWebsite',
        'businessLocation',
        'businessPhoneNumber',
        'businessEmail',
        'businessDescription',
        'companyId',
        'userId',
        'status',
    ];

    public function subAd()
    {
        return $this->hasMany(CompanySubAd::class, 'companyAdId');
    }
}
