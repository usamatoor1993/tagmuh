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
        'eventBy',
        'interested',
        'going',
        'email',
        'ticket',
        'status',
        'location',
        'userId',


    ];
    public function user()
    {
        return $this->belongsTo(User::class,'userId');
    }
}
