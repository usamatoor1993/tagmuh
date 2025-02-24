<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'company_id',
        'issue_date',
        'invoice_number',
        'company_name',
        'company_email',
        'company_address',
        'bank_name',
        'bank_code',
        'account_name',
        'account_number',
        'staus',
    ];
}
