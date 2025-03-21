<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'image',
        'description',
        'date',
        'time',
        'event_by',
        'interested',
        'going',
        'email',
        'ticket',
        'status',
        'location',
        'user_id',
        'rating',
        'company_id',


    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}
