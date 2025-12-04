@extends('admin.AdminDashboard')

@section('title', 'Manage Menu')
@section('page-title', 'Manage Menu')

@section('page-content')

<link rel="stylesheet" href="{{ asset('admin/css/admin-menu.css') }}">

<div class="menu-header">
    <div class="menu-header-info">
        <h1 class="menu-title">Manage Menu</h1>
        <p class="menu-subtitle">Add, edit, and organize your restaurant menu items</p>

        <div class="menu-stats">
            <div class="stat-item">
                <span>Total Items:</span>
                <span class="stat-badge" id="total-items">{{ $totalItems ?? 0 }}</span>
            </div>

            <div class="stat-item">
                <span>Available:</span>
                <span class="stat-badge" id="available-items">{{ $availableItems ?? 0 }}</span>
            </div>

            <div class="stat-item">
                <span>Active Categories:</span>
                <span class="stat-badge">{{ $categoriesCount ?? 0 }}</span>
            </div>
        </div>
    </div>

    <button class="add-menu-btn" onclick="openAddMenuModal()">
        <i class="material-icons">add</i>
        Add New Menu Item
    </button>
</div>

<div class="bulk-actions" id="bulk-actions">
    <span id="selected-count">0 items selected</span>

    <button class="action-btn edit" onclick="editSelectedItems()">
        <i class="fas fa-edit"></i> Edit Selected
    </button>

    <button class="action-btn delete" onclick="deleteSelectedItems()">
        <i class="fas fa-trash"></i> Delete Selected
    </button>
</div>

<div class="category-filter">
    <button class="category-btn active" data-category="all">All Items</button>
    <button class="category-btn" data-category="1">Western</button>
    <button class="category-btn" data-category="2">Chinese</button>
    <button class="category-btn" data-category="3">Japanese</button>
    <button class="category-btn" data-category="4">Filipino</button>
    <button class="category-btn" data-category="5">Desserts</button>
</div>

<div class="menu-grid" id="menu-grid"></div>

<div class="pagination" id="menu-pagination"></div>

<div class="menu-empty-state" id="empty-state" style="display: none;">
    <i class="fas fa-utensils"></i>
    <h3>No Menu Items Found</h3>
    <p>Get started by adding your first menu item</p>

    <button class="add-menu-btn" id="add-menu-item-btn">
        <i class="material-icons">add</i>
        Add New Menu Item
    </button>
</div>

{{-- MODAL --}}
<div class="modal-overlay" id="menu-modal" style="display: none;">
    <div class="modal-content">

        <div class="modal-header">
            <h3 id="modal-title">Add Menu Item</h3>
            <button type="button" class="close-modal" onclick="closeMenuModal()">&times;</button>
        </div>

        <form id="menu-form" enctype="multipart/form-data">

            <input type="hidden" id="menu-id" name="menu_id">

            <div class="modal-body">

                <div class="form-section">
                    <h4 class="form-section-title">Basic Information</h4>

                    <div class="form-grid">

                        <div class="form-group">
                            <label for="menu_name" class="label-required">Item Name</label>
                            <input
                                type="text"
                                id="menu_name"
                                name="menu_name"
                                placeholder="Enter item name"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="menu_category" class="label-required">Category</label>
                            <select id="menu_category" name="menu_category" required>
                                <option value="">Select Category</option>
                                <option value="1">Western</option>
                                <option value="2">Chinese</option>
                                <option value="3">Japanese</option>
                                <option value="4">Filipino</option>
                                <option value="5">Desserts</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="menu_price" class="label-required">Price (₱)</label>
                            <input
                                type="number"
                                id="menu_price"
                                name="menu_price"
                                placeholder="0.00"
                                min="0"
                                step="0.01"
                                required
                            >
                        </div>

                    </div>

                    <div class="form-group full-width">
                        <label for="menu_description">Description</label>
                        <textarea
                            id="menu_description"
                            name="menu_description"
                            placeholder="Describe the menu item..."
                        ></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Item Image</h4>

                    <div class="image-upload-container"
                        id="image-upload-area"
                        onclick="document.getElementById('menu_image').click()">

                        <div class="image-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>

                        <div class="image-upload-text">
                            <h4>Upload Item Image</h4>
                            <p>Drag & drop your image here or click to browse</p>
                        </div>

                        <input
                            type="file"
                            id="menu_image"
                            name="menu_image"
                            class="image-upload-input"
                            accept="image/*"
                            onchange="handleImageSelect(event)"
                        >
                    </div>

                    <div class="image-preview-container" id="image-preview-container" style="display:none;">
                        <div class="image-preview">
                            <img id="preview-image" src="" alt="">
                            <div class="image-preview-actions">
                                <button type="button" onclick="removeImage()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Additional Settings</h4>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="menu_prep_time">Preparation Time (minutes)</label>
                            <input
                                type="number"
                                id="menu_prep_time"
                                name="menu_prep_time"
                                placeholder="e.g., 15"
                                min="1"
                            >
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeMenuModal()">Cancel</button>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Save Menu Item
                </button>
            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function openAddMenuModal() {
        const modal = document.getElementById('menu-modal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeMenuModal() {
        const modal = document.getElementById('menu-modal');
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('add-menu-item-btn');
        if (btn) btn.addEventListener('click', openAddMenuModal);
    });

    window.openAddMenuModal = openAddMenuModal;
    window.closeMenuModal = closeMenuModal;
</script>

<script src="{{ asset('js/admin/admin-menu.js') }}"></script>

@endpush