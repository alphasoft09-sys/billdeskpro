@extends('layouts.app')

@section('title', 'User Management - BillDesk Pro')
@section('page-title', 'User Management')

@section('content')
<div class="page-header">
    <h1 class="page-title">User Management</h1>
    <p class="page-subtitle">Manage system users, roles, and permissions for your team</p>
</div>

<div id="alert-container"></div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add New User</h3>
    </div>
    <form id="user-form">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="user_role">Role</label>
                <select id="user_role" name="user_role" class="form-input" required>
                    <option value="2">User</option>
                    <option value="1">Admin</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Users List</h3>
    </div>
    <div class="table-container">
        <div id="users-container">
            <div class="loading">Loading users...</div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit User</h3>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        <form id="edit-form">
            <input type="hidden" id="edit-id" name="id">
            <div class="form-group">
                <label class="form-label" for="edit-name">Full Name</label>
                <input type="text" id="edit-name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-email">Email</label>
                <input type="email" id="edit-email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-password">New Password (leave blank to keep current)</label>
                <input type="password" id="edit-password" name="password" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-user_role">Role</label>
                <select id="edit-user_role" name="user_role" class="form-input" required>
                    <option value="2">User</option>
                    <option value="1">Admin</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
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
        max-width: 500px;
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
    
    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .role-admin {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .role-user {
        background: #f0f9ff;
        color: #0369a1;
    }
</style>
@endpush

@push('scripts')
<script>
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let users = [];

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadUsers();
    });

    // Add user form submission
    document.getElementById('user-form').addEventListener('submit', function(e) {
        e.preventDefault();
        addUser();
    });

    // Edit form submission
    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        updateUser();
    });

    // Close modal when clicking X
    document.querySelector('.close').addEventListener('click', closeEditModal);

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('editModal');
        if (e.target === modal) {
            closeEditModal();
        }
    });

    function loadUsers() {
        fetch('/users', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                users = data.data;
                displayUsers(data.data);
            } else {
                showAlert('Error loading users', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error loading users', 'error');
        });
    }

    function displayUsers(users) {
        const container = document.getElementById('users-container');
        
        if (users.length === 0) {
            container.innerHTML = '<div class="loading">No users found</div>';
            return;
        }

        let html = `
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;

        users.forEach(user => {
            const roleClass = user.user_role === 1 ? 'role-admin' : 'role-user';
            const roleText = user.user_role === 1 ? 'Admin' : 'User';
            const createdDate = new Date(user.created_at).toLocaleDateString();
            
            html += `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td><span class="role-badge ${roleClass}">${roleText}</span></td>
                    <td>${createdDate}</td>
                    <td>
                        <button class="btn btn-secondary" onclick="editUser(${user.id})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteUser(${user.id})">Delete</button>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    function addUser() {
        const formData = new FormData(document.getElementById('user-form'));
        
        fetch('/users', {
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
                showAlert('User created successfully', 'success');
                document.getElementById('user-form').reset();
                loadUsers();
            } else {
                showAlert('Error: ' + JSON.stringify(data.errors), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error creating user', 'error');
        });
    }

    function editUser(id) {
        const user = users.find(u => u.id === id);
        if (user) {
            document.getElementById('edit-id').value = user.id;
            document.getElementById('edit-name').value = user.name;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-user_role').value = user.user_role;
            document.getElementById('edit-password').value = '';
            document.getElementById('editModal').style.display = 'block';
        }
    }

    function updateUser() {
        const formData = new FormData(document.getElementById('edit-form'));
        const id = document.getElementById('edit-id').value;
        
        fetch(`/users/${id}`, {
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
                showAlert('User updated successfully', 'success');
                closeEditModal();
                loadUsers();
            } else {
                showAlert('Error: ' + JSON.stringify(data.errors), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating user', 'error');
        });
    }

    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch(`/users/${id}`, {
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
                    showAlert('User deleted successfully', 'success');
                    loadUsers();
                } else {
                    showAlert('Error deleting user', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error deleting user', 'error');
            });
        }
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function showAlert(message, type) {
        const container = document.getElementById('alert-container');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        
        container.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
        
        setTimeout(() => {
            container.innerHTML = '';
        }, 5000);
    }
</script>
@endpush