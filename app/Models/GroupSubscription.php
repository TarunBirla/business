<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'name',
        'price',
        'currency',
        'duration',
        'duration_type',
        'status',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
