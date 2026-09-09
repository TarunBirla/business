<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'created_by',
        'title',
        'slug',
        'description',
        'banner',
        'event_type',
        'venue',
        'address',
        'city',
        'country',
        'meeting_url',
        'start_at',
        'end_at',
        'capacity',
        'price',
        'currency',
        'registration_deadline',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_registrations')->withPivot('amount', 'payment_status', 'registration_status', 'registered_at');
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->capacity - $this->registrations()->where('registration_status', 'confirmed')->count());
    }
}
