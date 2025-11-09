@extends('layouts.app')

@section('page-title', 'Supplier Management')

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
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        background-color: #fff;
        margin: 2% auto;
        padding: 0;
        border: none;
        border-radius: 8px;
        width: 95%;
        max-width: 900px;
        max-height: 95vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        border-radius: 8px 8px 0 0;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        border-radius: 0 0 8px 8px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .close {
        color: #6b7280;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .close:hover {
        color: #374151;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from { 
            opacity: 0;
            transform: translateY(-50px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Styles */
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        background-color: #fff;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-input:required:invalid {
        border-color: #ef4444;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
    }

    /* Button Styles */
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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

    .btn-danger {
        background-color: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    /* Status Badge */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Alert Styles */
    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .alert-success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .alert-error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .modal-content {
            width: 98%;
            margin: 1% auto;
            max-height: 98vh;
        }

        .modal-body {
            padding: 1rem;
        }

        .modal-footer {
            padding: 1rem;
            flex-direction: column;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .modal-footer .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .modal-header {
            padding: 1rem;
        }

        .modal-header h3 {
            font-size: 1.125rem;
        }

        .close {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Supplier Management</h1>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="openAddSupplierModal()">
                <span>➕</span>
                Add New Supplier
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number" id="total-suppliers-count">0</div>
            <div class="stat-label">Total Suppliers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="active-suppliers-count">0</div>
            <div class="stat-label">Active Suppliers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="suppliers-with-products-count">0</div>
            <div class="stat-label">Suppliers with Products</div>
        </div>
    </div>

    <!-- Suppliers List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Suppliers List</h3>
            <div class="search-container">
                <input type="text" id="supplier-search" class="form-input" placeholder="Search suppliers..." onkeyup="searchSuppliers()">
                <button class="btn btn-secondary" onclick="clearSupplierSearch()">Clear</button>
            </div>
        </div>
        <div class="table-container">
            <div id="suppliers-container">
                <div class="loading">Loading suppliers...</div>
            </div>
        </div>
    </div>
</div>

<!-- Add Supplier Modal -->
<div id="addSupplierModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Supplier</h3>
            <span class="close" onclick="closeAddSupplierModal()">&times;</span>
        </div>
        <form id="supplier-form">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="supplier-name">Company Name *</label>
                        <input type="text" id="supplier-name" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="supplier-contact-person">Contact Person *</label>
                        <input type="text" id="supplier-contact-person" name="contact_person" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="supplier-email">Email *</label>
                        <input type="email" id="supplier-email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="supplier-phone">Phone *</label>
                        <input type="tel" id="supplier-phone" name="phone" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="supplier-gst-number">GST Number</label>
                        <input type="text" id="supplier-gst-number" name="gst_number" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="supplier-status">Status</label>
                        <select id="supplier-status" name="is_active" class="form-input">
                            <option value="">Select status...</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group full-width">
                    <label for="supplier-address">Address *</label>
                    <textarea id="supplier-address" name="address" class="form-input" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddSupplierModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Supplier</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div id="editSupplierModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Supplier</h3>
            <span class="close" onclick="closeEditSupplierModal()">&times;</span>
        </div>
        <form id="edit-supplier-form">
            <input type="hidden" id="edit-supplier-id">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-supplier-name">Company Name *</label>
                        <input type="text" id="edit-supplier-name" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-supplier-contact-person">Contact Person *</label>
                        <input type="text" id="edit-supplier-contact-person" name="contact_person" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-supplier-email">Email *</label>
                        <input type="email" id="edit-supplier-email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-supplier-phone">Phone *</label>
                        <input type="tel" id="edit-supplier-phone" name="phone" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-supplier-gst-number">GST Number</label>
                        <input type="text" id="edit-supplier-gst-number" name="gst_number" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="edit-supplier-status">Status</label>
                        <select id="edit-supplier-status" name="is_active" class="form-input">
                            <option value="">Select status...</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-group full-width">
                    <label for="edit-supplier-address">Address *</label>
                    <textarea id="edit-supplier-address" name="address" class="form-input" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditSupplierModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

<!-- Alert Container -->
<div id="alert-container"></div>

@endsection

@push('scripts')
<script>
    let suppliers = [];

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadSuppliers();
    });

    // Load suppliers
    function loadSuppliers() {
        fetch('/suppliers', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                suppliers = data.data;
                displaySuppliers();
                updateStatistics();
            } else {
                console.error('Failed to load suppliers:', data);
            }
        })
        .catch(error => {
            console.error('Error loading suppliers:', error);
        });
    }

    // Display suppliers
    function displaySuppliers(suppliersToShow = null) {
        const container = document.getElementById('suppliers-container');
        const suppliersToDisplay = suppliersToShow || suppliers;
        
        if (suppliersToDisplay.length === 0) {
            container.innerHTML = '<div class="loading">No suppliers found</div>';
            return;
        }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>GST Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        suppliersToDisplay.forEach(supplier => {
            const statusClass = supplier.is_active ? 'status-active' : 'status-inactive';
            const statusText = supplier.is_active ? 'Active' : 'Inactive';
            
            html += `
                <tr>
                    <td><strong>${supplier.name}</strong></td>
                    <td>${supplier.contact_person}</td>
                    <td>${supplier.email}</td>
                    <td>${supplier.phone}</td>
                    <td>${supplier.gst_number || 'N/A'}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <button class="btn btn-secondary" onclick="editSupplier(${supplier.id})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteSupplier(${supplier.id})">Delete</button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    // Add supplier
    function addSupplier() {
        const formData = new FormData(document.getElementById('supplier-form'));
        
        fetch('/suppliers', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                closeAddSupplierModal();
                loadSuppliers();
            } else {
                showAlert(data.message || 'Error creating supplier', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error creating supplier', 'error');
        });
    }

    // Edit supplier
    function editSupplier(supplierId) {
        const supplier = suppliers.find(s => s.id === supplierId);
        if (!supplier) {
            showAlert('Supplier not found', 'error');
            return;
        }

        // Populate form with supplier data
        document.getElementById('edit-supplier-id').value = supplier.id;
        document.getElementById('edit-supplier-name').value = supplier.name;
        document.getElementById('edit-supplier-contact-person').value = supplier.contact_person;
        document.getElementById('edit-supplier-email').value = supplier.email;
        document.getElementById('edit-supplier-phone').value = supplier.phone;
        document.getElementById('edit-supplier-gst-number').value = supplier.gst_number || '';
        document.getElementById('edit-supplier-address').value = supplier.address;
        // Wait a bit for Selectize to initialize, then set status value
        setTimeout(() => {
            const statusSelect = document.getElementById('edit-supplier-status');
            if (statusSelect.selectize) {
                statusSelect.selectize.setValue(supplier.is_active ? '1' : '0');
            } else {
                statusSelect.value = supplier.is_active ? '1' : '0';
            }
        }, 100);

        // Show modal
        document.getElementById('editSupplierModal').style.display = 'block';
    }

    // Update supplier
    function updateSupplier() {
        const formData = new FormData(document.getElementById('edit-supplier-form'));
        const id = document.getElementById('edit-supplier-id').value;
        
        fetch(`/suppliers/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                closeEditSupplierModal();
                loadSuppliers();
            } else {
                showAlert(data.message || 'Error updating supplier', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating supplier', 'error');
        });
    }

    // Delete supplier
    function deleteSupplier(supplierId) {
        if (!confirm('Are you sure you want to delete this supplier? This action cannot be undone.')) {
            return;
        }

        fetch(`/suppliers/${supplierId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                loadSuppliers();
            } else {
                showAlert(data.message || 'Error deleting supplier', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting supplier', 'error');
        });
    }

    // Update statistics
    function updateStatistics() {
        try {
            const totalSuppliers = suppliers.length;
            const activeSuppliers = suppliers.filter(s => s.is_active).length;
            const suppliersWithProducts = suppliers.filter(s => s.products_count > 0).length;

            document.getElementById('total-suppliers-count').textContent = totalSuppliers;
            document.getElementById('active-suppliers-count').textContent = activeSuppliers;
            document.getElementById('suppliers-with-products-count').textContent = suppliersWithProducts;
        } catch (error) {
            console.error('Error updating statistics:', error);
        }
    }

    // Search functions
    function searchSuppliers() {
        const searchTerm = document.getElementById('supplier-search').value.toLowerCase();
        const filteredSuppliers = suppliers.filter(supplier => 
            supplier.name.toLowerCase().includes(searchTerm) ||
            supplier.contact_person.toLowerCase().includes(searchTerm) ||
            supplier.email.toLowerCase().includes(searchTerm) ||
            supplier.phone.toLowerCase().includes(searchTerm) ||
            (supplier.gst_number && supplier.gst_number.toLowerCase().includes(searchTerm))
        );
        displaySuppliers(filteredSuppliers);
    }

    function clearSupplierSearch() {
        document.getElementById('supplier-search').value = '';
        displaySuppliers(suppliers);
    }

    // Modal functions
    function openAddSupplierModal() {
        document.getElementById('addSupplierModal').style.display = 'block';
    }

    function closeAddSupplierModal() {
        document.getElementById('addSupplierModal').style.display = 'none';
        document.getElementById('supplier-form').reset();
    }

    function closeEditSupplierModal() {
        document.getElementById('editSupplierModal').style.display = 'none';
        document.getElementById('edit-supplier-form').reset();
    }

    // Show alert
    function showAlert(message, type) {
        const container = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        
        container.innerHTML = `
            <div class="alert ${alertClass}">
                ${message}
            </div>
        `;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            container.innerHTML = '';
        }, 5000);
    }

    // Form event listeners
    document.getElementById('supplier-form').addEventListener('submit', function(e) {
        e.preventDefault();
        addSupplier();
    });

    document.getElementById('edit-supplier-form').addEventListener('submit', function(e) {
        e.preventDefault();
        updateSupplier();
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        const addSupplierModal = document.getElementById('addSupplierModal');
        const editSupplierModal = document.getElementById('editSupplierModal');
        
        // Don't close modal if clicking on Selectize elements
        if (e.target.closest('.selectize-control') || e.target.closest('.selectize-dropdown')) {
            return;
        }
        
        if (e.target === addSupplierModal) {
            closeAddSupplierModal();
        }
        if (e.target === editSupplierModal) {
            closeEditSupplierModal();
        }
    });
    
    // Initialize Selectize for supplier page
    $(document).ready(function() {
        setTimeout(function() {
            console.log('Initializing Selectize for supplier page');
            
            // Initialize status selects
            $('#supplier-status').selectize({
                sortField: 'text',
                create: false,
                allowEmptyOption: true,
                placeholder: 'Select status...'
            });
            
            $('#edit-supplier-status').selectize({
                sortField: 'text',
                create: false,
                allowEmptyOption: true,
                placeholder: 'Select status...'
            });
            
            console.log('Selectize initialization complete');
        }, 500);
    });
</script>
@endpush
