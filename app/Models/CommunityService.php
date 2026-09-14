<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id',
        'title',
        'description',
        'category',
        'price_type',
        'price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function requests()
    {
        return $this->hasMany(ServiceRequest::class, 'service_id');
    }
}
