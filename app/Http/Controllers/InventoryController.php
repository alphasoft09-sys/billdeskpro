<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\UnitConversion;
use App\Models\Batch;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    /**
     * Display inventory management page
     */
    public function index()
    {
        return view('inventory.index');
    }

    /**
     * Get all products (AJAX)
     */
    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category', 'unit', 'primaryUnitConversion.fromUnit', 'primaryUnitConversion.toUnit', 'batches'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Add batch information to each product
            $products->each(function ($product) {
                $product->total_batch_stock = $product->getTotalStock();
                $product->average_selling_price = $product->getAverageSellingPrice();
                $product->average_purchase_price = $product->getAveragePurchasePrice();
                $product->selling_price_range = [
                    'min' => $product->getLowestSellingPrice(),
                    'max' => $product->getHighestSellingPrice()
                ];
                $product->purchase_price_range = [
                    'min' => $product->getLowestPurchasePrice(),
                    'max' => $product->getHighestPurchasePrice()
                ];
                $product->batch_count = $product->batches()->active()->count();
                $product->average_profit_margin = $product->getAverageProfitMarginPercentage();
            });
            
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }
    }

    /**
     * Get single product details (AJAX)
     */
    public function getProduct(Request $request, $id)
    {
        if ($request->ajax()) {
            $product = Product::with(['category', 'unit', 'batches.supplier', 'unitConversions.fromUnit', 'unitConversions.toUnit'])
                ->findOrFail($id);
            
            // Add batch information
            $product->total_batch_stock = $product->getTotalStock();
            $product->average_selling_price = $product->getAverageSellingPrice();
            $product->average_purchase_price = $product->getAveragePurchasePrice();
            $product->selling_price_range = [
                'min' => $product->getLowestSellingPrice(),
                'max' => $product->getHighestSellingPrice()
            ];
            $product->purchase_price_range = [
                'min' => $product->getLowestPurchasePrice(),
                'max' => $product->getHighestPurchasePrice()
            ];
            $product->batch_count = $product->batches()->active()->count();
            $product->average_profit_margin = $product->getAverageProfitMarginPercentage();
            
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        }
    }

    /**
     * Get all categories (AJAX)
     */
    public function getCategories(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        }
    }

    /**
     * Get all units (AJAX)
     */
    public function getUnits(Request $request)
    {
        if ($request->ajax()) {
            $units = Unit::orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        }
    }

    /**
     * Store a new unit (AJAX)
     */
    public function storeUnit(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'symbol' => 'required|string|max:50',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $unit = Unit::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Unit created successfully',
                'data' => $unit
            ]);
        }
    }

    /**
     * Update unit (AJAX)
     */
    public function updateUnit(Request $request, $id)
    {
        if ($request->ajax()) {
            $unit = Unit::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'symbol' => 'required|string|max:50',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $unit->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Unit updated successfully',
                'data' => $unit
            ]);
        }
    }

    /**
     * Delete unit (AJAX)
     */
    public function deleteUnit(Request $request, $id)
    {
        if ($request->ajax()) {
            $unit = Unit::findOrFail($id);
            $unit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Unit deleted successfully'
            ]);
        }
    }

    /**
     * Get unit conversions for a product (AJAX)
     */
    public function getUnitConversions(Request $request, $productId)
    {
        if ($request->ajax()) {
            $conversions = UnitConversion::with(['product', 'fromUnit', 'toUnit'])
                ->where('product_id', $productId)
                ->active()
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $conversions
            ]);
        }
    }

    /**
     * Store unit conversion (AJAX)
     */
    public function storeUnitConversion(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'from_unit_id' => 'required|exists:units,id',
                'to_unit_id' => 'required|exists:units,id',
                'conversion_factor' => 'required|numeric|min:0.0001',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if conversion already exists for the same product and units
            $existing = UnitConversion::where('product_id', $request->product_id)
                ->where('from_unit_id', $request->from_unit_id)
                ->where('to_unit_id', $request->to_unit_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit conversion already exists for this product and unit combination.'
                ], 422);
            }

            $conversion = UnitConversion::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Unit conversion created successfully',
                'data' => $conversion->load(['product', 'fromUnit', 'toUnit'])
            ]);
        }
    }

    /**
     * Update unit conversion (AJAX)
     */
    public function updateUnitConversion(Request $request, $id)
    {
        if ($request->ajax()) {
            $conversion = UnitConversion::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'conversion_factor' => 'required|numeric|min:0.0001',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $conversion->update($request->only(['conversion_factor', 'is_active']));

            return response()->json([
                'success' => true,
                'message' => 'Unit conversion updated successfully',
                'data' => $conversion->load(['product', 'fromUnit', 'toUnit'])
            ]);
        }
    }

    /**
     * Delete unit conversion (AJAX)
     */
    public function deleteUnitConversion(Request $request, $id)
    {
        if ($request->ajax()) {
            $conversion = UnitConversion::findOrFail($id);
            $conversion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Unit conversion deleted successfully'
            ]);
        }
    }

    /**
     * Store a new product (AJAX)
     */
    public function storeProduct(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:255|unique:products',
                'hsc_code' => 'nullable|string|max:255',
                'puc_code' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:0',
                'unit_id' => 'required|exists:units,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load(['category', 'unit'])
            ]);
        }
    }

    /**
     * Update product (AJAX)
     */
    public function updateProduct(Request $request, $id)
    {
        if ($request->ajax()) {
            $product = Product::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:255|unique:products,sku,' . $id,
                'hsc_code' => 'nullable|string|max:255',
                'puc_code' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:0',
                'unit_id' => 'required|exists:units,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $product->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load(['category', 'unit'])
            ]);
        }
    }

    /**
     * Delete product (AJAX)
     */
    public function deleteProduct(Request $request, $id)
    {
        if ($request->ajax()) {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        }
    }

    /**
     * Store a new category (AJAX)
     */
    public function storeCategory(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $category = Category::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ]);
        }
    }

    /**
     * Update category (AJAX)
     */
    public function updateCategory(Request $request, $id)
    {
        if ($request->ajax()) {
            $category = Category::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $category->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ]);
        }
    }

    /**
     * Delete category (AJAX)
     */
    public function deleteCategory(Request $request, $id)
    {
        if ($request->ajax()) {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        }
    }

    /**
     * Get all suppliers (AJAX)
     */
    public function getSuppliers(Request $request)
    {
        if ($request->ajax()) {
            $suppliers = Supplier::orderBy('name', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $suppliers
            ]);
        }
    }
}