<?php

namespace App\Models;

use App\Models\Concerns\HasCommonId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasCommonId, SoftDeletes;
    protected $fillable = ['name','slug','tagline','summary','description','category',
        'image','features','modules','is_featured','is_active','sort_order'];

    protected $casts = ['features' => 'array', 'modules' => 'array',
        'is_featured' => 'boolean', 'is_active' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('sort_order'); }
}
