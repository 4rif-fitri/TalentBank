<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    /** @use HasFactory<\Database\Factories\JobOfferFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'salary_amount',
        'salary_period',
        'start_date',
        'end_date',
        'terms_and_conditions',
        'benefits',
        'offer_status',
        'created_at',
        'updated_at',
        'expires_at',
        'position_id',
        'sender_profile_id',
        'receiver_profile_id',
    ];

    public function sender()
    {
        return $this->belongsTo(UserProfile::class, 'sender_profile_id');
    }

    public function receiver()
    {
        return $this->belongsTo(UserProfile::class, 'receiver_profile_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
