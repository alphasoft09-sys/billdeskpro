@extends('layouts.app')

@section('title', 'Dashboard - BillDesk Pro')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard Overview</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening with your hardware shop today.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number" id="total-products">0</div>
        <div class="stat-label">Products in Stock</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-number" id="total-customers">0</div>
        <div class="stat-label">Active Customers</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-number" id="total-sales">₹0</div>
        <div class="stat-label">Today's Sales</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-number" id="low-stock">0</div>
        <div class="stat-label">Low Stock Items</div>
    </div>
</div>

<div class="modules-grid">
    <a href="/users" class="module-card">
        <div class="module-title">User Management</div>
        <div class="module-desc">Manage system users, roles, and permissions for your team</div>
        <span class="module-status status-active">Active</span>
    </a>
    
    <a href="/inventory" class="module-card">
        <div class="module-title">Inventory Management</div>
        <div class="module-desc">Track products, categories, stock levels, and low stock alerts</div>
        <span class="module-status status-active">Active</span>
    </a>
    
    <a href="/sales" class="module-card">
        <div class="module-title">Sales Management</div>
        <div class="module-desc">Process sales transactions and generate professional invoices</div>
        <span class="module-status status-active">Active</span>
    </a>
    
    <a href="/purchases" class="module-card">
        <div class="module-title">Purchase Management</div>
        <div class="module-desc">Manage supplier purchases, orders, and inventory restocking</div>
        <span class="module-status status-active">Active</span>
    </a>
    
    <a href="/invoices" class="module-card">
        <div class="module-title">Invoice Management</div>
        <div class="module-desc">View, manage, and track all invoices and payment status</div>
        <span class="module-status status-active">Active</span>
    </a>
    
    <a href="/billing" class="module-card">
        <div class="module-title">Billing Interface</div>
        <div class="module-desc">Quick billing system for fast checkout and customer service</div>
        <span class="module-status status-active">Active</span>
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="/inventory" class="btn btn-primary">
            <span>📦</span>
            Add Product
        </a>
        <a href="/sales" class="btn btn-primary">
            <span>🛒</span>
            New Sale
        </a>
        <a href="/purchases" class="btn btn-primary">
            <span>📋</span>
            New Purchase
        </a>
        <a href="/billing" class="btn btn-primary">
            <span>💳</span>
            Quick Bill
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadDashboardStats() {
        const token = localStorage.getItem('auth_token');
        if (!token) return;
        
        fetch('/api/dashboard-stats', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load stats');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.stats) {
                document.getElementById('total-products').textContent = data.stats.total_products || '0';
                document.getElementById('total-customers').textContent = data.stats.total_customers || '0';
                document.getElementById('total-sales').textContent = '₹' + (data.stats.today_sales || '0');
                document.getElementById('low-stock').textContent = data.stats.low_stock_items || '0';
            } else {
                throw new Error('Invalid stats data');
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
            document.getElementById('total-products').textContent = '0';
            document.getElementById('total-customers').textContent = '0';
            document.getElementById('total-sales').textContent = '₹0';
            document.getElementById('low-stock').textContent = '0';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardStats();
    });
</script>
@endpush