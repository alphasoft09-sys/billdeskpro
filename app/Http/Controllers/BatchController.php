<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BatchController extends Controller
{
    /**
     * Display batch management page
     */
    public function index()
    {
        return view('inventory.batches');
    }

    /**
     * Get all batches (AJAX)
     */
    public function getBatches(Request $request)
    {
        try {
            $batches = Batch::with(['product', 'supplier'])
                ->orderBy('received_date', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $batches
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get batches for a specific product (AJAX)
     */
    public function getBatchesForProduct(Request $request, $productId)
    {
        if ($request->ajax()) {
            $batches = Batch::with(['product', 'supplier'])
                ->where('product_id', $productId)
                ->active()
                ->inStock()
                ->orderBy('received_date', 'asc') // FIFO order
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $batches
            ]);
        }
    }

    /**
     * Get a single batch (AJAX)
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $batch = Batch::with(['product', 'supplier'])
                    ->findOrFail($id);
                
                return response()->json([
                    'success' => true,
                    'data' => $batch
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => 'Batch not found'
                ], 404);
            }
        }
    }

    /**
     * Store a new batch (AJAX)
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'batch_number' => 'required|string|max:255|unique:batches,batch_number',
                'supplier_batch_number' => 'nullable|string|max:255',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'quantity_received' => 'required|integer|min:1',
                'production_date' => 'nullable|date',
                'expiry_date' => 'nullable|date|after_or_equal:production_date',
                'received_date' => 'required|date',
                'notes' => 'nullable|string',
                'purchase_unit_id' => 'nullable|exists:units,id',
                'purchase_quantity' => 'nullable|numeric|min:0',
                'sale_unit_id' => 'nullable|exists:units,id',
                'min_selling_price' => 'nullable|numeric|min:0',
                'max_selling_price' => 'nullable|numeric|min:0',
                'conversion_factor' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $batchData = $request->all();
            $batchData['quantity_remaining'] = $request->quantity_received;
            
            // Calculate cost per sale unit if bulk purchase data is provided
            if ($request->purchase_price && $request->purchase_quantity && $request->conversion_factor) {
                $batchData['cost_per_sale_unit'] = $request->purchase_price / ($request->purchase_quantity * $request->conversion_factor);
            }
            
            // Calculate available sale quantity
            if ($request->conversion_factor) {
                $batchData['available_sale_quantity'] = $request->quantity_received * $request->conversion_factor;
            }

            $batch = Batch::create($batchData);
            $batch->load(['product', 'supplier', 'purchaseUnit', 'saleUnit']);

            return response()->json([
                'success' => true,
                'message' => 'Batch created successfully',
                'data' => $batch
            ]);
        }
    }

    /**
     * Update batch (AJAX)
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            // Debug: Log the incoming request data
            \Log::info('Batch Update Request', [
                'id' => $id,
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            $batch = Batch::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'batch_number' => 'required|string|max:255',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'supplier_batch_number' => 'nullable|string|max:255',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'quantity_received' => 'required|integer|min:1',
                'production_date' => 'nullable|date',
                'expiry_date' => 'nullable|date|after_or_equal:production_date',
                'received_date' => 'nullable|date',
                'notes' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                \Log::error('Batch Update Validation Failed', [
                    'id' => $id,
                    'errors' => $validator->errors()->toArray(),
                    'data' => $request->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            try {
                $batch->update($request->all());
                $batch->load(['product', 'supplier']);

                \Log::info('Batch Updated Successfully', [
                    'id' => $id,
                    'batch' => $batch->toArray()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Batch updated successfully',
                    'data' => $batch
                ]);
            } catch (\Exception $e) {
                \Log::error('Batch Update Error', [
                    'id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating batch: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    /**
     * Delete batch (AJAX)
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            // Debug: Log the delete request
            \Log::info('Batch Delete Request', [
                'id' => $id,
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            try {
                $batch = Batch::findOrFail($id);
                
                \Log::info('Batch found for deletion', [
                    'batch_id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                    'quantity_remaining' => $batch->quantity_remaining
                ]);
                
                // Check if batch has remaining stock
                if ($batch->quantity_remaining > 0) {
                    \Log::warning('Cannot delete batch with remaining stock', [
                        'batch_id' => $batch->id,
                        'quantity_remaining' => $batch->quantity_remaining
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete batch with remaining stock. Please adjust stock first.'
                    ], 422);
                }

                $batch->delete();
                
                \Log::info('Batch deleted successfully', [
                    'batch_id' => $id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Batch deleted successfully'
                ]);
            } catch (\Exception $e) {
                \Log::error('Batch Delete Error', [
                    'id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting batch: ' . $e->getMessage()
                ], 500);
            }
        }
    }

    /**
     * Adjust batch stock (AJAX)
     */
    public function adjustStock(Request $request, $id)
    {
        if ($request->ajax()) {
            $batch = Batch::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'adjustment_type' => 'required|in:add,remove',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->adjustment_type === 'add') {
                $batch->addStock($request->quantity);
                $message = "Added {$request->quantity} units to batch {$batch->batch_number}";
            } else {
                if ($request->quantity > $batch->quantity_remaining) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot remove more stock than available'
                    ], 422);
                }
                $batch->reduceStock($request->quantity);
                $message = "Removed {$request->quantity} units from batch {$batch->batch_number}";
            }

            $batch->load(['product', 'supplier']);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $batch
            ]);
        }
    }

    /**
     * Get batch statistics (AJAX)
     */
    public function getBatchStats(Request $request)
    {
        if ($request->ajax()) {
            $stats = [
                'total_batches' => Batch::count(),
                'active_batches' => Batch::where('is_active', true)->count(),
                'expired_batches' => Batch::where('expiry_date', '<', now())->count(),
                'low_stock_batches' => Batch::where('quantity_remaining', '<=', 10)->where('quantity_remaining', '>', 0)->count(),
                'out_of_stock_batches' => Batch::where('quantity_remaining', '<=', 0)->count(),
                'products_with_batches' => Batch::distinct('product_id')->count(),
                'active_suppliers' => Batch::whereNotNull('supplier_id')->distinct('supplier_id')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }
    }
}