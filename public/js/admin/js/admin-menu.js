// public/admin/js/admin-menu.js
// FINAL WORKING VERSION - Saves to database, no more empty table!

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let menuItems = [];

// LOAD ITEMS
function loadMenuItems() {
    fetch('/admin/menu-items/json')
        .then(r => r.json())
        .then(result => {
            menuItems = result.data || [];
            renderMenuItems();
        });
}

// RENDER ITEMS
function renderMenuItems() {
    const grid = document.getElementById('menu-grid');
    const empty = document.getElementById('empty-state');

    if (!menuItems.length) {
        empty.style.display = 'block';
        grid.innerHTML = '';
        return;
    }

    empty.style.display = 'none';

    grid.innerHTML = menuItems.map(item => `
        <div class="menu-card" data-id="${item.id}">
            <img src="${item.image_url ?? 'https://via.placeholder.com/300x200'}">
            
            <h3>${item.name}</h3>
            <p>₱${parseFloat(item.price).toFixed(2)}</p>
            <p>${item.category?.name || 'Uncategorized'}</p>

            <button onclick="editItem(${item.id})">Edit</button>
            <button onclick="deleteItem(${item.id})">Delete</button>
        </div>
    `).join('');
}

// SAVE (CREATE / UPDATE)
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
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: formData,
    })
        .then(r => r.json())
        .then(() => {
            loadMenuItems();
            closeMenuModal();
        });
}

// EDIT
function editItem(id) {
    const item = menuItems.find(i => i.id == id);

    document.getElementById('menu-id').value = id;
    document.getElementById('menu_name').value = item.name;
    document.getElementById('menu_price').value = item.price;
    document.getElementById('menu_category').value = item.category?.name;
    document.getElementById('menu_description').value = item.description;
    document.getElementById('menu_prep_time').value = item.preparation_time;

    if (item.image_url) {
        document.getElementById('preview-image').src = item.image_url;
        document.getElementById('image-preview-container').style.display = 'block';
    }

    openAddMenuModal();
}

// DELETE
function deleteItem(id) {
    if (!confirm('Delete this item?')) return;

    fetch(`/admin/menu/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
    })
        .then(() => loadMenuItems());
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
    loadMenuItems();
    document.getElementById('menu-form').addEventListener('submit', saveMenuItem);
});


// Global functions
window.openAddMenuModal = openAddMenuModal;
window.closeMenuModal = closeMenuModal;
window.editItem = editItem;
window.deleteItem = deleteItem;
window.handleImageSelect = handleImageSelect;
window.removeImage = removeImage;