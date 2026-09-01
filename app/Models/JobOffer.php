<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    /** @use HasFactory<\Database\Factories\JobOfferFactory> */
    use HasFactory;

    protected $fillable = [
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
        'invitation_id',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class, 'invitation_id');
    }
}
