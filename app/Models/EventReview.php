<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventReview extends Model
{
    use HasFactory;

    protected $fillable=[
        'stars',
        'eventId',
        'userId',
        'comment',
    ];
}
