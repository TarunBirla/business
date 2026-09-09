<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'event_id',
        'subscription_id',
        'amount',
        'currency',
        'provider',
        'transaction_id',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function subscription()
    {
        return $this->belongsTo(GroupSubscription::class, 'subscription_id');
    }
}
