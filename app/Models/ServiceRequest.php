<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'requester_id',
        'provider_id',
        'status',
        'message',
    ];

    public function service()
    {
        return $this->belongsTo(CommunityService::class, 'service_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
