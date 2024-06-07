<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable=[
       'user_id','name','email','address','store_hours','category','reels','webLink','profilePhoto','coverPhoto','isVerified','likes','dislikes','rating','isSelected',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function employee()
    {
        return $this->hasMany(Employee::class,'companyId');
    }
    public function portfolio()
    {
        return $this->hasMany(Portfolio::class,'companyId');
    }
}
