<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get products that use this unit
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }

    /**
     * Scope for active units only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}