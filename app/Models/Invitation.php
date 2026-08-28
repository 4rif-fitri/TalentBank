<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /** @use HasFactory<\Database\Factories\InvitationFactory> */
    use HasFactory;

    protected $fillable = [
        'sender_profile_id',
        'receiver_profile_id',
        'invitation_message',
        'invitation_status',
        'created_at',
        'updated_at',
        'expires_at',
        'position_id',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function sender()
    {
        return $this->belongsTo(UserProfile::class, 'sender_profile_id');
    }

    public function receiver()
    {
        return $this->belongsTo(UserProfile::class, 'receiver_profile_id');
    }
}
