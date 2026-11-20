<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    protected $table = 'user_activities';

    protected $fillable = [
        'user_id',
        'group_id',
        'activity_type',
        'description',
        'path',
        'url',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get icon representation for activity type.
     */
    public function getIconAttribute(): string
    {
        return match ($this->activity_type) {
            'login' => 'fa-solid fa-right-to-bracket text-emerald-600',
            'logout' => 'fa-solid fa-right-from-bracket text-rose-500',
            'page_view' => 'fa-regular fa-compass text-sky-600',
            'connection' => 'fa-solid fa-user-plus text-indigo-600',
            'survey' => 'fa-solid fa-clipboard-question text-amber-600',
            'event' => 'fa-regular fa-calendar-check text-purple-600',
            default => 'fa-solid fa-circle-dot text-slate-500',
        };
    }

    /**
     * Get badge color styling.
     */
    public function getBadgeClassAttribute(): string
    {
        return match ($this->activity_type) {
            'login' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'logout' => 'bg-rose-50 text-rose-700 border-rose-200',
            'page_view' => 'bg-sky-50 text-sky-700 border-sky-200',
            'connection' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'survey' => 'bg-amber-50 text-amber-700 border-amber-200',
            'event' => 'bg-purple-50 text-purple-700 border-purple-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
