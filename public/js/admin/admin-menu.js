// public/admin/js/admin-menu.js

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// GLOBAL VARIABLES
let menuItems = [];
let currentCategory = 'all'; // <--- NEW: Tracks the selected category

/* ---------------------------------------------
   LOAD MENU ITEMS
--------------------------------------------- */
function loadMenuItems() {
    fetch('/admin/menu-items/json')
        .then(r => r.json())
        .then(result => {
            menuItems = result.data || [];
            renderMenuItems(); // Initial Render
        })
        .catch(err => console.error('Error loading menu:', err));
}

/* ---------------------------------------------
   RENDER MENU ITEMS (With Filtering)
--------------------------------------------- */
function renderMenuItems() {
    const grid = document.getElementById('menu-grid');
    const empty = document.getElementById('empty-state');

    // 1. FILTER LOGIC
    let filteredItems = menuItems;
    
    if (currentCategory !== 'all') {
        // Filter by category_id (ensuring string/number comparison works)
        filteredItems = menuItems.filter(item => item.category_id == currentCategory);
    }

    // 2. EMPTY STATE
    if (!filteredItems.length) {
        if(empty) empty.style.display = 'block';
        grid.innerHTML = '';
        
        // Optional: Change empty text based on filter
        const emptyText = empty.querySelector('p');
        if(emptyText) {
             emptyText.textContent = currentCategory === 'all' 
                ? "Get started by adding your first menu item" 
                : "No items found in this category";
        }
        return;
    }

    if(empty) empty.style.display = 'none';

    // 3. RENDER CARDS
    grid.innerHTML = filteredItems.map(item => `
        <div class="menu-card" data-id="${item.id}">
            <div class="menu-card-image">
                <img src="${item.image_url ?? 'https://via.placeholder.com/600x400'}"
                     alt="${item.name}">
            </div>

            <div class="menu-card-content">
                <div class="menu-card-header">
                    <h3 class="menu-card-title">${item.name}</h3>
                    <span class="menu-card-price">₱${parseFloat(item.price).toFixed(2)}</span>
                </div>

                <p class="menu-card-description">
                    ${item.description || ''}
                </p>

                <div class="menu-card-meta">
                    <span>Category: ${item.category?.name ?? 'Uncategorized'}</span>
                    <span>Prep: ${item.preparation_time || '—'} mins</span>
                </div>

                <div class="menu-card-actions">
                    <button class="action-btn edit" onclick="editItem(${item.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="action-btn delete" onclick="deleteItem(${item.id})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

/* ---------------------------------------------
   INIT FILTERS (New Function)
--------------------------------------------- */
function initCategoryFilters() {
    const buttons = document.querySelectorAll('.category-btn');
    
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            // 1. Remove 'active' class from all buttons
            buttons.forEach(b => b.classList.remove('active'));
            
            // 2. Add 'active' class to clicked button
            this.classList.add('active');
            
            // 3. Update global variable
            currentCategory = this.getAttribute('data-category');
            
            // 4. Re-render the grid
            renderMenuItems();
        });
    });
}

/* ---------------------------------------------
   SAVE MENU ITEM
--------------------------------------------- */
function saveMenuItem(e) {
    e.preventDefault();

    const id = document.getElementById('menu-id').value;
    const formData = new FormData(e.target);

    if (id) {
        formData.append('_method', 'PUT');
    }

    const url = id ? `/admin/menu/${id}` : '/admin/menu';

    fetch(url, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        },
        body: formData,
    })
    .then(async response => {
        const json = await response.json();
        if (!response.ok) {
            throw new Error(json.message || 'Validation failed');
        }
        return json;
    })
    .then(() => {
        loadMenuItems();
        closeMenuModal();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save: ' + error.message);
    });
}

/* ---------------------------------------------
   MODAL CONTROLS
--------------------------------------------- */
function showModal() {
    const modal = document.getElementById('menu-modal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
}

function closeMenuModal() {
    const modal = document.getElementById('menu-modal');
    modal.classList.remove('show');
    setTimeout(() => (modal.style.display = 'none'), 300);
}

function openAddMenuModal() {
    document.getElementById('menu-form').reset();
    document.getElementById('menu-id').value = ""; 
    document.getElementById('modal-title').innerText = "Add Menu Item";
    removeImage();
    showModal();
}

function editItem(id) {
    const item = menuItems.find(i => i.id == id);
    if (!item) return;

    document.getElementById('menu-form').reset();
    removeImage();

    document.getElementById('modal-title').innerText = "Edit Menu Item";
    document.getElementById('menu-id').value = item.id;
    
    document.getElementById('menu_name').value = item.name;
    document.getElementById('menu_price').value = item.price;
    document.getElementById('menu_category').value = item.category_id;
    document.getElementById('menu_description').value = item.description || '';
    document.getElementById('menu_prep_time').value = item.preparation_time || '';

    if (item.image_url) {
        const preview = document.getElementById('preview-image');
        const container = document.getElementById('image-preview-container');
        preview.src = item.image_url;
        container.style.display = 'block';
    }

    showModal();
}

function deleteItem(id) {
    if (!confirm('Are you sure you want to delete this item?')) return;

    fetch(`/admin/menu/${id}`, {
        method: 'DELETE',
        headers: { 
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(() => loadMenuItems());
}

/* ---------------------------------------------
   IMAGE & DRAG/DROP
--------------------------------------------- */
function handleImageSelect(event) {
    const file = event.target.files[0];
    if (file) {
        const preview = document.getElementById("preview-image");
        const container = document.getElementById("image-preview-container");
        preview.src = URL.createObjectURL(file);
        container.style.display = "block";
    }
}

function removeImage() {
    document.getElementById("menu_image").value = "";
    document.getElementById("preview-image").src = "";
    document.getElementById("image-preview-container").style.display = "none";
}

/* ---------------------------------------------
   INIT
--------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Load Data
    loadMenuItems();
    
    // 2. Init Form Listener
    const form = document.getElementById('menu-form');
    if(form) form.addEventListener('submit', saveMenuItem);

    // 3. Init Filters (NEW)
    initCategoryFilters();
    
    // 4. Init Drag & Drop
    const dropArea = document.getElementById("image-upload-area");
    const fileInput = document.getElementById("menu_image");
    
    if (dropArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault(); 
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(() => dropArea.classList.add('drag-over'));
        ['dragleave', 'drop'].forEach(() => dropArea.classList.remove('drag-over'));

        dropArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleImageSelect({ target: { files: files } });
        });
    }
});

// Expose functions globally
window.openAddMenuModal = openAddMenuModal;
window.closeMenuModal = closeMenuModal;
window.editItem = editItem;
window.deleteItem = deleteItem;
window.handleImageSelect = handleImageSelect;
window.removeImage = removeImage;