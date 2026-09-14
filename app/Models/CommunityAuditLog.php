<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'field_name',
        'old_value',
        'new_value',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
