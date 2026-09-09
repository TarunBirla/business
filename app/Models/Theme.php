<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_default',
        'is_active',
        'colors',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'colors' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($theme) {
            if (empty($theme->slug)) {
                $theme->slug = Str::slug($theme->name);
            }
        });
    }

    public function toCssVariables(): string
    {
        $colors = $this->colors ?? [];
        $variables = [];
        foreach ($colors as $key => $value) {
            $varName = '--' . str_replace('_', '-', $key);
            $variables[] = "    {$varName}: {$value};";
        }
        return implode("\n", $variables);
    }

    public static function getDefaultTheme()
    {
        return static::where('is_default', true)->where('is_active', true)->first()
            ?? static::where('is_active', true)->first();
    }
}
