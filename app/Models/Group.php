<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'cover_image',
        'description',
        'purpose',
        'why_join',
        'who_can_join',
        'benefits',
        'rules',
        'community_type',
        'country',
        'state',
        'city',
        'status',
    ];

    protected $casts = [
        'who_can_join' => 'array',
        'benefits' => 'array',
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('membership_role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function groupAdmins()
    {
        return $this->members()->wherePivot('membership_role', 'group_admin');
    }

    public function activeMembers()
    {
        return $this->members()->wherePivot('status', 'active');
    }

    public function subscriptions()
    {
        return $this->hasMany(GroupSubscription::class);
    }

    public function activeSubscriptionPlan()
    {
        return $this->hasOne(GroupSubscription::class)->where('status', 'active')->latestOfMany();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function upcomingEvents()
    {
        return $this->events()->where('start_at', '>=', now())->where('status', 'published')->orderBy('start_at', 'asc');
    }

    public function notices()
    {
        return $this->hasMany(Notice::class)->orderBy('created_at', 'desc');
    }

    public function activeNotices()
    {
        return $this->notices()->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function getJoinUrlAttribute(): string
    {
        return url('/join/' . $this->slug);
    }
}
