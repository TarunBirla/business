<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function usersOffering()
    {
        return $this->belongsToMany(User::class, 'user_services')->wherePivot('type', 'offer');
    }

    public function usersNeeding()
    {
        return $this->belongsToMany(User::class, 'user_services')->wherePivot('type', 'need');
    }
}
