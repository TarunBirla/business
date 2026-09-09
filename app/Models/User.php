<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'profile_photo',
        'profession',
        'company',
        'job_title',
        'country',
        'state',
        'city',
        'description',
        'website',
        'linkedin',
        'privacy_settings',
        'global_role',
        'status',
        'theme_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'privacy_settings' => 'array',
        'password' => 'hashed',
    ];

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isSuperAdmin(): bool
    {
        return $this->global_role === 'super_admin';
    }

    public function isGroupAdmin(int $groupId = null): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($groupId) {
            return $this->groups()
                ->where('group_id', $groupId)
                ->wherePivot('membership_role', 'group_admin')
                ->exists();
        }

        return $this->groups()
            ->wherePivot('membership_role', 'group_admin')
            ->exists();
    }

    public function isMemberOf(int $groupId): bool
    {
        return $this->groups()->where('group_id', $groupId)->exists();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')
            ->withPivot('membership_role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscriptionFor(int $groupId)
    {
        return $this->subscriptions()
            ->where('group_id', $groupId)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>', now());
            })->first();
    }

    public function servicesOffered()
    {
        return $this->belongsToMany(Service::class, 'user_services')->wherePivot('type', 'offer')->withTimestamps();
    }

    public function servicesNeeded()
    {
        return $this->belongsToMany(Service::class, 'user_services')->wherePivot('type', 'need')->withTimestamps();
    }

    public function sentConnections()
    {
        return $this->hasMany(Connection::class, 'sender_id');
    }

    public function receivedConnections()
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function unreadMessagesCountInGroup(int $groupId): int
    {
        return $this->receivedMessages()
            ->where('group_id', $groupId)
            ->where('is_read', false)
            ->count();
    }

    public function getProfileCompletionPercentageAttribute(): int
    {
        $fields = ['first_name', 'last_name', 'email', 'phone', 'profession', 'company', 'city', 'description', 'linkedin', 'profile_photo'];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filled++;
            }
        }
        $percentage = (int) round(($filled / count($fields)) * 100);
        return min($percentage, 100);
    }
}
