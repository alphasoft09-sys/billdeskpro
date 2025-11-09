@extends('layouts.app')

@section('title', 'Inventory Management - BillDesk Pro')
@section('page-title', 'Inventory Management')

@section('content')
<!-- <div class="page-header">
    <h1 class="page-title">Inventory Management</h1>
    <p class="page-subtitle">Track products, categories, stock levels, and low stock alerts</p>
</div> -->

<div id="alert-container"></div>


<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number" id="total-products-count">0</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="total-categories-count">0</div>
        <div class="stat-label">Total Categories</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="low-stock-count">0</div>
        <div class="stat-label">Low Stock Items</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="out-of-stock-count">0</div>
        <div class="stat-label">Out of Stock</div>
    </div>
    <div class="stat-card">
    <div class="stat-number" id="total-units-count">0</div>
    <div class="stat-label">Total Units</div>
</div>
<div class="stat-card">
    <!-- <div class="stat-icon">📦</div> -->
    <div class="stat-number" id="total-batches-count">0</div>
    <div class="stat-label">Total Batches</div>
</div>
<div class="stat-card">
    <!-- <div class="stat-icon">⚠️</div> -->
    <div class="stat-number" id="expired-batches-count">0</div>
    <div class="stat-label">Expired Batches</div>
</div>
</div>

<div class="tabs">
    <div class="tabs-left">
        <button class="tab active" onclick="switchTab('products')">Products</button>
        <button class="tab" onclick="switchTab('categories')">Categories</button>
        <button class="tab" onclick="switchTab('units')">Units</button>
        <button class="tab" onclick="switchTab('conversions')">Unit Conversions</button>
    </div>
    <div class="tabs-right">
        <button class="btn btn-primary btn-sm" onclick="openAddProductModal()">
            <span>📦</span> Add Product
        </button>
        <button class="btn btn-primary btn-sm" onclick="openAddCategoryModal()">
            <span>📁</span> Add Category
        </button>
        <button class="btn btn-primary btn-sm" onclick="openAddUnitModal()">
            <span>📏</span> Add Unit
        </button>
    </div>
</div>

<!-- Products Tab -->
<div id="products-tab" class="tab-content active">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Products List</h3>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" id="product-search" placeholder="Search products..." class="form-input" style="width: 200px;" onkeyup="searchProducts()">
                <button class="btn btn-secondary" onclick="clearProductSearch()">Clear</button>
            </div>
        </div>
        <div class="table-container">
            <div id="products-container">
                <div class="loading">Loading products...</div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Tab -->
<div id="categories-tab" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Categories List</h3>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" id="category-search" placeholder="Search categories..." class="form-input" style="width: 200px;" onkeyup="searchCategories()">
                <button class="btn btn-secondary" onclick="clearCategorySearch()">Clear</button>
            </div>
        </div>
        <div class="table-container">
            <div id="categories-container">
                <div class="loading">Loading categories...</div>
            </div>
        </div>
    </div>
</div>

<!-- Units Tab -->
<div id="units-tab" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Units List</h3>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" id="unit-search" placeholder="Search units..." class="form-input" style="width: 200px;" onkeyup="searchUnits()">
                <button class="btn btn-secondary" onclick="clearUnitSearch()">Clear</button>
            </div>
        </div>
        <div class="table-container">
            <div id="units-container">
                <div class="loading">Loading units...</div>
            </div>
        </div>
    </div>
</div>

<!-- Unit Conversions Tab -->
<div id="conversions-tab" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Unit Conversions</h3>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="text" id="conversion-search" placeholder="Search conversions..." class="form-input" style="width: 200px;" onkeyup="searchConversions()">
                <select id="conversion-product-filter" class="form-input" style="width: 200px;" onchange="filterConversionsByProduct()">
                    <option value="">All Products</option>
                </select>
                <button class="btn btn-secondary" onclick="clearConversionSearch()">Clear</button>
                <button class="btn btn-primary" onclick="openAddConversionModal()">
                    <span>🔄</span>
                    Add Conversion
                </button>
            </div>
        </div>
        <div class="table-container">
            <div id="conversions-container">
                <div class="loading">Loading conversions...</div>
            </div>
        </div>
    </div>
</div>


<!-- Add Product Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content" style="max-width: 1000px; width: 95%; max-height: 85vh; overflow-y: auto;">
        <div class="modal-header">
            <h3>Add New Product</h3>
            <span class="close" onclick="closeAddProductModal()">&times;</span>
        </div>
        <form id="product-form" style="padding: 1rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="product-name">Product Name</label>
                    <input type="text" id="product-name" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="product-sku">SKU</label>
                    <input type="text" id="product-sku" name="sku" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="product-category">Category</label>
                    <select id="product-category" name="category_id" class="form-input" required>
                        <option value="">Select Category</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="product-hsc-code">HSC Code</label>
                    <input type="text" id="product-hsc-code" name="hsc_code" class="form-input" placeholder="Enter HSC code">
                </div>
                <div class="form-group">
                    <label class="form-label" for="product-puc-code">PUC Code</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" id="product-puc-code" name="puc_code" class="form-input" placeholder="Enter PUC code or scan barcode">
                        <button type="button" class="btn btn-secondary" onclick="openBarcodeScanner('product-puc-code')" style="white-space: nowrap;">
                            📷 Scan
                        </button>
                    </div>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="product-unit">Unit</label>
                    <select id="product-unit" name="unit_id" class="form-input" required>
                        <option value="">Select Unit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="product-stock">Initial Stock Quantity</label>
                    <input type="number" id="product-stock" name="stock_quantity" class="form-input" min="0" required>
                    <small style="color: #666; font-size: 0.75rem;">Stock will be managed through batches</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="product-min-stock">Minimum Stock Level</label>
                    <input type="number" id="product-min-stock" name="min_stock_level" class="form-input" min="0" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="product-description">Description</label>
                <textarea id="product-description" name="description" class="form-input" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem; margin-bottom: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeAddProductModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Category</h3>
            <span class="close" onclick="closeAddCategoryModal()">&times;</span>
        </div>
        <form id="category-form">
            <div class="form-group">
                <label class="form-label" for="category-name">Category Name</label>
                <input type="text" id="category-name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="category-description">Description</label>
                <textarea id="category-description" name="description" class="form-input" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeAddCategoryModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Unit Modal -->
<div id="addUnitModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Unit</h3>
            <span class="close" onclick="closeAddUnitModal()">&times;</span>
        </div>
        <form id="unit-form">
            <div class="form-group">
                <label class="form-label" for="unit-name">Unit Name</label>
                <input type="text" id="unit-name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="unit-symbol">Symbol</label>
                <input type="text" id="unit-symbol" name="symbol" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="unit-description">Description</label>
                <textarea id="unit-description" name="description" class="form-input" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeAddUnitModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Unit</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Unit Conversion Modal -->
<div id="addConversionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Unit Conversion</h3>
            <span class="close" onclick="closeAddConversionModal()">&times;</span>
        </div>
        <form id="conversion-form">
            <div class="form-group">
                <label class="form-label" for="conversion-product">Product</label>
                <select id="conversion-product" name="product_id" class="form-input" required>
                    <option value="">Select Product</option>
                </select>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="conversion-from-unit">Purchase Unit (From)</label>
                    <select id="conversion-from-unit" name="from_unit_id" class="form-input" required>
                        <option value="">Select Purchase Unit</option>
                    </select>
                    <small style="color: #666; font-size: 0.75rem;">Auto-selected from product's current unit</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="conversion-to-unit">Sale Unit (To)</label>
                    <select id="conversion-to-unit" name="to_unit_id" class="form-input" required>
                        <option value="">Select Sale Unit</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="conversion-factor">Conversion Factor</label>
                <input type="number" id="conversion-factor" name="conversion_factor" class="form-input" step="0.0001" min="0.0001" required>
                <small style="color: #666; font-size: 0.75rem;">How many sale units are in 1 purchase unit (e.g., 100 pieces in 1 box)</small>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeAddConversionModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Conversion</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Unit Conversion Modal -->
<div id="editConversionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Unit Conversion</h3>
            <span class="close" onclick="closeEditConversionModal()">&times;</span>
        </div>
        <form id="edit-conversion-form">
            <input type="hidden" id="edit-conversion-id" name="id">
            <div class="form-group">
                <label class="form-label" for="edit-conversion-product">Product</label>
                <input type="text" id="edit-conversion-product-display" class="form-input" readonly>
                <input type="hidden" id="edit-conversion-product" name="product_id">
                <small style="color: #666; font-size: 0.75rem;">Product cannot be changed</small>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="edit-conversion-from-unit">Purchase Unit (From)</label>
                    <input type="text" id="edit-conversion-from-unit-display" class="form-input" readonly>
                    <input type="hidden" id="edit-conversion-from-unit" name="from_unit_id">
                    <small style="color: #666; font-size: 0.75rem;">Purchase unit cannot be changed</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-conversion-to-unit">Sale Unit (To)</label>
                    <input type="text" id="edit-conversion-to-unit-display" class="form-input" readonly>
                    <input type="hidden" id="edit-conversion-to-unit" name="to_unit_id">
                    <small style="color: #666; font-size: 0.75rem;">Sale unit cannot be changed</small>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-conversion-factor">Conversion Factor</label>
                <input type="number" id="edit-conversion-factor" name="conversion_factor" class="form-input" step="0.0001" min="0.0001" required>
                <small style="color: #666; font-size: 0.75rem;">How many sale units are in 1 purchase unit (e.g., 100 pieces in 1 box)</small>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-conversion-status">Status</label>
                <select id="edit-conversion-status" name="is_active" class="form-input" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditConversionModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Conversion</button>
            </div>
        </form>
    </div>
</div>




<!-- Edit Product Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-content" style="max-width: 1000px; width: 95%; max-height: 85vh; overflow-y: auto;">
        <div class="modal-header">
            <h3>Edit Product</h3>
            <span class="close" onclick="closeEditProductModal()">&times;</span>
        </div>
        <form id="edit-product-form" style="padding: 1rem;">
            <input type="hidden" id="edit-product-id" name="id">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="edit-product-name">Product Name</label>
                    <input type="text" id="edit-product-name" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-product-sku">SKU</label>
                    <input type="text" id="edit-product-sku" name="sku" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-product-category">Category</label>
                    <select id="edit-product-category" name="category_id" class="form-input" required>
                        <option value="">Select Category</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="edit-product-hsc-code">HSC Code</label>
                    <input type="text" id="edit-product-hsc-code" name="hsc_code" class="form-input" placeholder="Enter HSC code">
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-product-puc-code">PUC Code</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" id="edit-product-puc-code" name="puc_code" class="form-input" placeholder="Enter PUC code or scan barcode">
                        <button type="button" class="btn btn-secondary" onclick="openBarcodeScanner('edit-product-puc-code')" style="white-space: nowrap;">
                            📷 Scan
                        </button>
                    </div>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="edit-product-unit">Unit</label>
                    <select id="edit-product-unit" name="unit_id" class="form-input" required>
                        <option value="">Select Unit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-product-stock">Stock Quantity</label>
                    <input type="number" id="edit-product-stock" name="stock_quantity" class="form-input" min="0" required>
                    <small style="color: #666; font-size: 0.75rem;">Stock managed through batches</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-product-min-stock">Minimum Stock Level</label>
                    <input type="number" id="edit-product-min-stock" name="min_stock_level" class="form-input" min="0" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="edit-product-description">Description</label>
                <textarea id="edit-product-description" name="description" class="form-input" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem; margin-bottom: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditProductModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Category</h3>
            <span class="close" onclick="closeEditCategoryModal()">&times;</span>
        </div>
        <form id="edit-category-form">
            <input type="hidden" id="edit-category-id" name="id">
            <div class="form-group">
                <label class="form-label" for="edit-category-name">Category Name</label>
                <input type="text" id="edit-category-name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-category-description">Description</label>
                <textarea id="edit-category-description" name="description" class="form-input" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditCategoryModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<!-- View Product Modal -->
<div id="viewProductModal" class="modal">
    <div class="modal-content" style="max-width: 1000px; width: 95%; max-height: 85vh; overflow-y: auto;">
        <div class="modal-header">
            <h3>Product Details</h3>
            <span class="close" onclick="closeViewProductModal()">&times;</span>
        </div>
        <div id="product-details-content" style="padding: 1rem;">
            <div class="loading">Loading product details...</div>
        </div>
    </div>
</div>

<!-- Barcode Scanner Modal -->
<div id="barcodeScannerModal" class="modal">
    <div class="modal-content" style="max-width: 600px; width: 95%;">
        <div class="modal-header">
            <h3>Scan Barcode/QR Code</h3>
            <span class="close" onclick="closeBarcodeScanner()">&times;</span>
        </div>
        <div style="padding: 1rem;">
            <div style="margin-bottom: 1rem; text-align: center; color: #666;">
                <p>Point your camera at a barcode or QR code. Scanning automatically...</p>
                <div id="scanning-indicator" style="display: none; color: #0ea5e9; font-weight: bold;">
                    🔍 Scanning for barcode...
                </div>
            </div>
            <div id="scanner-container" style="text-align: center;">
                <video id="scanner-video" width="100%" height="300" style="border: 1px solid #ddd; border-radius: 8px;"></video>
                <canvas id="scanner-canvas" style="display: none;"></canvas>
            </div>
            <div style="margin-top: 1rem; text-align: center;">
                <button class="btn btn-primary" onclick="startScanning()">Start Camera</button>
                <button class="btn btn-success" onclick="captureBarcode()" id="capture-btn" style="display: none;">Capture Barcode</button>
                <button class="btn btn-secondary" onclick="stopScanning()">Stop Camera</button>
                <button class="btn btn-danger" onclick="closeBarcodeScanner()">Cancel</button>
            </div>
            <div id="scan-result" style="margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px; display: none;">
                <strong>Scanned Code:</strong> <span id="scanned-code"></span>
                <div style="margin-top: 0.5rem;">
                    <button class="btn btn-success" onclick="useScannedCode()">Use This Code</button>
                    <button class="btn btn-secondary" onclick="clearScanResult()">Scan Again</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tabs {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        background: white;
        border-radius: 8px;
        padding: 0.5rem;
        border: 1px solid #e5e5e5;
    }
    
    .tabs-left {
        display: flex;
        gap: 0.25rem;
    }
    
    .tabs-right {
        display: flex;
        gap: 0.5rem;
    }
    
    .tab {
        padding: 0.75rem 1rem;
        background: #f8f8f8;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    
    .tab.active {
        background: #1a1a1a;
        color: white;
    }
    
    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 6px;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background: white;
        margin: 5% auto;
        padding: 2rem;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e5e5;
    }
    
    .close {
        font-size: 24px;
        cursor: pointer;
        color: #666;
    }
    
    .close:hover {
        color: #1a1a1a;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .status-in-stock {
        background: #dcfce7;
        color: #166534;
    }
    
    .status-low-stock {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-out-of-stock {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .btn-info {
        background: #0ea5e9;
        color: white;
        border: none;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.875rem;
        transition: background-color 0.2s ease;
    }
    
    .btn-info:hover {
        background: #0284c7;
    }
    
    .status-active {
        background: #dcfce7;
        color: #166534;
    }
    
    .status-inactive {
        background: #fef2f2;
        color: #dc2626;
    }
    
    /* Icon button styles - compact single row */
    .btn-sm {
        padding: 0.25rem 0.375rem;
        font-size: 0.75rem;
        border-radius: 4px;
        margin: 0 0.0625rem;
        min-width: 1.5rem;
        height: 1.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn-sm:active {
        transform: translateY(0);
    }
    
    /* Icon button specific colors */
    .btn-success {
        background: #10b981;
        color: white;
        border: none;
    }
    
    .btn-success:hover {
        background: #059669;
    }
    
    .btn-warning {
        background: #f59e0b;
        color: white;
        border: none;
    }
    
    .btn-warning:hover {
        background: #d97706;
    }
    
    /* Actions column styling */
    .actions-column {
        white-space: nowrap;
        min-width: 160px;
        max-width: 180px;
        width: 160px;
    }
    
    .actions-column .btn-sm {
        font-size: 0.65rem;
        padding: 0.15rem 0.25rem;
        margin: 0 0.03rem;
        min-width: 1.2rem;
        height: 1.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Table optimization for compact layout */
    table {
        table-layout: fixed;
        width: 100%;
    }
    
    table th, table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    /* Specific column widths for better space utilization */
    table th:nth-child(1), table td:nth-child(1) { width: 4%; } /* S.No */
    table th:nth-child(2), table td:nth-child(2) { width: 10%; } /* SKU */
    table th:nth-child(3), table td:nth-child(3) { width: 15%; } /* Name */
    table th:nth-child(4), table td:nth-child(4) { width: 8%; } /* HSC Code */
    table th:nth-child(5), table td:nth-child(5) { width: 10%; } /* PUC Code */
    table th:nth-child(6), table td:nth-child(6) { width: 8%; } /* Category */
    table th:nth-child(7), table td:nth-child(7) { width: 6%; } /* Unit */
    table th:nth-child(8), table td:nth-child(8) { width: 10%; } /* Price Range */
    table th:nth-child(9), table td:nth-child(9) { width: 8%; } /* Qty Available */
    table th:nth-child(10), table td:nth-child(10) { width: 6%; } /* Stock */
    table th:nth-child(11), table td:nth-child(11) { width: 6%; } /* Batches */
    table th:nth-child(12), table td:nth-child(12) { width: 8%; } /* Status */
    table th:nth-child(13), table td:nth-child(13) { width: 8%; } /* Actions */
    
    /* Text truncation for long content */
    table td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Allow text wrapping for specific columns that need it */
    table td:nth-child(3), /* Name */
    table td:nth-child(8) { /* Price Range */
        white-space: normal;
        word-wrap: break-word;
    }
    
    /* Ensure actions column doesn't get cut off */
    table td:nth-child(12) {
        overflow: visible;
        white-space: nowrap;
    }
    
    /* Force all buttons to be visible */
    .actions-column {
        overflow: visible !important;
        white-space: nowrap !important;
    }
</style>
@endpush

@push('scripts')
<!-- QuaggaJS for barcode detection -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<!-- ZXing for QR code detection -->
<script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
<script>
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let products = [];
    let categories = [];
    let units = [];

    // Helper function to format error messages
    function formatErrorMessage(data, defaultMessage) {
        if (data.errors) {
            const errorArray = Object.values(data.errors).flat();
            return errorArray.join(', ');
        } else if (data.message) {
            return data.message;
        }
        return defaultMessage;
    }
    let conversions = [];

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadCategories();
        loadProducts();
        loadUnits();
        updateStatistics();
    });

    // Tab switching
    function switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        
        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById(tabName + '-tab').classList.add('active');
    }

    // Product form submission
    document.getElementById('product-form').addEventListener('submit', function(e) {
        e.preventDefault();
        addProduct();
    });

    // Category form submission
    document.getElementById('category-form').addEventListener('submit', function(e) {
        e.preventDefault();
        addCategory();
    });

    // Edit product form submission
    document.getElementById('edit-product-form').addEventListener('submit', function(e) {
        e.preventDefault();
        updateProduct();
    });

    // Edit category form submission
    document.getElementById('edit-category-form').addEventListener('submit', function(e) {
        e.preventDefault();
        updateCategory();
    });

    // Unit form submission
    document.getElementById('unit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        addUnit();
    });

    // Conversion form submission
    document.getElementById('conversion-form').addEventListener('submit', function(e) {
        e.preventDefault();
        addConversion();
    });

    // Edit conversion form submission
    document.getElementById('edit-conversion-form').addEventListener('submit', function(e) {
        e.preventDefault();
        updateConversion();
    });


    // Load categories
    function loadCategories() {
        fetch('/inventory/categories', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                categories = data.data;
                populateCategorySelects();
                displayCategories();
                updateStatistics();
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
        });
    }

    // Load products
    function loadProducts() {
        fetch('/inventory/products', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                products = data.data;
                displayProducts();
                updateStatistics();
                // Load conversions after products are loaded
                loadConversions();
                // Populate batch selects after products are loaded
                populateBatchSelects();
            } else {
                console.error('API returned success: false', data);
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
        });
    }

    // Load units
    function loadUnits() {
        fetch('/inventory/units', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                units = data.data;
                populateUnitSelects();
                displayUnits();
                updateStatistics();
            }
        })
        .catch(error => {
            console.error('Error loading units:', error);
        });
    }

    // Load conversions
    function loadConversions() {
        // Only load conversions if products are available
        if (products.length === 0) {
            return;
        }
        
        // Load all conversions by fetching from all products
        Promise.all(products.map(product => 
            fetch(`/inventory/products/${product.id}/conversions`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => data.success ? data.data : [])
        ))
        .then(allConversions => {
            conversions = allConversions.flat();
            populateConversionSelects();
            displayConversions();
            updateStatistics();
        })
        .catch(error => {
            console.error('Error loading conversions:', error);
        });
    }

    // Populate category selects
    function populateCategorySelects() {
        const selects = ['product-category', 'edit-product-category'];
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Select Category</option>';
            categories.forEach(category => {
                select.innerHTML += `<option value="${category.id}">${category.name}</option>`;
            });
            
            // Initialize Selectize after populating options
            if (!select.classList.contains('selectized')) {
                $(select).selectize({
                    sortField: 'text',
                    create: false,
                    allowEmptyOption: true,
                    placeholder: 'Select Category...'
                });
            }
        });
    }

    // Populate unit selects
    function populateUnitSelects() {
        const selects = ['product-unit', 'edit-product-unit', 'conversion-from-unit', 'conversion-to-unit'];
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            select.innerHTML = '<option value="">Select Unit</option>';
            units.forEach(unit => {
                select.innerHTML += `<option value="${unit.id}">${unit.name} (${unit.symbol})</option>`;
            });
            
            // Initialize Selectize after populating options
            if (!select.classList.contains('selectized')) {
                $(select).selectize({
                    sortField: 'text',
                    create: false,
                    allowEmptyOption: true,
                    placeholder: 'Select Unit...'
                });
            }
        });
    }

    // Populate conversion selects
    function populateConversionSelects() {
        // Populate product filter
        const productFilter = document.getElementById('conversion-product-filter');
        productFilter.innerHTML = '<option value="">All Products</option>';
        products.forEach(product => {
            productFilter.innerHTML += `<option value="${product.id}">${product.name}</option>`;
        });

        // Populate conversion form product select
        const conversionProductSelect = document.getElementById('conversion-product');
        conversionProductSelect.innerHTML = '<option value="">Select Product</option>';
        products.forEach(product => {
            conversionProductSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
        });
    }


    // Display products
    function displayProducts(productsToDisplay = null) {
        try {
            const container = document.getElementById('products-container');
            
            if (!container) {
                console.error('Products container not found');
                return;
            }
            
            const productsData = productsToDisplay || products;
            
            if (productsData.length === 0) {
                container.innerHTML = '<div class="loading">No products found</div>';
                return;
            }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>HSC Code</th>
                        <th>PUC Code</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Price Range</th>
                        <th>Qty Available</th>
                        <th>Stock</th>
                        <th>Batches</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        productsData.forEach((product, index) => {
            let statusClass = 'status-in-stock';
            let statusText = 'In Stock';
            
            // Use batch stock instead of product stock
            const totalStock = product.total_batch_stock || 0;
            
            if (totalStock <= 0) {
                statusClass = 'status-out-of-stock';
                statusText = 'Out of Stock';
            } else if (totalStock <= product.min_stock_level) {
                statusClass = 'status-low-stock';
                statusText = 'Low Stock';
            }
            
            // Price range from batches
            const priceRange = product.selling_price_range || { min: 0, max: 0 };
            const priceDisplay = priceRange.min > 0 && priceRange.max > 0 
                ? `₹${parseFloat(priceRange.min).toFixed(2)} - ₹${parseFloat(priceRange.max).toFixed(2)}`
                : 'No batches';
            
            // Safe unit symbol access
            const unitSymbol = product.unit && product.unit.symbol ? product.unit.symbol : 'N/A';
            
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${product.sku || 'N/A'}</td>
                    <td>${product.name || 'N/A'}</td>
                    <td>${product.hsc_code || 'N/A'}</td>
                    <td>${product.puc_code || 'N/A'}</td>
                    <td>${product.category && product.category.name ? product.category.name : 'N/A'}</td>
                    <td>${unitSymbol}</td>
                    <td>${priceDisplay}</td>
                    <td>${totalStock} ${unitSymbol}</td>
                    <td>${product.stock_quantity || 0} ${unitSymbol}</td>
                    <td>${product.batch_count || 0}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td class="actions-column">
                        <button class="btn btn-info btn-sm" onclick="viewProduct(${product.id})" title="View">👁️</button>
                        <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})" title="Edit">✏️</button>
                        <button class="btn btn-primary btn-sm" onclick="viewBatches(${product.id})" title="Batches">📦</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})" title="Delete">🗑️</button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
        } catch (error) {
            console.error('Error displaying products:', error);
            const container = document.getElementById('products-container');
            if (container) {
                container.innerHTML = '<div class="loading">Error loading products</div>';
            }
        }
    }

    // Display categories
    function displayCategories(categoriesToDisplay = null) {
        const container = document.getElementById('categories-container');
        
        const categoriesData = categoriesToDisplay || categories;
        
        if (categoriesData.length === 0) {
            container.innerHTML = '<div class="loading">No categories found</div>';
            return;
        }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        categoriesData.forEach(category => {
            html += `
                <tr>
                    <td>${category.name}</td>
                    <td>${category.description || 'N/A'}</td>
                    <td>${new Date(category.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-secondary" onclick="editCategory(${category.id})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteCategory(${category.id})">Delete</button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // Display units
    function displayUnits(unitsToDisplay = null) {
        const container = document.getElementById('units-container');
        
        const unitsData = unitsToDisplay || units;
        
        if (unitsData.length === 0) {
            container.innerHTML = '<div class="loading">No units found</div>';
            return;
        }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        unitsData.forEach(unit => {
            const statusClass = unit.is_active ? 'status-active' : 'status-inactive';
            const statusText = unit.is_active ? 'Active' : 'Inactive';
            const createdDate = new Date(unit.created_at).toLocaleDateString();
            
            html += `
                <tr>
                    <td>${unit.name}</td>
                    <td><strong>${unit.symbol}</strong></td>
                    <td>${unit.description || 'N/A'}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>${createdDate}</td>
                    <td>
                        <button class="btn btn-secondary" onclick="editUnit(${unit.id})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteUnit(${unit.id})">Delete</button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // Display conversions
    function displayConversions(conversionsToDisplay = null) {
        const container = document.getElementById('conversions-container');
        
        const conversionsData = conversionsToDisplay || conversions;
        
        if (conversionsData.length === 0) {
            container.innerHTML = '<div class="loading">No conversions found</div>';
            return;
        }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>From Unit</th>
                        <th>To Unit</th>
                        <th>Conversion Factor</th>
                        <th>Example</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        conversionsData.forEach(conversion => {
            const statusClass = conversion.is_active ? 'status-active' : 'status-inactive';
            const statusText = conversion.is_active ? 'Active' : 'Inactive';
            const example = `1 ${conversion.from_unit.symbol} = ${conversion.conversion_factor} ${conversion.to_unit.symbol}`;
            
            html += `
                <tr>
                    <td>${conversion.product ? conversion.product.name : 'N/A'}</td>
                    <td><strong>${conversion.from_unit.symbol}</strong></td>
                    <td><strong>${conversion.to_unit.symbol}</strong></td>
                    <td>${conversion.conversion_factor}</td>
                    <td><small>${example}</small></td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <button class="btn btn-secondary" onclick="editConversion(${conversion.id})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteConversion(${conversion.id})">Delete</button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // Add product
    function addProduct() {
        const formData = new FormData(document.getElementById('product-form'));
        
        fetch('/inventory/products', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Product created successfully', 'success');
                closeAddProductModal();
                loadProducts();
            } else {
                showAlert(formatErrorMessage(data, 'Error creating product'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error creating product', 'error');
        });
    }

    // Add category
    function addCategory() {
        const formData = new FormData(document.getElementById('category-form'));
        
        fetch('/inventory/categories', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Category created successfully', 'success');
                closeAddCategoryModal();
                loadCategories();
            } else {
                showAlert(formatErrorMessage(data, 'Error creating category'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error creating category', 'error');
        });
    }

    // Add unit
    function addUnit() {
        const formData = new FormData(document.getElementById('unit-form'));
        
        fetch('/inventory/units', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Unit created successfully', 'success');
                closeAddUnitModal();
                loadUnits();
            } else {
                showAlert('Error: ' + JSON.stringify(data.errors), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error creating unit', 'error');
        });
    }

    // Add conversion
    function addConversion() {
        const formData = new FormData(document.getElementById('conversion-form'));
        
        fetch('/inventory/conversions', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Unit conversion created successfully', 'success');
                closeAddConversionModal();
                loadConversions();
            } else {
                showAlert('Error: ' + (data.message || JSON.stringify(data.errors)), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error creating conversion', 'error');
        });
    }

    // Update conversion
    function updateConversion() {
        const formData = new FormData(document.getElementById('edit-conversion-form'));
        const id = document.getElementById('edit-conversion-id').value;
        
        fetch(`/inventory/conversions/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Unit conversion updated successfully', 'success');
                closeEditConversionModal();
                loadConversions();
            } else {
                showAlert('Error: ' + (data.message || JSON.stringify(data.errors)), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating conversion', 'error');
        });
    }

    // Edit product
    function editProduct(id) {
        const product = products.find(p => p.id === id);
        if (product) {
            // Set basic form values
            document.getElementById('edit-product-id').value = product.id;
            document.getElementById('edit-product-name').value = product.name;
            document.getElementById('edit-product-sku').value = product.sku;
            document.getElementById('edit-product-hsc-code').value = product.hsc_code || '';
            document.getElementById('edit-product-puc-code').value = product.puc_code || '';
            document.getElementById('edit-product-stock').value = product.stock_quantity;
            document.getElementById('edit-product-min-stock').value = product.min_stock_level;
            document.getElementById('edit-product-description').value = product.description || '';
            
            // Ensure Selectize instances are initialized
            populateCategorySelects();
            populateUnitSelects();
            
            // Wait a bit for Selectize to initialize, then set values
            setTimeout(() => {
                const categorySelect = document.getElementById('edit-product-category');
                const unitSelect = document.getElementById('edit-product-unit');
                
                // Set category value using Selectize API
                if (categorySelect.selectize) {
                    categorySelect.selectize.setValue(product.category_id);
                } else {
                    categorySelect.value = product.category_id;
                }
                
                // Set unit value using Selectize API
                if (unitSelect.selectize) {
                    unitSelect.selectize.setValue(product.unit_id);
                } else {
                    unitSelect.value = product.unit_id;
                }
            }, 100);
            
            document.getElementById('editProductModal').style.display = 'block';
        }
    }

    // Edit category
    function editCategory(id) {
        const category = categories.find(c => c.id === id);
        if (category) {
            document.getElementById('edit-category-id').value = category.id;
            document.getElementById('edit-category-name').value = category.name;
            document.getElementById('edit-category-description').value = category.description || '';
            document.getElementById('editCategoryModal').style.display = 'block';
        }
    }

    // Update product
    function updateProduct() {
        const formData = new FormData(document.getElementById('edit-product-form'));
        const id = document.getElementById('edit-product-id').value;
        
        fetch(`/inventory/products/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Product updated successfully', 'success');
                closeEditProductModal();
                loadProducts();
            } else {
                showAlert(formatErrorMessage(data, 'Error updating product'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating product', 'error');
        });
    }

    // Update category
    function updateCategory() {
        const formData = new FormData(document.getElementById('edit-category-form'));
        const id = document.getElementById('edit-category-id').value;
        
        fetch(`/inventory/categories/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Category updated successfully', 'success');
                closeEditCategoryModal();
                loadCategories();
            } else {
                showAlert('Error: ' + JSON.stringify(data.errors), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating category', 'error');
        });
    }

    // Delete product
    function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            fetch(`/inventory/products/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Product deleted successfully', 'success');
                    loadProducts();
                } else {
                    showAlert('Error deleting product', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting product', 'error');
            });
        }
    }

    // Delete category
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category?')) {
            fetch(`/inventory/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Category deleted successfully', 'success');
                    loadCategories();
                } else {
                    showAlert('Error deleting category', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting category', 'error');
            });
        }
    }

    // Delete unit
    function deleteUnit(id) {
        if (confirm('Are you sure you want to delete this unit?')) {
            fetch(`/inventory/units/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Unit deleted successfully', 'success');
                    loadUnits();
                } else {
                    showAlert('Error deleting unit', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting unit', 'error');
            });
        }
    }

    // Modal functions
    function openAddProductModal() {
        document.getElementById('addProductModal').style.display = 'block';
        populateCategorySelects();
        populateUnitSelects();
    }

    function closeAddProductModal() {
        document.getElementById('addProductModal').style.display = 'none';
        document.getElementById('product-form').reset();
    }

    function openAddCategoryModal() {
        document.getElementById('addCategoryModal').style.display = 'block';
    }

    function closeAddCategoryModal() {
        document.getElementById('addCategoryModal').style.display = 'none';
        document.getElementById('category-form').reset();
    }

    function openAddUnitModal() {
        document.getElementById('addUnitModal').style.display = 'block';
    }

    function closeAddUnitModal() {
        document.getElementById('addUnitModal').style.display = 'none';
        document.getElementById('unit-form').reset();
    }

    function openAddConversionModal() {
        document.getElementById('addConversionModal').style.display = 'block';
        populateConversionSelects();
        
        // Add event listener to auto-select product's unit when product is selected
        const productSelect = document.getElementById('conversion-product');
        const fromUnitSelect = document.getElementById('conversion-from-unit');
        
        productSelect.addEventListener('change', function() {
            const selectedProductId = this.value;
            if (selectedProductId) {
                const product = products.find(p => p.id == selectedProductId);
                if (product && product.unit) {
                    // Automatically select the product's current unit as the purchase unit
                    fromUnitSelect.value = product.unit.id;
                }
            }
        });
    }

    function closeAddConversionModal() {
        document.getElementById('addConversionModal').style.display = 'none';
        document.getElementById('conversion-form').reset();
        
        // Remove event listener to prevent multiple listeners
        const productSelect = document.getElementById('conversion-product');
        const fromUnitSelect = document.getElementById('conversion-from-unit');
        
        // Clone and replace the select to remove all event listeners
        const newProductSelect = productSelect.cloneNode(true);
        productSelect.parentNode.replaceChild(newProductSelect, productSelect);
        
        // Reset the from unit select
        fromUnitSelect.value = '';
    }

    // Edit conversion
    function editConversion(id) {
        const conversion = conversions.find(c => c.id === id);
        if (!conversion) {
            showAlert('Conversion not found', 'error');
            return;
        }

        // Populate form with conversion data
        document.getElementById('edit-conversion-id').value = conversion.id;
        document.getElementById('edit-conversion-product').value = conversion.product_id;
        document.getElementById('edit-conversion-product-display').value = conversion.product ? conversion.product.name : 'Unknown Product';
        document.getElementById('edit-conversion-from-unit').value = conversion.from_unit_id;
        document.getElementById('edit-conversion-from-unit-display').value = conversion.from_unit ? conversion.from_unit.symbol : 'Unknown Unit';
        document.getElementById('edit-conversion-to-unit').value = conversion.to_unit_id;
        document.getElementById('edit-conversion-to-unit-display').value = conversion.to_unit ? conversion.to_unit.symbol : 'Unknown Unit';
        document.getElementById('edit-conversion-factor').value = conversion.conversion_factor;
        document.getElementById('edit-conversion-status').value = conversion.is_active ? '1' : '0';

        // Show modal
        document.getElementById('editConversionModal').style.display = 'block';
    }

    // Close edit conversion modal
    function closeEditConversionModal() {
        document.getElementById('editConversionModal').style.display = 'none';
        document.getElementById('edit-conversion-form').reset();
    }

    // Delete conversion
    function deleteConversion(id) {
        if (confirm('Are you sure you want to delete this unit conversion?')) {
            fetch(`/inventory/conversions/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Unit conversion deleted successfully', 'success');
                    loadConversions();
                } else {
                    showAlert('Error deleting unit conversion', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting unit conversion', 'error');
            });
        }
    }

    function closeEditProductModal() {
        document.getElementById('editProductModal').style.display = 'none';
    }

    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').style.display = 'none';
    }

    // View product function
    function viewProduct(id) {
        // Show modal with loading state
        document.getElementById('viewProductModal').style.display = 'block';
        document.getElementById('product-details-content').innerHTML = '<div class="loading">Loading product details...</div>';
        
        // Fetch product details
        fetch(`/inventory/products/${id}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayProductDetails(data.data);
            } else {
                document.getElementById('product-details-content').innerHTML = '<div class="alert alert-error">Error loading product details</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('product-details-content').innerHTML = '<div class="alert alert-error">Error loading product details</div>';
        });
    }

    // Display product details in modal
    function displayProductDetails(product) {
        const container = document.getElementById('product-details-content');
        
        // Calculate status
        let statusClass = 'status-in-stock';
        let statusText = 'In Stock';
        const totalStock = product.total_batch_stock || 0;
        
        if (totalStock <= 0) {
            statusClass = 'status-out-of-stock';
            statusText = 'Out of Stock';
        } else if (totalStock <= product.min_stock_level) {
            statusClass = 'status-low-stock';
            statusText = 'Low Stock';
        }
        
        // Price ranges
        const sellingPriceRange = product.selling_price_range || { min: 0, max: 0 };
        const purchasePriceRange = product.purchase_price_range || { min: 0, max: 0 };
        
        const sellingPriceDisplay = sellingPriceRange.min > 0 && sellingPriceRange.max > 0 
            ? `₹${parseFloat(sellingPriceRange.min).toFixed(2)} - ₹${parseFloat(sellingPriceRange.max).toFixed(2)}`
            : 'No batches';
            
        const purchasePriceDisplay = purchasePriceRange.min > 0 && purchasePriceRange.max > 0 
            ? `₹${parseFloat(purchasePriceRange.min).toFixed(2)} - ₹${parseFloat(purchasePriceRange.max).toFixed(2)}`
            : 'No batches';
        
        // Unit conversions
        let conversionsHtml = '';
        if (product.unit_conversions && product.unit_conversions.length > 0) {
            conversionsHtml = product.unit_conversions.map(conversion => 
                `<tr>
                    <td>${conversion.from_unit ? conversion.from_unit.symbol : 'N/A'}</td>
                    <td>${conversion.to_unit ? conversion.to_unit.symbol : 'N/A'}</td>
                    <td>${conversion.conversion_factor}</td>
                    <td>${conversion.is_active ? 'Active' : 'Inactive'}</td>
                </tr>`
            ).join('');
        } else {
            conversionsHtml = '<tr><td colspan="4" style="text-align: center; color: #666;">No unit conversions available</td></tr>';
        }
        
        // Batches
        let batchesHtml = '';
        if (product.batches && product.batches.length > 0) {
            batchesHtml = product.batches.map(batch => 
                `<tr>
                    <td>${batch.batch_number || 'N/A'}</td>
                    <td>${batch.supplier ? batch.supplier.name : 'N/A'}</td>
                    <td>${batch.quantity || 0}</td>
                    <td>₹${parseFloat(batch.purchase_price || 0).toFixed(2)}</td>
                    <td>₹${parseFloat(batch.selling_price || 0).toFixed(2)}</td>
                    <td>${batch.expiry_date ? new Date(batch.expiry_date).toLocaleDateString() : 'N/A'}</td>
                    <td>${batch.is_active ? 'Active' : 'Inactive'}</td>
                </tr>`
            ).join('');
        } else {
            batchesHtml = '<tr><td colspan="7" style="text-align: center; color: #666;">No batches available</td></tr>';
        }
        
        container.innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h4>Basic Information</h4>
                    </div>
                    <div style="padding: 1rem;">
                        <div style="margin-bottom: 1rem;">
                            <strong>Product Name:</strong> ${product.name || 'N/A'}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>SKU:</strong> ${product.sku || 'N/A'}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>HSC Code:</strong> ${product.hsc_code || 'N/A'}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>PUC Code:</strong> ${product.puc_code || 'N/A'}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Category:</strong> ${product.category ? product.category.name : 'N/A'}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Unit:</strong> ${product.unit ? product.unit.name + ' (' + product.unit.symbol + ')' : 'N/A'}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Description:</strong> ${product.description || 'No description available'}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Status:</strong> <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Stock & Pricing Information -->
                <div class="card">
                    <div class="card-header">
                        <h4>Stock & Pricing</h4>
                    </div>
                    <div style="padding: 1rem;">
                        <div style="margin-bottom: 1rem;">
                            <strong>Current Stock:</strong> ${totalStock} ${product.unit ? product.unit.symbol : ''}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Minimum Stock Level:</strong> ${product.min_stock_level || 0} ${product.unit ? product.unit.symbol : ''}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Average Selling Price:</strong> ₹${parseFloat(product.average_selling_price || 0).toFixed(2)}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Average Purchase Price:</strong> ₹${parseFloat(product.average_purchase_price || 0).toFixed(2)}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Selling Price Range:</strong> ${sellingPriceDisplay}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Purchase Price Range:</strong> ${purchasePriceDisplay}
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Average Profit Margin:</strong> ${parseFloat(product.average_profit_margin || 0).toFixed(2)}%
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Total Batches:</strong> ${product.batch_count || 0}
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Unit Conversions -->
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header">
                    <h4>Unit Conversions</h4>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>From Unit</th>
                                <th>To Unit</th>
                                <th>Conversion Factor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${conversionsHtml}
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Batches -->
            <div class="card">
                <div class="card-header">
                    <h4>Batches</h4>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Batch Number</th>
                                <th>Supplier</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${batchesHtml}
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                <button class="btn btn-secondary" onclick="closeViewProductModal()">Close</button>
            </div>
        `;
    }

    // Close view product modal
    function closeViewProductModal() {
        document.getElementById('viewProductModal').style.display = 'none';
    }

    // Barcode Scanner Variables
    let currentTargetField = '';
    let stream = null;
    let scanning = false;
    let scanTimeout = null;

    // Open barcode scanner modal
    function openBarcodeScanner(targetFieldId) {
        currentTargetField = targetFieldId;
        document.getElementById('barcodeScannerModal').style.display = 'block';
        document.getElementById('scan-result').style.display = 'none';
        
        // Automatically start scanning when modal opens
        setTimeout(() => {
            startScanning();
        }, 100);
        
        // Set timeout to close scanner after 30 seconds if no barcode detected
        scanTimeout = setTimeout(() => {
            if (scanning) {
                showAlert('No barcode detected. Please try again.', 'error');
                closeBarcodeScanner();
            }
        }, 30000); // 30 seconds timeout
    }

    // Close barcode scanner modal
    function closeBarcodeScanner() {
        stopScanning();
        document.getElementById('barcodeScannerModal').style.display = 'none';
        currentTargetField = '';
        
        // Clear timeout
        if (scanTimeout) {
            clearTimeout(scanTimeout);
            scanTimeout = null;
        }
    }

    // Start camera and scanning
    function startScanning() {
        if (scanning) return;
        
        scanning = true;
        
        // Show scanning indicator
        document.getElementById('scanning-indicator').style.display = 'block';
        
        // Initialize QuaggaJS for barcode detection
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-video'),
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment" // Use back camera
                },
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "code_39_vin_reader",
                    "codabar_reader",
                    "upc_reader",
                    "upc_e_reader",
                    "i2of5_reader"
                ]
            },
            locate: true,
            locator: {
                patchSize: "medium",
                halfSample: true
            }
        }, function(err) {
            if (err) {
                console.error('QuaggaJS initialization error:', err);
                alert('Error initializing barcode scanner. Please try again.');
                scanning = false;
                document.getElementById('scanning-indicator').style.display = 'none';
                return;
            }
            console.log("QuaggaJS initialization finished. Ready to start");
            Quagga.start();
        });

        // Listen for barcode detection
        Quagga.onDetected(function(result) {
            if (result && result.codeResult) {
                const code = result.codeResult.code;
                console.log("Barcode detected:", code);
                
                // Clear timeout since we found a barcode
                if (scanTimeout) {
                    clearTimeout(scanTimeout);
                    scanTimeout = null;
                }
                
                // Automatically fill the field and close scanner
                document.getElementById(currentTargetField).value = code;
                
                // Show success message briefly
                showAlert('Barcode scanned successfully!', 'success');
                
                closeBarcodeScanner();
            }
        });

        // Also set up QR code detection using ZXing
        setupQRCodeDetection();
    }

    // Stop camera and scanning
    function stopScanning() {
        if (scanning) {
            // Stop QuaggaJS
            Quagga.stop();
            scanning = false;
        }
        
        const video = document.getElementById('scanner-video');
        const captureBtn = document.getElementById('capture-btn');
        const scanningIndicator = document.getElementById('scanning-indicator');
        
        // Clear video
        video.innerHTML = '';
        captureBtn.style.display = 'none';
        scanningIndicator.style.display = 'none';
    }

    // Manual capture function (kept for compatibility but not used with QuaggaJS)
    function captureBarcode() {
        // This function is not needed with QuaggaJS as it detects automatically
        console.log('Manual capture not needed with QuaggaJS');
    }

    // Setup QR code detection using ZXing
    function setupQRCodeDetection() {
        const codeReader = new ZXing.BrowserMultiFormatReader();
        const videoElement = document.getElementById('scanner-video');
        
        // Start QR code detection
        codeReader.decodeFromVideoDevice(undefined, videoElement, (result, err) => {
            if (result) {
                const code = result.getText();
                console.log("QR Code detected:", code);
                
                // Clear timeout since we found a code
                if (scanTimeout) {
                    clearTimeout(scanTimeout);
                    scanTimeout = null;
                }
                
                // Automatically fill the field and close scanner
                document.getElementById(currentTargetField).value = code;
                
                // Show success message briefly
                showAlert('QR Code scanned successfully!', 'success');
                
                // Stop QR code detection
                codeReader.reset();
                closeBarcodeScanner();
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error('QR Code detection error:', err);
            }
        });
    }

    // Handle barcode detection
    function onBarcodeDetected(code) {
        stopScanning();
        document.getElementById('scanned-code').textContent = code;
        document.getElementById('scan-result').style.display = 'block';
    }

    // Use the scanned code
    function useScannedCode() {
        const code = document.getElementById('scanned-code').textContent;
        if (currentTargetField && code) {
            document.getElementById(currentTargetField).value = code;
        }
        closeBarcodeScanner();
    }

    // Clear scan result and scan again
    function clearScanResult() {
        document.getElementById('scan-result').style.display = 'none';
        // Don't restart scanning, just show the capture button again
        if (scanning) {
            document.getElementById('capture-btn').style.display = 'inline-block';
        }
    }

    // Statistics update
    function updateStatistics() {
        try {
            const totalProducts = products.length;
            const totalCategories = categories.length;
            const totalUnits = units.length;
            const lowStockItems = products.filter(p => {
                const totalStock = p.stock_quantity || 0;
                return totalStock > 0 && totalStock <= p.min_stock_level;
            }).length;
            const outOfStockItems = products.filter(p => {
                const totalStock = p.stock_quantity || 0;
                return totalStock <= 0;
            }).length;

            document.getElementById('total-products-count').textContent = totalProducts;
            document.getElementById('total-categories-count').textContent = totalCategories;
            document.getElementById('total-units-count').textContent = totalUnits;
            document.getElementById('low-stock-count').textContent = lowStockItems;
            document.getElementById('out-of-stock-count').textContent = outOfStockItems;
            document.getElementById('total-batches-count').textContent = '0';
            document.getElementById('expired-batches-count').textContent = '0';
        } catch (error) {
            console.error('Error updating statistics:', error);
        }
    }

    // Search functions
    function searchProducts() {
        const searchTerm = document.getElementById('product-search').value.toLowerCase();
        const filteredProducts = products.filter(product => 
            product.name.toLowerCase().includes(searchTerm) ||
            product.sku.toLowerCase().includes(searchTerm) ||
            product.hsc_code.toLowerCase().includes(searchTerm) ||
            product.puc_code.toLowerCase().includes(searchTerm) ||
            (product.category && product.category.name.toLowerCase().includes(searchTerm)) ||
            (product.unit && product.unit.name.toLowerCase().includes(searchTerm)) ||
            (product.unit && product.unit.symbol.toLowerCase().includes(searchTerm))
        );
        
        displayProducts(filteredProducts);
    }

    function clearProductSearch() {
        document.getElementById('product-search').value = '';
        displayProducts(products);
    }

    function searchCategories() {
        const searchTerm = document.getElementById('category-search').value.toLowerCase();
        const filteredCategories = categories.filter(category => 
            category.name.toLowerCase().includes(searchTerm) ||
            (category.description && category.description.toLowerCase().includes(searchTerm)) ||
            category.id.toString().includes(searchTerm)
        );
        
        displayCategories(filteredCategories);
    }

    function clearCategorySearch() {
        document.getElementById('category-search').value = '';
        displayCategories(categories);
    }

    function searchUnits() {
        const searchTerm = document.getElementById('unit-search').value.toLowerCase();
        const filteredUnits = units.filter(unit => 
            unit.name.toLowerCase().includes(searchTerm) ||
            unit.symbol.toLowerCase().includes(searchTerm) ||
            (unit.description && unit.description.toLowerCase().includes(searchTerm)) ||
            unit.id.toString().includes(searchTerm) ||
            (unit.is_active ? 'active' : 'inactive').includes(searchTerm)
        );
        
        displayUnits(filteredUnits);
    }

    function clearUnitSearch() {
        document.getElementById('unit-search').value = '';
        displayUnits(units);
    }

    function searchConversions() {
        const searchTerm = document.getElementById('conversion-search').value.toLowerCase();
        const filteredConversions = conversions.filter(conversion => 
            (conversion.product && conversion.product.name.toLowerCase().includes(searchTerm)) ||
            (conversion.from_unit && conversion.from_unit.name.toLowerCase().includes(searchTerm)) ||
            (conversion.from_unit && conversion.from_unit.symbol.toLowerCase().includes(searchTerm)) ||
            (conversion.to_unit && conversion.to_unit.name.toLowerCase().includes(searchTerm)) ||
            (conversion.to_unit && conversion.to_unit.symbol.toLowerCase().includes(searchTerm)) ||
            conversion.conversion_factor.toString().includes(searchTerm)
        );
        
        displayConversions(filteredConversions);
    }

    function clearConversionSearch() {
        document.getElementById('conversion-search').value = '';
        displayConversions(conversions);
    }

    function filterConversionsByProduct() {
        const productId = document.getElementById('conversion-product-filter').value;
        if (productId) {
            const filteredConversions = conversions.filter(conversion => conversion.product_id == productId);
            displayConversions(filteredConversions);
        } else {
            displayConversions(conversions);
        }
    }




    // Show alert
    function showAlert(message, type) {
        const container = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        
        container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
        
        setTimeout(() => {
            container.innerHTML = '';
        }, 5000);
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        // Don't close modal if clicking on Selectize elements
        if (e.target.closest('.selectize-control') || e.target.closest('.selectize-dropdown')) {
            return;
        }
        
        const addProductModal = document.getElementById('addProductModal');
        const addCategoryModal = document.getElementById('addCategoryModal');
        const addUnitModal = document.getElementById('addUnitModal');
        const addConversionModal = document.getElementById('addConversionModal');
        const editProductModal = document.getElementById('editProductModal');
        const editCategoryModal = document.getElementById('editCategoryModal');
        const editConversionModal = document.getElementById('editConversionModal');
        const viewProductModal = document.getElementById('viewProductModal');
        const barcodeScannerModal = document.getElementById('barcodeScannerModal');
        
        if (e.target === addProductModal) {
            closeAddProductModal();
        }
        if (e.target === addCategoryModal) {
            closeAddCategoryModal();
        }
        if (e.target === addUnitModal) {
            closeAddUnitModal();
        }
        if (e.target === addConversionModal) {
            closeAddConversionModal();
        }
        if (e.target === editProductModal) {
            closeEditProductModal();
        }
        if (e.target === editCategoryModal) {
            closeEditCategoryModal();
        }
        if (e.target === editConversionModal) {
            closeEditConversionModal();
        }
        if (e.target === viewProductModal) {
            closeViewProductModal();
        }
        if (e.target === barcodeScannerModal) {
            closeBarcodeScanner();
        }
    });
    
    // Selectize will be initialized after data is loaded in populate functions
</script>
@endpush