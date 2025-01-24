<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'business_name',
        'email',
        'business_email',
        'password',
        'phone_number',
        'image',
        'cover_photo',
        'location',
        'business_location',
        'category',
        'card_issue_date',
        'card_expire_date',
        'user_type',
        'status',
        'rating',
        'description',
        'id_card',
        'business_license',
        'business_model',
        'timings',
        'deleteUser',
        'category_verified',
        'isVerified',
        'likes',
        'dislikes',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
