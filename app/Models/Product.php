<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'hsc_code',
        'puc_code',
        'description',
        'category_id',
        'stock_quantity',
        'min_stock_level',
        'unit_id',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'min_stock_level' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get unit conversions for this product
     */
    public function unitConversions()
    {
        return $this->hasMany(UnitConversion::class);
    }

    /**
     * Get the primary unit conversion (purchase to sale unit)
     */
    public function primaryUnitConversion()
    {
        return $this->hasOne(UnitConversion::class)->active();
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get all batches for this product
     */
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Get active batches for this product
     */
    public function activeBatches()
    {
        return $this->hasMany(Batch::class)->active();
    }

    /**
     * Get batches with remaining stock
     */
    public function batchesInStock()
    {
        return $this->hasMany(Batch::class)->active()->inStock();
    }

    /**
     * Get total stock across all batches
     */
    public function getTotalStock()
    {
        return $this->batches()->active()->sum('quantity_remaining');
    }

    /**
     * Get average selling price across all batches
     */
    public function getAverageSellingPrice()
    {
        $batches = $this->batches()->active()->inStock()->get();
        if ($batches->count() > 0) {
            return $batches->avg('selling_price');
        }
        return 0; // No batches available
    }

    /**
     * Get lowest selling price across all batches
     */
    public function getLowestSellingPrice()
    {
        $batches = $this->batches()->active()->inStock()->get();
        if ($batches->count() > 0) {
            return $batches->min('selling_price');
        }
        return 0; // No batches available
    }

    /**
     * Get highest selling price across all batches
     */
    public function getHighestSellingPrice()
    {
        $batches = $this->batches()->active()->inStock()->get();
        if ($batches->count() > 0) {
            return $batches->max('selling_price');
        }
        return 0; // No batches available
    }

    /**
     * Get average purchase price across all batches
     */
    public function getAveragePurchasePrice()
    {
        $batches = $this->batches()->active()->inStock()->get();
        if ($batches->count() > 0) {
            return $batches->avg('purchase_price');
        }
        return 0; // No batches available
    }

    /**
     * Get lowest purchase price across all batches
     */
    public function getLowestPurchasePrice()
    {
        $batches = $this->batches()->active()->inStock()->get();
        if ($batches->count() > 0) {
            return $batches->min('purchase_price');
        }
        return 0; // No batches available
    }

    /**
     * Get highest purchase price across all batches
     */
    public function getHighestPurchasePrice()
    {
        $batches = $this->batches()->active()->inStock()->get();
        if ($batches->count() > 0) {
            return $batches->max('purchase_price');
        }
        return 0; // No batches available
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }

    /**
     * Get profit margin percentage for a specific batch
     */
    public function getBatchProfitMarginPercentage($batch)
    {
        if ($batch->purchase_price > 0) {
            return (($batch->selling_price - $batch->purchase_price) / $batch->purchase_price) * 100;
        }
        return 0;
    }

    /**
     * Get average profit margin across all batches
     */
    public function getAverageProfitMarginPercentage()
    {
        $batches = $this->batches()->active()->inStock()->get();
        if ($batches->count() > 0) {
            $totalMargin = $batches->sum(function ($batch) {
                return $this->getBatchProfitMarginPercentage($batch);
            });
            return $totalMargin / $batches->count();
        }
        return 0;
    }
}