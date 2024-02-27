<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable=[
        'companyId','userId','serviceId','userServiceId','service','tokenAmount','remaingAmount','phoneNumber','serviceType','catId','durationStart','durationEnd','status','canceledBy'
    ];
}
