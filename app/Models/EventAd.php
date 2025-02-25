<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAd extends Model
{
    use HasFactory;
    protected $fillable=[
        'event_id',
        'title',
        'description',
        'images',
    ];
    public function event()
    {
        return $this->belongsTo(Event::class,'event_id');
    }

    protected $casts = [
        'images' => 'array',
    ];
}
