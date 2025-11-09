<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $suppliers = Supplier::withCount('batches as products_count')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $suppliers
            ]);
        }
        
        return view('suppliers.index');
    }

    /**
     * Store a newly created supplier
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:suppliers,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'gst_number' => 'nullable|string|max:20|unique:suppliers,gst_number',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $supplier = Supplier::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Supplier created successfully',
                'data' => $supplier
            ]);
        }
    }

    /**
     * Update the specified supplier
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $supplier = Supplier::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:suppliers,email,' . $id,
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'gst_number' => 'nullable|string|max:20|unique:suppliers,gst_number,' . $id,
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $supplier->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Supplier updated successfully',
                'data' => $supplier
            ]);
        }
    }

    /**
     * Remove the specified supplier
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $supplier = Supplier::findOrFail($id);
            
            // Check if supplier has any batches/products
            if ($supplier->batches()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete supplier with existing products/batches. Please remove all associated products first.'
                ], 422);
            }

            $supplier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully'
            ]);
        }
    }

    /**
     * Get all suppliers for dropdowns
     */
    public function getAll(Request $request)
    {
        if ($request->ajax()) {
            $suppliers = Supplier::where('is_active', true)
                ->orderBy('name', 'asc')
                ->get(['id', 'name', 'contact_person', 'phone', 'email']);
            
            return response()->json([
                'success' => true,
                'data' => $suppliers
            ]);
        }
    }
}
