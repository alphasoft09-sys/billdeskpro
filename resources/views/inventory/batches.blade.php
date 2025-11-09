@extends('layouts.app')

@section('title', 'Batch Management - BillDesk Pro')

@push('styles')
<style>
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background-color: #ffffff;
        margin: 5% auto;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e5e5;
        background: #f8f9fa;
        border-radius: 8px 8px 0 0;
    }

    .modal-header h3 {
        margin: 0;
        color: #1a1a1a;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .close {
        color: #666;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .close:hover {
        color: #333;
    }

    .close:focus {
        color: #333;
        outline: none;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-input:read-only {
        background-color: #f9fafb;
        color: #6b7280;
    }

    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2563eb;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .btn-warning {
        background-color: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background-color: #d97706;
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    /* Table Styles - Compact Layout (Batches Table Only) */
    #batches-container .table {
        font-size: 0.8rem;
        line-height: 1.2;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    
    #batches-container .table th {
        padding: 0.5rem 0.4rem;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
        text-align: left;
    }
    
    #batches-container .table td {
        padding: 0.4rem;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    #batches-container .table tr:hover {
        background-color: #f9fafb;
    }
    
    /* Ensure table fits in container */
    .table-container {
        overflow-x: auto;
        width: 100%;
    }
    
    /* Compact button styles for batches table only */
    #batches-container .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.65rem;
        line-height: 1.2;
    }

    /* Status Badge Styles (Batches Table Only) */
    #batches-container .status-badge {
        padding: 0.2rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.65rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    #batches-container .status-badge.status-active {
        background-color: #d1fae5;
        color: #065f46;
    }

    #batches-container .status-badge.status-expired {
        background-color: #fee2e2;
        color: #991b1b;
        font-weight: bold;
        animation: pulse-red 2s infinite;
    }

    #batches-container .status-badge.status-expiring-soon {
        background-color: #fef3c7;
        color: #92400e;
        font-weight: bold;
        animation: pulse-orange 2s infinite;
    }

    #batches-container .status-badge.status-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    #batches-container .status-badge.status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Row highlighting for expired and expiring batches */
    .batch-row-expired {
        background-color: #fef2f2 !important;
        border-left: 4px solid #dc2626 !important;
        animation: pulse-row-red 3s infinite;
    }

    .batch-row-expiring-soon {
        background-color: #fffbeb !important;
        border-left: 4px solid #f59e0b !important;
        animation: pulse-row-orange 3s infinite;
    }

    /* Animations */
    @keyframes pulse-red {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    @keyframes pulse-orange {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    @keyframes pulse-row-red {
        0%, 100% { background-color: #fef2f2; }
        50% { background-color: #fee2e2; }
    }

    @keyframes pulse-row-orange {
        0%, 100% { background-color: #fffbeb; }
        50% { background-color: #fef3c7; }
    }

    @keyframes pulse-row-yellow {
        0%, 100% { background-color: #fef3c7; }
        50% { background-color: #fde68a; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Batch Management</h1>
    <p class="page-subtitle">Manage product batches, stock levels, and batch-specific pricing</p>
</div>

<div id="alert-container"></div>

<!-- Expiry Alerts Banner -->
<div id="expiry-alerts" style="margin-bottom: 1rem;"></div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number" id="total-batches-count">0</div>
        <div class="stat-label">Total Batches</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="active-batches-count">0</div>
        <div class="stat-label">Active Batches</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="expired-batches-count">0</div>
        <div class="stat-label">Expired Batches</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="low-stock-batches-count">0</div>
        <div class="stat-label">Low Stock Batches</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="out-of-stock-batches-count">0</div>
        <div class="stat-label">Out of Stock</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="total-products-count">0</div>
        <div class="stat-label">Products with Batches</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" id="total-suppliers-count">0</div>
        <div class="stat-label">Active Suppliers</div>
    </div>
</div>

<!-- Action Buttons -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <button class="btn btn-primary" onclick="openAddBatchModal()">
            <span>📦</span>
            Add New Batch
        </button>
        <button class="btn btn-secondary" onclick="loadBatches()">
            <span>🔄</span>
            Refresh Data
        </button>
        <button class="btn btn-secondary" onclick="exportBatches()">
            <span>📊</span>
            Export Data
        </button>
    </div>
</div>

<!-- Batches List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Batches List</h3>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <select id="batch-product-filter" class="form-input" style="width: 200px;" onchange="filterBatches()">
                <option value="">All Products</option>
            </select>
            <select id="batch-status-filter" class="form-input" style="width: 150px;" onchange="filterBatches()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="expired">Expired</option>
                <option value="expiring_soon">Expiring Soon</option>
                <option value="low_stock">Low Stock</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
            <input type="text" id="batch-search" placeholder="Search batches..." class="form-input" style="width: 200px;" onkeyup="searchBatches()">
            <button class="btn btn-secondary" onclick="clearBatchSearch()">Clear</button>
        </div>
    </div>
    <div class="table-container">
        <div id="batches-container">
            <div class="loading">Loading batches...</div>
        </div>
    </div>
</div>

<!-- Add Batch Modal -->
<div id="addBatchModal" class="modal">
    <div class="modal-content" style="max-width: 800px; width: 95%; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3>Add New Batch</h3>
            <span class="close" onclick="closeAddBatchModal()">&times;</span>
        </div>
        <form id="batch-form" style="padding: 1rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="batch-product">Product</label>
                    <select id="batch-product" name="product_id" class="form-input" required>
                        <option value="">Select Product</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="batch-number">Batch Number</label>
                    <input type="text" id="batch-number" name="batch_number" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="batch-supplier">Supplier</label>
                    <select id="batch-supplier" name="supplier_id" class="form-input">
                        <option value="">Select Supplier (Optional)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="batch-quantity">Quantity Received</label>
                    <input type="number" id="batch-quantity" name="quantity_received" class="form-input" step="0.01" min="0" required>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="batch-purchase-price">Purchase Price</label>
                    <input type="number" id="batch-purchase-price" name="purchase_price" class="form-input" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="batch-selling-price">Selling Price</label>
                    <input type="number" id="batch-selling-price" name="selling_price" class="form-input" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="batch-production-date">Production Date</label>
                    <input type="date" id="batch-production-date" name="production_date" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label" for="batch-expiry-date">Expiry Date</label>
                    <input type="date" id="batch-expiry-date" name="expiry_date" class="form-input">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="batch-notes">Notes</label>
                <textarea id="batch-notes" name="notes" class="form-input" rows="3" placeholder="Additional notes about this batch..."></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeAddBatchModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Batch</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Batch Modal -->
<div id="editBatchModal" class="modal">
    <div class="modal-content" style="max-width: 800px; width: 95%; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3>Edit Batch</h3>
            <span class="close" onclick="closeEditBatchModal()">&times;</span>
        </div>
        <form id="edit-batch-form" style="padding: 1rem;">
            <input type="hidden" id="edit-batch-id" name="id">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="edit-batch-product">Product</label>
                    <input type="text" id="edit-batch-product-display" class="form-input" readonly>
                    <input type="hidden" id="edit-batch-product" name="product_id">
                    <small style="color: #666; font-size: 0.75rem;">Product cannot be changed</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-batch-number">Batch Number</label>
                    <input type="text" id="edit-batch-number" name="batch_number" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-batch-supplier">Supplier</label>
                    <select id="edit-batch-supplier" name="supplier_id" class="form-input">
                        <option value="">Select Supplier (Optional)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-batch-quantity">Quantity Received</label>
                    <input type="number" id="edit-batch-quantity" name="quantity_received" class="form-input" step="0.01" min="0" required>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="edit-batch-purchase-price">Purchase Price</label>
                    <input type="number" id="edit-batch-purchase-price" name="purchase_price" class="form-input" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-batch-selling-price">Selling Price</label>
                    <input type="number" id="edit-batch-selling-price" name="selling_price" class="form-input" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-batch-production-date">Production Date</label>
                    <input type="date" id="edit-batch-production-date" name="production_date" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-batch-expiry-date">Expiry Date</label>
                    <input type="date" id="edit-batch-expiry-date" name="expiry_date" class="form-input">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-batch-notes">Notes</label>
                <textarea id="edit-batch-notes" name="notes" class="form-input" rows="3" placeholder="Additional notes about this batch..."></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditBatchModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Batch</button>
            </div>
        </form>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div id="adjustStockModal" class="modal">
    <div class="modal-content" style="max-width: 500px; width: 95%;">
        <div class="modal-header">
            <h3>Adjust Stock</h3>
            <span class="close" onclick="closeAdjustStockModal()">&times;</span>
        </div>
        <form id="adjust-stock-form" style="padding: 1rem;">
            <input type="hidden" id="adjust-batch-id" name="batch_id">
            <div class="form-group">
                <label class="form-label" for="adjust-batch-product">Product</label>
                <input type="text" id="adjust-batch-product" class="form-input" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="adjust-batch-number">Batch Number</label>
                <input type="text" id="adjust-batch-number" class="form-input" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="adjust-current-stock">Current Stock</label>
                <input type="number" id="adjust-current-stock" class="form-input" readonly>
            </div>
            <div class="form-group">
                <label class="form-label" for="adjust-type">Adjustment Type</label>
                <select id="adjust-type" name="adjustment_type" class="form-input" required>
                    <option value="">Select Adjustment Type</option>
                    <option value="add">Add Stock</option>
                    <option value="remove">Remove Stock</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="adjust-quantity">Adjustment Quantity</label>
                <input type="number" id="adjust-quantity" name="quantity" class="form-input" step="0.01" min="1" required>
                <small style="color: #666; font-size: 0.75rem;">Enter the quantity to add or remove</small>
            </div>
            <div class="form-group">
                <label class="form-label" for="adjust-reason">Reason</label>
                <input type="text" id="adjust-reason" name="notes" class="form-input" placeholder="Reason for adjustment..." required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeAdjustStockModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Adjust Stock</button>
            </div>
        </form>
    </div>
</div>

<!-- Batch Actions Modal -->
<div id="batchActionsModal" class="modal">
    <div class="modal-content" style="max-width: 500px; width: 95%;">
        <div class="modal-header">
            <h3>Batch Actions</h3>
            <span class="close" onclick="closeBatchActionsModal()">&times;</span>
        </div>
        <div style="padding: 1rem;">
            <div style="margin-bottom: 1rem;">
                <h4 id="batch-actions-info"></h4>
                <p><strong>Current Stock:</strong> <span id="batch-actions-stock"></span></p>
                <p><strong>Status:</strong> <span id="batch-actions-status"></span></p>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <button class="btn btn-info" onclick="viewBatchDetailsFromActions()">
                    <span>👁️</span> View Batch Details
                </button>
                <button class="btn btn-primary" onclick="editBatchFromActions()">
                    <span>✏️</span> Edit Batch Details
                </button>
                <button class="btn btn-warning" onclick="viewStockDetailsFromActions()">
                    <span>📦</span> View Stock Details
                </button>
                <button class="btn btn-secondary" onclick="adjustStockFromActions()">
                    <span>📊</span> Adjust Stock
                </button>
                <button class="btn btn-danger" onclick="deleteBatchFromActions()">
                    <span>🗑️</span> Delete Batch
                </button>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeBatchActionsModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Batch Details Modal -->
<div id="batchDetailsModal" class="modal">
    <div class="modal-content" style="max-width: 800px; width: 95%;">
        <div class="modal-header">
            <h3>Batch Details</h3>
            <span class="close" onclick="closeBatchDetailsModal()">&times;</span>
        </div>
        <div style="padding: 1rem;">
            <div id="batch-details-content">
                <!-- Batch details will be populated here -->
            </div>
        </div>
    </div>
</div>

<!-- Stock Details Modal -->
<div id="stockDetailsModal" class="modal">
    <div class="modal-content" style="max-width: 800px; width: 95%;">
        <div class="modal-header">
            <h3>Stock Details</h3>
            <span class="close" onclick="closeStockDetailsModal()">&times;</span>
        </div>
        <div style="padding: 1rem;">
            <div id="stock-details-content">
                <!-- Stock details will be populated here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let batches = [];
    let products = [];
    let suppliers = [];

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, starting data loading...');
        
        // Initialize modals
        initializeModals();
        
        loadBatches().then(() => {
            console.log('Batches loaded, testing batch finding...');
            // Test batch finding after a short delay
            setTimeout(() => {
                if (batches && batches.length > 0) {
                    console.log('Testing batch finding with first batch ID:', batches[0].id);
                    // Test the batch finding logic
                    const testId = batches[0].id;
                    const found = batches.find(b => b.id == testId);
                    console.log('Test result - found batch:', found);
                    
                    // Test the server endpoint
                    console.log('Testing server endpoint for batch:', testId);
                    fetch(`/inventory/batches/${testId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Server test response:', data);
                    })
                    .catch(error => {
                        console.error('Server test error:', error);
                    });
                } else {
                    console.log('No batches found in loaded data');
                }
            }, 1000);
        });
        loadProducts();
        loadSuppliers();
        loadBatchStats();
    });

    // Initialize all modals
    function initializeModals() {
        console.log('Initializing modals...');
        
        // Ensure all modals are hidden by default
        const modals = [
            'batchActionsModal',
            'batchDetailsModal', 
            'stockDetailsModal',
            'addBatchModal',
            'editBatchModal',
            'adjustStockModal'
        ];
        
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                console.log(`Initialized modal: ${modalId}`);
            } else {
                console.error(`Modal not found: ${modalId}`);
            }
        });
        
        // Add click outside to close functionality
        window.addEventListener('click', function(event) {
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    }

    // Load batches
    function loadBatches() {
        return fetch('/inventory/batches/data', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                batches = data.data;
                console.log('Batches loaded successfully:', batches.length, 'batches');
                console.log('Sample batch data:', batches[0]);
                console.log('Batch IDs:', batches.map(b => ({ id: b.id, type: typeof b.id })));
                displayBatches();
                populateBatchSelects();
                return data;
            } else {
                showAlert('Error loading batches: ' + (data.message || 'Unknown error'), 'error');
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error loading batches:', error);
            showAlert('Error loading batches', 'error');
            throw error;
        });
    }

    // Load products
    function loadProducts() {
        fetch('/inventory/products', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                products = data.data;
                populateBatchSelects();
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
        });
    }

    // Load suppliers
    function loadSuppliers() {
        fetch('/suppliers/all', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                suppliers = data.data;
                populateBatchSelects();
            }
        })
        .catch(error => {
            console.error('Error loading suppliers:', error);
        });
    }

    // Load batch statistics
    function loadBatchStats() {
        fetch('/inventory/batch-stats', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-batches-count').textContent = data.data.total_batches || 0;
                document.getElementById('active-batches-count').textContent = data.data.active_batches || 0;
                document.getElementById('expired-batches-count').textContent = data.data.expired_batches || 0;
                document.getElementById('low-stock-batches-count').textContent = data.data.low_stock_batches || 0;
                document.getElementById('out-of-stock-batches-count').textContent = data.data.out_of_stock_batches || 0;
                document.getElementById('total-products-count').textContent = data.data.products_with_batches || 0;
                document.getElementById('total-suppliers-count').textContent = data.data.active_suppliers || 0;
            }
        })
        .catch(error => {
            console.error('Error loading batch stats:', error);
        });
    }

    // Display expiry and stock alerts
    function displayExpiryAlerts() {
        const alertsContainer = document.getElementById('expiry-alerts');
        if (!batches || batches.length === 0) {
            alertsContainer.innerHTML = '';
            return;
        }

        const expiredBatches = batches.filter(batch => getBatchStatus(batch) === 'expired');
        const expiringSoonBatches = batches.filter(batch => getBatchStatus(batch) === 'expiring_soon');
        const lowStockBatches = batches.filter(batch => getBatchStatus(batch) === 'low_stock');
        
        let alertsHtml = '';
        
        if (expiredBatches.length > 0) {
            alertsHtml += `
                <div style="background: linear-gradient(135deg, #fee2e2, #fecaca); border: 2px solid #dc2626; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; animation: pulse-row-red 3s infinite;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.5rem;">🚨</span>
                        <div>
                            <h4 style="margin: 0; color: #991b1b; font-weight: bold;">EXPIRED BATCHES ALERT</h4>
                            <p style="margin: 0.25rem 0 0 0; color: #7f1d1d;">${expiredBatches.length} batch(es) have expired and need immediate attention!</p>
                            <div style="margin-top: 0.5rem;">
                                ${expiredBatches.map(batch => `
                                    <span style="background: #dc2626; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-right: 0.5rem; display: inline-block;">
                                        ${batch.batch_number} - ${batch.product ? batch.product.name : 'Unknown Product'}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        if (expiringSoonBatches.length > 0) {
            alertsHtml += `
                <div style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 2px solid #f59e0b; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; animation: pulse-row-orange 3s infinite;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.5rem;">⚠️</span>
                        <div>
                            <h4 style="margin: 0; color: #92400e; font-weight: bold;">EXPIRING SOON ALERT</h4>
                            <p style="margin: 0.25rem 0 0 0; color: #78350f;">${expiringSoonBatches.length} batch(es) will expire within 30 days!</p>
                            <div style="margin-top: 0.5rem;">
                                ${expiringSoonBatches.map(batch => {
                                    const expiry = new Date(batch.expiry_date);
                                    const today = new Date();
                                    const diffDays = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));
                                    return `
                                        <span style="background: #f59e0b; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-right: 0.5rem; display: inline-block;">
                                            ${batch.batch_number} - ${batch.product ? batch.product.name : 'Unknown Product'} (${diffDays} days)
                                        </span>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        if (lowStockBatches.length > 0) {
            alertsHtml += `
                <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); border: 2px solid #f59e0b; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; animation: pulse-row-yellow 3s infinite;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.5rem;">📉</span>
                        <div>
                            <h4 style="margin: 0; color: #92400e; font-weight: bold;">LOW STOCK ALERT</h4>
                            <p style="margin: 0.25rem 0 0 0; color: #78350f;">${lowStockBatches.length} batch(es) have low stock levels and may need restocking!</p>
                            <div style="margin-top: 0.5rem;">
                                ${lowStockBatches.map(batch => {
                                    const stockPercentage = ((batch.quantity_remaining / batch.quantity_received) * 100).toFixed(1);
                                    return `
                                        <span style="background: #f59e0b; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-right: 0.5rem; display: inline-block;">
                                            ${batch.batch_number} - ${batch.product ? batch.product.name : 'Unknown Product'} (${stockPercentage}% left)
                                        </span>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        alertsContainer.innerHTML = alertsHtml;
    }

    // Display batches
    function displayBatches() {
        const container = document.getElementById('batches-container');
        const productFilter = document.getElementById('batch-product-filter').value;
        const statusFilter = document.getElementById('batch-status-filter').value;
        const searchTerm = document.getElementById('batch-search').value.toLowerCase();

        let filteredBatches = batches.filter(batch => {
            const matchesProduct = !productFilter || batch.product_id == productFilter;
            const matchesStatus = !statusFilter || getBatchStatus(batch) === statusFilter;
            const matchesSearch = !searchTerm || 
                batch.batch_number.toLowerCase().includes(searchTerm) ||
                (batch.product && batch.product.name.toLowerCase().includes(searchTerm)) ||
                (batch.supplier && batch.supplier.name.toLowerCase().includes(searchTerm));
            
            return matchesProduct && matchesStatus && matchesSearch;
        });

        if (filteredBatches.length === 0) {
            container.innerHTML = '<div class="no-data">No batches found</div>';
            return;
        }

        let html = `
            <table class="table" style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 12%;">Batch No</th>
                        <th style="width: 15%;">Product</th>
                        <th style="width: 12%;">Supplier</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 10%;">Purchase ₹</th>
                        <th style="width: 10%;">Selling ₹</th>
                        <th style="width: 10%;">Prod Date</th>
                        <th style="width: 12%;">Expiry Date</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 8%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        filteredBatches.forEach(batch => {
            const status = getBatchStatus(batch);
            const statusClass = getStatusClass(status);
            const statusText = getStatusText(status);
            const productionDate = batch.production_date ? new Date(batch.production_date).toLocaleDateString() : 'N/A';
            const expiryDate = batch.expiry_date ? new Date(batch.expiry_date).toLocaleDateString() : 'N/A';
            
            // Calculate days until expiry
            let daysUntilExpiry = '';
            let expiryAlert = '';
            if (batch.expiry_date) {
                const expiry = new Date(batch.expiry_date);
                const today = new Date();
                const diffTime = expiry - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays < 0) {
                    daysUntilExpiry = `Expired ${Math.abs(diffDays)} days ago`;
                    expiryAlert = '⚠️ EXPIRED';
                } else if (diffDays === 0) {
                    daysUntilExpiry = 'Expires today!';
                    expiryAlert = '🚨 EXPIRES TODAY';
                } else if (diffDays <= 30) {
                    daysUntilExpiry = `Expires in ${diffDays} days`;
                    expiryAlert = '⚠️ EXPIRING SOON';
                } else {
                    daysUntilExpiry = `Expires in ${diffDays} days`;
                }
            }
            
            // Determine row class for highlighting
            let rowClass = '';
            if (status === 'expired') {
                rowClass = 'batch-row-expired';
            } else if (status === 'expiring_soon') {
                rowClass = 'batch-row-expiring-soon';
            }
            
            console.log(`Batch ${batch.batch_number}: Status=${status}, Class=${statusClass}, Text=${statusText}, Days=${daysUntilExpiry}`);
            
            html += `
                <tr class="${rowClass}">
                    <td style="overflow: hidden; text-overflow: ellipsis;">
                        <div style="font-weight: bold; font-size: 0.75rem;">${batch.batch_number}</div>
                        ${expiryAlert ? `<div style="color: #dc2626; font-weight: bold; font-size: 0.65rem; margin-top: 2px;">${expiryAlert}</div>` : ''}
                    </td>
                    <td style="overflow: hidden; text-overflow: ellipsis;" title="${batch.product ? batch.product.name : 'N/A'}">
                        <div style="font-size: 0.75rem;">${batch.product ? batch.product.name : 'N/A'}</div>
                    </td>
                    <td style="overflow: hidden; text-overflow: ellipsis;" title="${batch.supplier ? batch.supplier.name : 'N/A'}">
                        <div style="font-size: 0.75rem;">${batch.supplier ? batch.supplier.name : 'N/A'}</div>
                    </td>
                    <td style="text-align: center; font-weight: bold; font-size: 0.75rem;">${batch.quantity_remaining || 0}</td>
                    <td style="text-align: right; font-size: 0.75rem;">₹${parseFloat(batch.purchase_price || 0).toFixed(2)}</td>
                    <td style="text-align: right; font-size: 0.75rem;">₹${parseFloat(batch.selling_price || 0).toFixed(2)}</td>
                    <td style="text-align: center; font-size: 0.7rem;">${productionDate}</td>
                    <td style="text-align: center; font-size: 0.7rem;">
                        <div>${expiryDate}</div>
                        ${daysUntilExpiry ? `<div style="color: ${status === 'expired' ? '#dc2626' : status === 'expiring_soon' ? '#f59e0b' : '#6b7280'}; font-weight: ${status === 'expired' || status === 'expiring_soon' ? 'bold' : 'normal'}; font-size: 0.65rem; margin-top: 2px;">${daysUntilExpiry}</div>` : ''}
                    </td>
                    <td style="text-align: center;">
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-primary" onclick="openBatchActionsModal(${batch.id})" data-batch-id="${batch.id}" style="padding: 0.25rem 0.5rem; font-size: 0.65rem;">Actions</button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
        
        // Display expiry alerts after showing batches
        displayExpiryAlerts();
    }

    // Get batch status with enhanced expiry detection
    function getBatchStatus(batch) {
        console.log('=== CALCULATING BATCH STATUS ===');
        console.log('Batch:', batch.batch_number);
        console.log('Quantity remaining:', batch.quantity_remaining);
        console.log('Expiry date:', batch.expiry_date);
        console.log('Current date:', new Date().toISOString());
        
        if (batch.quantity_remaining <= 0) {
            console.log('Status: OUT OF STOCK');
            return 'out_of_stock';
        }
        
        if (batch.expiry_date) {
            const expiryDate = new Date(batch.expiry_date);
            const currentDate = new Date();
            const oneMonthFromNow = new Date();
            oneMonthFromNow.setMonth(oneMonthFromNow.getMonth() + 1);
            
            console.log('Expiry date parsed:', expiryDate.toISOString());
            console.log('One month from now:', oneMonthFromNow.toISOString());
            console.log('Is expired?', expiryDate < currentDate);
            console.log('Is expiring soon?', expiryDate <= oneMonthFromNow && expiryDate > currentDate);
            
            if (expiryDate < currentDate) {
                console.log('Status: EXPIRED');
                return 'expired';
            }
            
            if (expiryDate <= oneMonthFromNow && expiryDate > currentDate) {
                console.log('Status: EXPIRING_SOON');
                return 'expiring_soon';
            }
        }
        
        if (batch.quantity_remaining <= (batch.quantity_received * 0.1)) {
            console.log('Status: LOW STOCK');
            return 'low_stock';
        }
        
        console.log('Status: ACTIVE');
        return 'active';
    }

    // Get status class
    function getStatusClass(status) {
        switch(status) {
            case 'active': return 'status-active';
            case 'expired': return 'status-expired';
            case 'expiring_soon': return 'status-expiring-soon';
            case 'low_stock': return 'status-warning';
            case 'out_of_stock': return 'status-inactive';
            default: return 'status-active';
        }
    }

    // Get status text
    function getStatusText(status) {
        switch(status) {
            case 'active': return 'Active';
            case 'expired': return 'Expired';
            case 'expiring_soon': return 'Expiring Soon';
            case 'low_stock': return 'Low Stock';
            case 'out_of_stock': return 'Out of Stock';
            default: return 'Active';
        }
    }

    // Populate batch selects
    function populateBatchSelects() {
        // Populate product filter
        const productFilter = document.getElementById('batch-product-filter');
        productFilter.innerHTML = '<option value="">All Products</option>';
        products.forEach(product => {
            productFilter.innerHTML += `<option value="${product.id}">${product.name}</option>`;
        });

        // Populate batch form product select
        const batchProductSelect = document.getElementById('batch-product');
        batchProductSelect.innerHTML = '<option value="">Select Product</option>';
        products.forEach(product => {
            batchProductSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
        });

        // Populate supplier selects
        const supplierSelect = document.getElementById('batch-supplier');
        supplierSelect.innerHTML = '<option value="">Select Supplier (Optional)</option>';
        suppliers.forEach(supplier => {
            supplierSelect.innerHTML += `<option value="${supplier.id}">${supplier.name}</option>`;
        });

        const editSupplierSelect = document.getElementById('edit-batch-supplier');
        editSupplierSelect.innerHTML = '<option value="">Select Supplier (Optional)</option>';
        suppliers.forEach(supplier => {
            editSupplierSelect.innerHTML += `<option value="${supplier.id}">${supplier.name}</option>`;
        });
    }

    // Filter batches
    function filterBatches() {
        displayBatches();
    }

    // Search batches
    function searchBatches() {
        displayBatches();
    }

    // Clear batch search
    function clearBatchSearch() {
        document.getElementById('batch-search').value = '';
        document.getElementById('batch-product-filter').value = '';
        document.getElementById('batch-status-filter').value = '';
        displayBatches();
    }

    // Open add batch modal
    function openAddBatchModal() {
        document.getElementById('addBatchModal').style.display = 'block';
        populateBatchSelects();
    }

    // Close add batch modal
    function closeAddBatchModal() {
        document.getElementById('addBatchModal').style.display = 'none';
        document.getElementById('batch-form').reset();
    }

    // Open edit batch modal
    function openEditBatchModal(batchId) {
        console.log('Opening edit modal for batch ID:', batchId, 'Type:', typeof batchId);
        console.log('Current batches array length:', batches ? batches.length : 0);
        console.log('Called from:', new Error().stack);
        
        // Convert batchId to number for comparison
        const numericBatchId = parseInt(batchId);
        
        // Check if batches are loaded
        if (!batches || batches.length === 0) {
            console.log('Batches not loaded, reloading...');
            loadBatches().then(() => {
                // Retry after loading
                setTimeout(() => {
                    openEditBatchModal(batchId);
                }, 500);
            });
            return;
        }
        
        // Try multiple ways to find the batch
        let batch = batches.find(b => b.id == batchId); // Loose comparison
        if (!batch) {
            batch = batches.find(b => b.id === numericBatchId); // Strict comparison
        }
        if (!batch) {
            batch = batches.find(b => parseInt(b.id) === numericBatchId); // Convert both to int
        }
        
        console.log('Found batch:', batch);
        console.log('Available batch IDs:', batches.map(b => ({ id: b.id, type: typeof b.id })));
        
        if (!batch) {
            console.error('Batch not found in local data. Fetching from server...');
            // Try to fetch the batch directly from the server
            fetch(`/inventory/batches/${batchId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(response => {
                console.log('Server response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Server response data:', data);
                if (data.success) {
                    console.log('Successfully fetched batch from server:', data.data);
                    // Use the fetched batch data directly
                    populateEditModal(data.data);
                } else {
                    console.error('Server returned error:', data);
            showAlert('Batch not found. Please refresh the page and try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error fetching batch:', error);
                showAlert('Error connecting to server. Please check your connection and try again.', 'error');
            });
            return;
        }

        populateEditModal(batch);
    }
    
    // Populate edit modal with batch data
    function populateEditModal(batch) {
        console.log('=== POPULATING EDIT MODAL ===');
        console.log('Batch data received:', batch);
        console.log('Batch ID:', batch.id);
        console.log('Batch number:', batch.batch_number);
        console.log('Product:', batch.product);
        console.log('Supplier:', batch.supplier);
        console.log('Purchase price:', batch.purchase_price);
        console.log('Selling price:', batch.selling_price);
        console.log('Production date:', batch.production_date);
        console.log('Expiry date:', batch.expiry_date);
        console.log('Notes:', batch.notes);

        // Populate basic fields
        const idField = document.getElementById('edit-batch-id');
        const productField = document.getElementById('edit-batch-product');
        const productDisplayField = document.getElementById('edit-batch-product-display');
        const batchNumberField = document.getElementById('edit-batch-number');
        const quantityField = document.getElementById('edit-batch-quantity');
        const purchasePriceField = document.getElementById('edit-batch-purchase-price');
        const sellingPriceField = document.getElementById('edit-batch-selling-price');
        const productionDateField = document.getElementById('edit-batch-production-date');
        const expiryDateField = document.getElementById('edit-batch-expiry-date');
        const notesField = document.getElementById('edit-batch-notes');

        console.log('Form fields found:');
        console.log('ID field:', idField);
        console.log('Product field:', productField);
        console.log('Batch number field:', batchNumberField);
        console.log('Quantity field:', quantityField);

        if (idField) {
            idField.value = batch.id;
            console.log('Set ID to:', idField.value);
        }

        if (productField) {
            productField.value = batch.product_id || '';
            console.log('Set product_id to:', productField.value);
        }

        if (productDisplayField) {
            productDisplayField.value = batch.product ? batch.product.name : 'Unknown Product';
            console.log('Set product display to:', productDisplayField.value);
        }

        if (batchNumberField) {
            batchNumberField.value = batch.batch_number || '';
            console.log('Set batch number to:', batchNumberField.value);
        }

        if (quantityField) {
            quantityField.value = batch.quantity_received || '';
            console.log('Set quantity to:', quantityField.value);
        }

        if (purchasePriceField) {
            purchasePriceField.value = batch.purchase_price || '';
            console.log('Set purchase price to:', purchasePriceField.value);
        }

        if (sellingPriceField) {
            sellingPriceField.value = batch.selling_price || '';
            console.log('Set selling price to:', sellingPriceField.value);
        }

        if (productionDateField) {
            // Format date for input field (YYYY-MM-DD)
            console.log('Raw production date:', batch.production_date);
            let productionDate = '';
            if (batch.production_date) {
                try {
                    // Handle different date formats
                    const date = new Date(batch.production_date);
                    if (!isNaN(date.getTime())) {
                        productionDate = date.toISOString().split('T')[0];
                    } else {
                        // If it's already in YYYY-MM-DD format
                        productionDate = batch.production_date;
                    }
                } catch (e) {
                    console.error('Error parsing production date:', e);
                    productionDate = batch.production_date;
                }
            }
            productionDateField.value = productionDate;
            console.log('Set production date to:', productionDateField.value);
        }

        if (expiryDateField) {
            // Format date for input field (YYYY-MM-DD)
            console.log('Raw expiry date:', batch.expiry_date);
            let expiryDate = '';
            if (batch.expiry_date) {
                try {
                    // Handle different date formats
                    const date = new Date(batch.expiry_date);
                    if (!isNaN(date.getTime())) {
                        expiryDate = date.toISOString().split('T')[0];
                    } else {
                        // If it's already in YYYY-MM-DD format
                        expiryDate = batch.expiry_date;
                    }
                } catch (e) {
                    console.error('Error parsing expiry date:', e);
                    expiryDate = batch.expiry_date;
                }
            }
            expiryDateField.value = expiryDate;
            console.log('Set expiry date to:', expiryDateField.value);
        }

        if (notesField) {
            notesField.value = batch.notes || '';
            console.log('Set notes to:', notesField.value);
        }

        // Set supplier value
        setTimeout(() => {
            const supplierSelect = document.getElementById('edit-batch-supplier');
            console.log('Setting supplier value:', batch.supplier_id);
            if (supplierSelect) {
            if (supplierSelect.selectize) {
                supplierSelect.selectize.setValue(batch.supplier_id || '');
                    console.log('Set supplier via selectize to:', batch.supplier_id);
            } else {
                supplierSelect.value = batch.supplier_id || '';
                    console.log('Set supplier directly to:', supplierSelect.value);
                }
            }
        }, 100);

        console.log('Showing edit modal...');
        document.getElementById('editBatchModal').style.display = 'block';
        console.log('Edit modal display style:', document.getElementById('editBatchModal').style.display);
    }

    // Close edit batch modal
    function closeEditBatchModal() {
        document.getElementById('editBatchModal').style.display = 'none';
        document.getElementById('edit-batch-form').reset();
    }

    // Open adjust stock modal
    function openAdjustStockModal(batchId) {
        // Check if batches are loaded
        if (!batches || batches.length === 0) {
            showAlert('Batches are still loading. Please wait a moment and try again.', 'error');
            return;
        }
        
        const batch = batches.find(b => b.id == batchId); // Use == for type coercion
        if (!batch) {
            showAlert('Batch not found. Please refresh the page and try again.', 'error');
            return;
        }

        document.getElementById('adjust-batch-id').value = batch.id;
        document.getElementById('adjust-batch-product').value = batch.product ? batch.product.name : 'Unknown Product';
        document.getElementById('adjust-batch-number').value = batch.batch_number;
        document.getElementById('adjust-current-stock').value = batch.quantity_remaining || 0;

        document.getElementById('adjustStockModal').style.display = 'block';
    }

    // Close adjust stock modal
    function closeAdjustStockModal() {
        document.getElementById('adjustStockModal').style.display = 'none';
        document.getElementById('adjust-stock-form').reset();
    }

    // Add batch
    function addBatch() {
        const formData = new FormData(document.getElementById('batch-form'));
        
        fetch('/inventory/batches', {
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
                showAlert('Batch created successfully', 'success');
                closeAddBatchModal();
                loadBatches();
                loadBatchStats();
            } else {
                showAlert(formatErrorMessage(data, 'Error creating batch'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error creating batch', 'error');
        });
    }

    // Update batch
    function updateBatch() {
        console.log('=== UPDATE BATCH CALLED ===');
        
        const form = document.getElementById('edit-batch-form');
        const formData = new FormData(form);
        const id = document.getElementById('edit-batch-id').value;
        
        console.log('Form element:', form);
        console.log('Batch ID:', id);
        console.log('Form data entries:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        if (!id) {
            console.error('No batch ID found!');
            showAlert('Error: No batch ID found. Please try again.', 'error');
            return;
        }
        
        console.log('Sending request to:', `/inventory/batches/${id}`);
        console.log('Request method: POST');
        console.log('CSRF token:', token);
        
        fetch(`/inventory/batches/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Batch updated successfully');
                showAlert('Batch updated successfully', 'success');
                closeEditBatchModal();
                loadBatches();
                loadBatchStats();
            } else {
                console.error('Update failed:', data);
                showAlert(formatErrorMessage(data, 'Error updating batch'), 'error');
            }
        })
        .catch(error => {
            console.error('=== UPDATE BATCH ERROR ===');
            console.error('Error type:', error.name);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            showAlert('Error updating batch: ' + error.message, 'error');
        });
    }

    // Adjust stock
    function adjustStock() {
        console.log('=== ADJUST STOCK CALLED ===');
        
        const form = document.getElementById('adjust-stock-form');
        const formData = new FormData(form);
        const id = document.getElementById('adjust-batch-id').value;
        
        console.log('Form element:', form);
        console.log('Batch ID:', id);
        console.log('Form data entries:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        if (!id) {
            console.error('No batch ID found!');
            showAlert('Error: No batch ID found. Please try again.', 'error');
            return;
        }
        
        console.log('Sending request to:', `/inventory/batches/${id}/adjust-stock`);
        console.log('Request method: POST');
        console.log('CSRF token:', token);
        
        fetch(`/inventory/batches/${id}/adjust-stock`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Stock adjusted successfully');
                showAlert('Stock adjusted successfully', 'success');
                closeAdjustStockModal();
                loadBatches();
                loadBatchStats();
            } else {
                console.error('Adjust stock failed:', data);
                showAlert(formatErrorMessage(data, 'Error adjusting stock'), 'error');
            }
        })
        .catch(error => {
            console.error('=== ADJUST STOCK ERROR ===');
            console.error('Error type:', error.name);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            showAlert('Error adjusting stock: ' + error.message, 'error');
        });
    }

    // Delete batch
    function deleteBatch(id) {
        console.log('=== DELETE BATCH CALLED ===');
        console.log('Batch ID:', id);
        
        // Find the batch to check its stock
        const batch = batches.find(b => b.id == id);
        if (batch) {
            console.log('Batch found:', batch);
            console.log('Quantity remaining:', batch.quantity_remaining);
            
            if (batch.quantity_remaining > 0) {
                const confirmMessage = `This batch has ${batch.quantity_remaining} units remaining. Are you sure you want to delete it? You may need to adjust stock first.`;
                if (!confirm(confirmMessage)) {
                    return;
                }
            } else {
                if (!confirm('Are you sure you want to delete this batch?')) {
                    return;
                }
            }
        } else {
            if (!confirm('Are you sure you want to delete this batch?')) {
                return;
            }
        }
        
        console.log('Proceeding with deletion...');
        
            fetch(`/inventory/batches/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
        .then(response => {
            console.log('Delete response status:', response.status);
            console.log('Delete response ok:', response.ok);
            return response.json();
        })
            .then(data => {
            console.log('Delete response data:', data);
                if (data.success) {
                console.log('Batch deleted successfully');
                    showAlert('Batch deleted successfully', 'success');
                    loadBatches();
                    loadBatchStats();
                } else {
                console.error('Delete failed:', data);
                const errorMessage = data.message || 'Error deleting batch';
                showAlert(errorMessage, 'error');
                }
            })
            .catch(error => {
            console.error('=== DELETE BATCH ERROR ===');
            console.error('Error type:', error.name);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            showAlert('Error deleting batch: ' + error.message, 'error');
        });
    }

    // Export batches
    function exportBatches() {
        // Simple CSV export
        let csv = 'Batch Number,Product,Supplier,Quantity,Purchase Price,Selling Price,Production Date,Expiry Date,Status\n';
        
        batches.forEach(batch => {
            const status = getBatchStatus(batch);
            const statusText = getStatusText(status);
            const productionDate = batch.production_date ? new Date(batch.production_date).toLocaleDateString() : 'N/A';
            const expiryDate = batch.expiry_date ? new Date(batch.expiry_date).toLocaleDateString() : 'N/A';
            
            csv += `"${batch.batch_number}","${batch.product ? batch.product.name : 'N/A'}","${batch.supplier ? batch.supplier.name : 'N/A'}","${batch.quantity_remaining || 0}","${batch.purchase_price || 0}","${batch.selling_price || 0}","${productionDate}","${expiryDate}","${statusText}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'batches.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // Batch Actions Modal Functions
    let currentBatchId = null;
    let currentBatchData = null;

    function openBatchActionsModal(batchId) {
        console.log('=== OPENING BATCH ACTIONS MODAL ===');
        console.log('Batch ID:', batchId, 'Type:', typeof batchId);
        console.log('Current batches array:', batches);
        console.log('Current batch data before fetch:', currentBatchData);
        
        // Show loading message
        showAlert('Loading batch data...', 'info');
        
        // Always fetch the batch data directly from the server for reliability
        console.log('Fetching batch data from server...');
        console.log('Request URL:', `/inventory/batches/${batchId}`);
        console.log('Request headers:', {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        });
        
        fetch(`/inventory/batches/${batchId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => {
            console.log('=== SERVER RESPONSE ===');
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('=== PARSED RESPONSE DATA ===');
            console.log('Full response data:', data);
            console.log('Data success:', data.success);
            console.log('Data data:', data.data);
            
            if (data.success && data.data) {
                console.log('Successfully fetched batch from server:', data.data);
                console.log('Batch ID from server:', data.data.id);
                console.log('Batch number from server:', data.data.batch_number);
                console.log('Product from server:', data.data.product);
                
                // Clear loading message
                const alertElement = document.querySelector('.alert');
                if (alertElement) {
                    alertElement.remove();
                    console.log('Removed loading alert');
                }
                
                // Store the batch data globally
                currentBatchData = data.data;
                console.log('Stored currentBatchData:', currentBatchData);
                
                // Use the fetched batch data directly
                populateBatchActionsModal(data.data, batchId);
            } else {
                console.error('Server returned error or no data:', data);
            showAlert('Batch not found. Please refresh the page and try again.', 'error');
            }
        })
        .catch(error => {
            console.error('=== FETCH ERROR ===');
            console.error('Error type:', error.name);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            showAlert('Error connecting to server. Please check your connection and try again.', 'error');
        });
    }
    
    // Populate batch actions modal with batch data
    function populateBatchActionsModal(batch, batchId) {
        console.log('Populating batch actions modal with batch:', batch, 'batchId:', batchId);

        currentBatchId = batchId;
        currentBatchData = batch; // Store the batch data globally
        console.log('Set currentBatchId to:', currentBatchId);
        console.log('Set currentBatchData to:', currentBatchData);
        
        // Populate batch info
        document.getElementById('batch-actions-info').textContent = 
            `${batch.product ? batch.product.name : 'Unknown Product'} - ${batch.batch_number}`;
        document.getElementById('batch-actions-stock').textContent = 
            `${batch.quantity_remaining || 0}/${batch.quantity_received || 0}`;
        
        const status = getBatchStatus(batch);
        const statusText = getStatusText(status);
        document.getElementById('batch-actions-status').textContent = statusText;

        document.getElementById('batchActionsModal').style.display = 'block';
    }

    function closeBatchActionsModal() {
        document.getElementById('batchActionsModal').style.display = 'none';
        currentBatchId = null;
        currentBatchData = null;
    }

    function editBatchFromActions() {
        console.log('=== EDIT BATCH FROM ACTIONS CALLED ===');
        console.log('Current batch ID:', currentBatchId);
        console.log('Current batch data:', currentBatchData);
        
        // Store the data before closing the modal (which clears it)
        const batchDataToEdit = currentBatchData;
        const batchIdToEdit = currentBatchId;
        
        console.log('Stored batch data for editing:', batchDataToEdit);
        console.log('Stored batch ID for editing:', batchIdToEdit);
        
        closeBatchActionsModal();
        
        // Use the stored batch data if available
        if (batchDataToEdit) {
            console.log('Using stored batch data for edit');
            populateEditModal(batchDataToEdit);
            return;
        }
        
        // If no stored data, try to find in local data as fallback
        console.error('No stored batch data available, trying local data...');
        const fallbackBatch = findBatchInLocalData(batchIdToEdit);
        if (fallbackBatch) {
            console.log('Found batch in local data as fallback:', fallbackBatch);
            populateEditModal(fallbackBatch);
            return;
        }
        
        // If all else fails, show error
        console.error('No batch data available for editing from any source');
        showAlert('Batch data not available. Please try again.', 'error');
    }

    function adjustStockFromActions() {
        console.log('=== ADJUST STOCK FROM ACTIONS CALLED ===');
        console.log('Current batch ID:', currentBatchId);
        console.log('Current batch data:', currentBatchData);
        
        // Store the data before closing the modal (which clears it)
        const batchIdToAdjust = currentBatchId;
        
        closeBatchActionsModal();
        openAdjustStockModal(batchIdToAdjust);
    }

    function deleteBatchFromActions() {
        console.log('=== DELETE BATCH FROM ACTIONS CALLED ===');
        console.log('Current batch ID:', currentBatchId);
        console.log('Current batch data:', currentBatchData);
        
        // Store the data before closing the modal (which clears it)
        const batchIdToDelete = currentBatchId;
        
        closeBatchActionsModal();
        deleteBatch(batchIdToDelete);
    }

    // View Batch Details Functions
    function viewBatchDetailsFromActions() {
        console.log('=== VIEW BATCH DETAILS CALLED ===');
        console.log('Current batch data:', currentBatchData);
        console.log('Current batch ID:', currentBatchId);
        console.log('Batch details modal element:', document.getElementById('batchDetailsModal'));
        console.log('Modal element exists:', !!document.getElementById('batchDetailsModal'));
        
        // Store the data before closing the modal (which clears it)
        const batchDataToView = currentBatchData;
        const batchIdToView = currentBatchId;
        
        console.log('Stored batch data for viewing:', batchDataToView);
        console.log('Stored batch ID for viewing:', batchIdToView);
        
        closeBatchActionsModal();
        
        if (batchDataToView) {
            console.log('Batch data available, populating modal...');
            console.log('Batch data details:', {
                id: batchDataToView.id,
                batch_number: batchDataToView.batch_number,
                product: batchDataToView.product,
                quantity_received: batchDataToView.quantity_received,
                quantity_remaining: batchDataToView.quantity_remaining
            });
            populateBatchDetailsModal(batchDataToView);
        } else {
            console.error('=== NO BATCH DATA AVAILABLE ===');
            console.error('Current batch data is null/undefined');
            console.error('Trying to find batch in local data as fallback...');
            
            // Try to find the batch in local data as fallback
            const fallbackBatch = findBatchInLocalData(batchIdToView);
            if (fallbackBatch) {
                console.log('Found batch in local data as fallback:', fallbackBatch);
                populateBatchDetailsModal(fallbackBatch);
            } else {
                console.error('Batch not found in local data either');
                showAlert('Batch data not available. Please try again.', 'error');
            }
        }
    }

    // Helper function to find batch in local data
    function findBatchInLocalData(batchId) {
        console.log('Searching for batch in local data, ID:', batchId);
        console.log('Available batches:', batches);
        
        if (!batches || batches.length === 0) {
            console.log('No batches in local data');
            return null;
        }
        
        // Try multiple ways to find the batch
        let batch = batches.find(b => b.id == batchId);
        if (!batch) {
            batch = batches.find(b => b.id === parseInt(batchId));
        }
        if (!batch) {
            batch = batches.find(b => parseInt(b.id) === parseInt(batchId));
        }
        
        console.log('Found batch in local data:', batch);
        return batch;
    }

    function populateBatchDetailsModal(batch) {
        console.log('Populating batch details modal with:', batch);
        
        const content = `
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="detail-section">
                    <h4 style="color: #0ea5e9; margin-bottom: 1rem; border-bottom: 2px solid #0ea5e9; padding-bottom: 0.5rem;">Basic Information</h4>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Batch Number:</strong> ${batch.batch_number || 'N/A'}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Product:</strong> ${batch.product ? batch.product.name : 'Unknown Product'}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Supplier:</strong> ${batch.supplier ? batch.supplier.name : 'No Supplier'}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Supplier Batch Number:</strong> ${batch.supplier_batch_number || 'N/A'}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Status:</strong> <span class="status-badge ${getStatusClass(getBatchStatus(batch))}">${getStatusText(getBatchStatus(batch))}</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 style="color: #10b981; margin-bottom: 1rem; border-bottom: 2px solid #10b981; padding-bottom: 0.5rem;">Stock Information</h4>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Quantity Received:</strong> ${batch.quantity_received || 0} units
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Quantity Remaining:</strong> ${batch.quantity_remaining || 0} units
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Stock Percentage:</strong> ${((batch.quantity_remaining / batch.quantity_received) * 100).toFixed(1)}%
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Units Sold:</strong> ${(batch.quantity_received - batch.quantity_remaining)} units
                    </div>
                </div>

                <div class="detail-section">
                    <h4 style="color: #f59e0b; margin-bottom: 1rem; border-bottom: 2px solid #f59e0b; padding-bottom: 0.5rem;">Pricing Information</h4>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Purchase Price:</strong> ₹${parseFloat(batch.purchase_price || 0).toFixed(2)}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Selling Price:</strong> ₹${parseFloat(batch.selling_price || 0).toFixed(2)}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Profit per Unit:</strong> ₹${(parseFloat(batch.selling_price || 0) - parseFloat(batch.purchase_price || 0)).toFixed(2)}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Profit Margin:</strong> ${((parseFloat(batch.selling_price || 0) - parseFloat(batch.purchase_price || 0)) / parseFloat(batch.purchase_price || 1) * 100).toFixed(1)}%
                    </div>
                </div>

                <div class="detail-section">
                    <h4 style="color: #8b5cf6; margin-bottom: 1rem; border-bottom: 2px solid #8b5cf6; padding-bottom: 0.5rem;">Date Information</h4>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Received Date:</strong> ${batch.received_date ? new Date(batch.received_date).toLocaleDateString() : 'N/A'}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Production Date:</strong> ${batch.production_date ? new Date(batch.production_date).toLocaleDateString() : 'N/A'}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Expiry Date:</strong> ${batch.expiry_date ? new Date(batch.expiry_date).toLocaleDateString() : 'No Expiry'}
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <strong>Days Since Received:</strong> ${batch.received_date ? Math.floor((new Date() - new Date(batch.received_date)) / (1000 * 60 * 60 * 24)) : 'N/A'} days
                    </div>
                </div>
            </div>

            ${batch.notes ? `
                <div class="detail-section" style="margin-top: 1.5rem;">
                    <h4 style="color: #6b7280; margin-bottom: 1rem; border-bottom: 2px solid #6b7280; padding-bottom: 0.5rem;">Notes</h4>
                    <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; border-left: 4px solid #6b7280;">
                        ${batch.notes}
                    </div>
                </div>
            ` : ''}
        `;

        document.getElementById('batch-details-content').innerHTML = content;
        const modal = document.getElementById('batchDetailsModal');
        console.log('Setting batch details modal display to block, modal:', modal);
        modal.style.display = 'block';
        console.log('Batch details modal display style:', modal.style.display);
    }

    function closeBatchDetailsModal() {
        document.getElementById('batchDetailsModal').style.display = 'none';
    }

    // View Stock Details Functions
    function viewStockDetailsFromActions() {
        console.log('=== VIEW STOCK DETAILS CALLED ===');
        console.log('Current batch data:', currentBatchData);
        console.log('Current batch ID:', currentBatchId);
        console.log('Stock details modal element:', document.getElementById('stockDetailsModal'));
        console.log('Modal element exists:', !!document.getElementById('stockDetailsModal'));
        
        // Store the data before closing the modal (which clears it)
        const batchDataToView = currentBatchData;
        const batchIdToView = currentBatchId;
        
        console.log('Stored batch data for stock viewing:', batchDataToView);
        console.log('Stored batch ID for stock viewing:', batchIdToView);
        
        closeBatchActionsModal();
        
        if (batchDataToView) {
            console.log('Batch data available, populating stock modal...');
            console.log('Batch data details:', {
                id: batchDataToView.id,
                batch_number: batchDataToView.batch_number,
                product: batchDataToView.product,
                quantity_received: batchDataToView.quantity_received,
                quantity_remaining: batchDataToView.quantity_remaining,
                purchase_price: batchDataToView.purchase_price,
                selling_price: batchDataToView.selling_price
            });
            populateStockDetailsModal(batchDataToView);
        } else {
            console.error('=== NO BATCH DATA AVAILABLE FOR STOCK ===');
            console.error('Current batch data is null/undefined');
            console.error('Trying to find batch in local data as fallback...');
            
            // Try to find the batch in local data as fallback
            const fallbackBatch = findBatchInLocalData(batchIdToView);
            if (fallbackBatch) {
                console.log('Found batch in local data as fallback:', fallbackBatch);
                populateStockDetailsModal(fallbackBatch);
            } else {
                console.error('Batch not found in local data either');
                showAlert('Batch data not available. Please try again.', 'error');
            }
        }
    }

    function populateStockDetailsModal(batch) {
        console.log('Populating stock details modal with:', batch);
        
        const stockPercentage = ((batch.quantity_remaining / batch.quantity_received) * 100);
        const unitsSold = batch.quantity_received - batch.quantity_remaining;
        const totalValue = batch.quantity_remaining * parseFloat(batch.purchase_price || 0);
        const soldValue = unitsSold * parseFloat(batch.selling_price || 0);
        const potentialProfit = batch.quantity_remaining * (parseFloat(batch.selling_price || 0) - parseFloat(batch.purchase_price || 0));
        
        const content = `
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="stock-section">
                    <h4 style="color: #0ea5e9; margin-bottom: 1rem; border-bottom: 2px solid #0ea5e9; padding-bottom: 0.5rem;">Current Stock Status</h4>
                    <div style="background: #f0f9ff; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span><strong>Remaining Stock:</strong></span>
                            <span style="font-size: 1.25rem; font-weight: bold; color: #0ea5e9;">${batch.quantity_remaining || 0} units</span>
                        </div>
                        <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: linear-gradient(90deg, #ef4444 0%, #f59e0b 50%, #10b981 100%); height: 100%; width: ${Math.max(0, Math.min(100, stockPercentage))}%; transition: width 0.3s ease;"></div>
                        </div>
                        <div style="text-align: center; margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                            ${stockPercentage.toFixed(1)}% remaining
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="text-align: center; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: bold; color: #10b981;">${batch.quantity_received || 0}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Total Received</div>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: bold; color: #f59e0b;">${unitsSold}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Units Sold</div>
                        </div>
                    </div>
                </div>

                <div class="stock-section">
                    <h4 style="color: #10b981; margin-bottom: 1rem; border-bottom: 2px solid #10b981; padding-bottom: 0.5rem;">Financial Overview</h4>
                    <div style="background: #f0fdf4; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Current Stock Value:</span>
                            <span style="font-weight: bold; color: #10b981;">₹${totalValue.toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Revenue from Sales:</span>
                            <span style="font-weight: bold; color: #10b981;">₹${soldValue.toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Potential Profit:</span>
                            <span style="font-weight: bold; color: #10b981;">₹${potentialProfit.toFixed(2)}</span>
                        </div>
                    </div>
                    
                    <div style="background: #fef3c7; padding: 1rem; border-radius: 8px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Cost per Unit:</span>
                            <span style="font-weight: bold;">₹${parseFloat(batch.purchase_price || 0).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span>Selling Price:</span>
                            <span style="font-weight: bold;">₹${parseFloat(batch.selling_price || 0).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Profit per Unit:</span>
                            <span style="font-weight: bold; color: #10b981;">₹${(parseFloat(batch.selling_price || 0) - parseFloat(batch.purchase_price || 0)).toFixed(2)}</span>
                        </div>
                    </div>
                </div>

                <div class="stock-section">
                    <h4 style="color: #f59e0b; margin-bottom: 1rem; border-bottom: 2px solid #f59e0b; padding-bottom: 0.5rem;">Stock Analysis</h4>
                    <div style="background: #fffbeb; padding: 1rem; border-radius: 8px;">
                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Stock Level:</span>
                                <span class="status-badge ${stockPercentage > 50 ? 'status-active' : stockPercentage > 20 ? 'status-warning' : 'status-inactive'}">
                                    ${stockPercentage > 50 ? 'High' : stockPercentage > 20 ? 'Medium' : 'Low'}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Turnover Rate:</span>
                                <span style="font-weight: bold;">${((unitsSold / batch.quantity_received) * 100).toFixed(1)}%</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Days in Stock:</span>
                                <span style="font-weight: bold;">${batch.received_date ? Math.floor((new Date() - new Date(batch.received_date)) / (1000 * 60 * 60 * 24)) : 'N/A'} days</span>
                            </div>
                        </div>
                        
                        ${batch.expiry_date ? `
                            <div style="border-top: 1px solid #f3f4f6; padding-top: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Days to Expiry:</span>
                                    <span style="font-weight: bold; color: ${new Date(batch.expiry_date) < new Date() ? '#ef4444' : new Date(batch.expiry_date) < new Date(Date.now() + 30 * 24 * 60 * 60 * 1000) ? '#f59e0b' : '#10b981'}">
                                        ${Math.floor((new Date(batch.expiry_date) - new Date()) / (1000 * 60 * 60 * 24))} days
                                    </span>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        document.getElementById('stock-details-content').innerHTML = content;
        const modal = document.getElementById('stockDetailsModal');
        console.log('Setting stock details modal display to block, modal:', modal);
        modal.style.display = 'block';
        console.log('Stock details modal display style:', modal.style.display);
    }

    function closeStockDetailsModal() {
        document.getElementById('stockDetailsModal').style.display = 'none';
    }

    // Test functions for debugging
    function testBatchDetailsModal() {
        console.log('Testing batch details modal...');
        const testBatch = {
            id: 1,
            batch_number: 'TEST-001',
            product: { name: 'Test Product' },
            supplier: { name: 'Test Supplier' },
            quantity_received: 100,
            quantity_remaining: 50,
            purchase_price: 10.00,
            selling_price: 15.00,
            received_date: '2024-01-01',
            production_date: '2023-12-01',
            expiry_date: '2024-12-01',
            notes: 'Test batch for debugging'
        };
        
        populateBatchDetailsModal(testBatch);
    }

    // Test function for edit batch form submission
    function testEditBatchFormSubmission() {
        console.log('=== TESTING EDIT BATCH FORM SUBMISSION ===');
        
        // Check if form exists
        const form = document.getElementById('edit-batch-form');
        console.log('Form element:', form);
        
        if (!form) {
            console.error('Edit batch form not found!');
            return;
        }
        
        // Check if event listener is attached
        console.log('Form event listeners:', form.onclick, form.onsubmit);
        
        // Manually trigger form submission
        console.log('Manually calling updateBatch()...');
        updateBatch();
    }

    // Test function to populate edit modal with sample data
    function testEditModalPopulation() {
        console.log('=== TESTING EDIT MODAL POPULATION ===');
        
        const testBatch = {
            id: 8,
            batch_number: 'TEST-4324',
            product_id: 12,
            product: { name: 'Steel Bolts M81' },
            supplier_id: 1,
            supplier: { name: 'Test Supplier' },
            quantity_received: 100,
            quantity_remaining: 50,
            purchase_price: 25.50,
            selling_price: 35.75,
            production_date: '2024-01-15',
            expiry_date: '2025-01-15',
            received_date: '2024-01-20',
            notes: 'Test batch for debugging modal population'
        };
        
        console.log('Testing with sample data:', testBatch);
        populateEditModal(testBatch);
    }

    // Test function to check expired, expiring, and low stock batches
    function testExpiredBatches() {
        console.log('=== TESTING EXPIRED, EXPIRING, AND LOW STOCK BATCHES ===');
        console.log('Current batches:', batches);
        
        if (!batches || batches.length === 0) {
            console.log('No batches loaded');
            return;
        }
        
        const expiredBatches = batches.filter(batch => {
            const status = getBatchStatus(batch);
            return status === 'expired';
        });
        
        const expiringSoonBatches = batches.filter(batch => {
            const status = getBatchStatus(batch);
            return status === 'expiring_soon';
        });
        
        const lowStockBatches = batches.filter(batch => {
            const status = getBatchStatus(batch);
            return status === 'low_stock';
        });
        
        console.log('Found expired batches:', expiredBatches.length);
        expiredBatches.forEach(batch => {
            console.log(`Expired batch: ${batch.batch_number}, Expiry: ${batch.expiry_date}`);
        });
        
        console.log('Found expiring soon batches:', expiringSoonBatches.length);
        expiringSoonBatches.forEach(batch => {
            console.log(`Expiring soon batch: ${batch.batch_number}, Expiry: ${batch.expiry_date}`);
        });
        
        console.log('Found low stock batches:', lowStockBatches.length);
        lowStockBatches.forEach(batch => {
            const stockPercentage = ((batch.quantity_remaining / batch.quantity_received) * 100).toFixed(1);
            console.log(`Low stock batch: ${batch.batch_number}, Stock: ${stockPercentage}%`);
        });
        
        // Test with sample batches
        const testExpiredBatch = {
            batch_number: 'TEST-EXPIRED',
            quantity_remaining: 10,
            quantity_received: 100,
            expiry_date: '2023-01-01' // Past date
        };
        
        const testExpiringSoonBatch = {
            batch_number: 'TEST-EXPIRING-SOON',
            quantity_remaining: 10,
            quantity_received: 100,
            expiry_date: new Date(Date.now() + 15 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] // 15 days from now
        };
        
        const testLowStockBatch = {
            batch_number: 'TEST-LOW-STOCK',
            quantity_remaining: 5,
            quantity_received: 100,
            expiry_date: new Date(Date.now() + 60 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] // 60 days from now
        };
        
        console.log('Testing expired batch logic:');
        const testExpiredStatus = getBatchStatus(testExpiredBatch);
        console.log('Test expired batch status:', testExpiredStatus);
        
        console.log('Testing expiring soon batch logic:');
        const testExpiringStatus = getBatchStatus(testExpiringSoonBatch);
        console.log('Test expiring soon batch status:', testExpiringStatus);
        
        console.log('Testing low stock batch logic:');
        const testLowStockStatus = getBatchStatus(testLowStockBatch);
        console.log('Test low stock batch status:', testLowStockStatus);
    }

    function testStockDetailsModal() {
        console.log('Testing stock details modal...');
        const testBatch = {
            id: 1,
            batch_number: 'TEST-001',
            product: { name: 'Test Product' },
            quantity_received: 100,
            quantity_remaining: 50,
            purchase_price: 10.00,
            selling_price: 15.00,
            received_date: '2024-01-01',
            expiry_date: '2024-12-01'
        };
        
        populateStockDetailsModal(testBatch);
    }

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

    // Show alert
    function showAlert(message, type) {
        const container = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        
        container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
        
        setTimeout(() => {
            container.innerHTML = '';
        }, 5000);
    }

    // Form submission handlers
    document.getElementById('batch-form').addEventListener('submit', function(e) {
        e.preventDefault();
        addBatch();
    });

    document.getElementById('edit-batch-form').addEventListener('submit', function(e) {
        console.log('=== EDIT BATCH FORM SUBMITTED ===');
        console.log('Event:', e);
        console.log('Form element:', this);
        e.preventDefault();
        updateBatch();
    });

    document.getElementById('adjust-stock-form').addEventListener('submit', function(e) {
        e.preventDefault();
        adjustStock();
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        const addBatchModal = document.getElementById('addBatchModal');
        const editBatchModal = document.getElementById('editBatchModal');
        const adjustStockModal = document.getElementById('adjustStockModal');
        const batchActionsModal = document.getElementById('batchActionsModal');
        
        if (e.target === addBatchModal) {
            closeAddBatchModal();
        }
        if (e.target === editBatchModal) {
            closeEditBatchModal();
        }
        if (e.target === adjustStockModal) {
            closeAdjustStockModal();
        }
        if (e.target === batchActionsModal) {
            closeBatchActionsModal();
        }
    });
</script>
@endpush
