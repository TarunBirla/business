<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'session_id',
        'user_id',
        'url',
        'path',
        'method',
        'referrer',
        'user_agent',
        'browser',
        'device',
        'os',
        'country',
        'country_code',
        'city',
        'region',
        'is_unique_daily',
    ];

    protected $casts = [
        'is_unique_daily' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
