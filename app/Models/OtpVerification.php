<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;

    public $table = 'otp_verification';
    
    protected $fillable = ['user_id','email','otp','is_used'];
}
