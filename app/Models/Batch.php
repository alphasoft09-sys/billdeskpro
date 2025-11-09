<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'batch_number',
        'supplier_batch_number',
        'purchase_price',
        'selling_price',
        'quantity_received',
        'quantity_remaining',
        'production_date',
        'expiry_date',
        'received_date',
        'notes',
        'is_active',
        'purchase_unit_id',
        'purchase_quantity',
        'cost_per_sale_unit',
        'sale_unit_id',
        'available_sale_quantity',
        'min_selling_price',
        'max_selling_price',
        'conversion_factor',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity_received' => 'integer',
        'quantity_remaining' => 'integer',
        'production_date' => 'date',
        'expiry_date' => 'date',
        'received_date' => 'date',
        'is_active' => 'boolean',
        'purchase_quantity' => 'decimal:2',
        'cost_per_sale_unit' => 'decimal:2',
        'available_sale_quantity' => 'decimal:2',
        'min_selling_price' => 'decimal:2',
        'max_selling_price' => 'decimal:2',
        'conversion_factor' => 'decimal:4',
    ];

    /**
     * Get the product that owns this batch
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the supplier for this batch
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the purchase unit for this batch
     */
    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    /**
     * Get the sale unit for this batch
     */
    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    /**
     * Scope for active batches only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for batches with remaining stock
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity_remaining', '>', 0);
    }

    /**
     * Scope for batches by product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Check if batch has stock
     */
    public function hasStock()
    {
        return $this->quantity_remaining > 0;
    }

    /**
     * Check if batch is expired
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    /**
     * Get profit margin for this batch
     */
    public function getProfitMargin()
    {
        if ($this->purchase_price > 0) {
            return (($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100;
        }
        return 0;
    }

    /**
     * Get total value of remaining stock
     */
    public function getRemainingStockValue()
    {
        return $this->quantity_remaining * $this->purchase_price;
    }

    /**
     * Calculate cost per sale unit from bulk purchase
     */
    public function calculateCostPerSaleUnit()
    {
        if ($this->purchase_price && $this->purchase_quantity && $this->conversion_factor) {
            return $this->purchase_price / ($this->purchase_quantity * $this->conversion_factor);
        }
        return 0;
    }

    /**
     * Calculate available sale quantity from bulk purchase
     */
    public function calculateAvailableSaleQuantity()
    {
        if ($this->quantity_remaining && $this->conversion_factor) {
            return $this->quantity_remaining * $this->conversion_factor;
        }
        return $this->quantity_remaining;
    }

    /**
     * Calculate profit margin for a sale
     */
    public function calculateProfitMargin($sellingPrice)
    {
        $costPerUnit = $this->cost_per_sale_unit ?: $this->calculateCostPerSaleUnit();
        if ($costPerUnit > 0) {
            return (($sellingPrice - $costPerUnit) / $costPerUnit) * 100;
        }
        return 0;
    }

    /**
     * Calculate profit amount for a sale
     */
    public function calculateProfitAmount($sellingPrice, $quantity)
    {
        $costPerUnit = $this->cost_per_sale_unit ?: $this->calculateCostPerSaleUnit();
        return ($sellingPrice - $costPerUnit) * $quantity;
    }

    /**
     * Reduce stock quantity
     */
    public function reduceStock($quantity)
    {
        if ($quantity <= $this->quantity_remaining) {
            $this->quantity_remaining -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Add stock quantity
     */
    public function addStock($quantity)
    {
        $this->quantity_remaining += $quantity;
        $this->quantity_received += $quantity;
        $this->save();
    }
}