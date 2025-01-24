<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioAd extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'portfolio_id',
        'name',
        'images',
        'description',
        'price',
        'total_quantity',
    ];
    
    protected $casts = [
        'images' => 'array',
    ];

}
