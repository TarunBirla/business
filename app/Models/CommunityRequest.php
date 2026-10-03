<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'community_name',
        'image',
        'description',
        'purpose',
        'why_join',
        'community_type',
        'city',
        'country',
        'status',
        'rejection_reason',
        'created_group_id',
    ];

    public function createdGroup()
    {
        return $this->belongsTo(Group::class, 'created_group_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        $path = ltrim($this->image, '/');
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        return asset('storage/' . $path);
    }
}
