<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable=[
        'company_id',
        'user_id',
        'service_id',
        'user_service_id',
        'service',
        'token_amount',
        'remaining_amount',
        'phone_number',
        'service_type',
        'category_id',
        'duration_start',
        'duration_end',
        'status',
        'canceled_by'
    ];
}
