<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPermission extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'company_id',
        'post',
        'chat',
        'group_chat',
        'group_create',
        'ads',
        
    ];
}
