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
        'gallery_images',
        'thumbnail_image',
        'description',
        'promotional_message',
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
        'theme_id',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    protected $casts = [
        'who_can_join' => 'array',
        'benefits' => 'array',
        'gallery_images' => 'array',
    ];

    public function formatImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        return asset('storage/' . $path);
    }

    public function getThumbnailImageUrlAttribute(): ?string
    {
        if ($this->thumbnail_image) {
            return $this->formatImageUrl($this->thumbnail_image);
        }
        if (!empty($this->gallery_images) && is_array($this->gallery_images) && count($this->gallery_images) > 0) {
            return $this->formatImageUrl($this->gallery_images[0]);
        }
        return $this->formatImageUrl($this->cover_image);
    }

    public function getGalleryImageUrlsAttribute(): array
    {
        if (empty($this->gallery_images) || !is_array($this->gallery_images)) {
            return [];
        }
        $urls = [];
        foreach ($this->gallery_images as $path) {
            $url = $this->formatImageUrl($path);
            if ($url) {
                $urls[] = $url;
            }
        }
        return $urls;
    }

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

    public function pendingMembers()
    {
        return $this->members()->wherePivot('status', 'pending');
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

    public function auditLogs()
    {
        return $this->hasMany(CommunityAuditLog::class)->orderBy('created_at', 'desc');
    }

    public function getJoinUrlAttribute(): string
    {
        return url('/join/' . $this->slug);
    }
}
