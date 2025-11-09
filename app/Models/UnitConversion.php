<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'from_unit_id',
        'to_unit_id',
        'conversion_factor',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'conversion_factor' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the product that owns this conversion
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the batch that owns this conversion
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the unit this converts from
     */
    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    /**
     * Get the unit this converts to
     */
    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    /**
     * Scope for active conversions only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Convert quantity from one unit to another
     */
    public function convertQuantity($quantity)
    {
        return $quantity * $this->conversion_factor;
    }

    /**
     * Reverse convert quantity (from sale unit to purchase unit)
     */
    public function reverseConvertQuantity($quantity)
    {
        return $quantity / $this->conversion_factor;
    }
}