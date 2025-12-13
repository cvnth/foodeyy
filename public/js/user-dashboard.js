// public/js/user-dashboard.js

let allMenuItems = [];
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('DOMContentLoaded', () => {
    fetchMenuData();
    setupSearchAndFilters();

    // CHECK SESSION MESSAGES (Passed from Blade via window.appConfig)
    if (window.appConfig && window.appConfig.session.success) {
        showNotification(window.appConfig.session.success, 'success');
    }
    if (window.appConfig && window.appConfig.session.error) {
        showNotification(window.appConfig.session.error, 'error');
    }
});

// 1. Fetch Data
function fetchMenuData() {
    fetch('/user/menu/json')
        .then(res => res.json())
        .then(data => {
            allMenuItems = data;
            renderFoodGrid(allMenuItems);
        })
        .catch(err => {
            console.error('Error:', err);
            document.getElementById('foodGrid').innerHTML = '<p style="grid-column:1/-1; text-align:center;">Failed to load menu.</p>';
        });
}

// 2. Render Grid
function renderFoodGrid(items) {
    const grid = document.getElementById('foodGrid');
    if (items.length === 0) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px;">No items found.</p>';
        return;
    }

    grid.innerHTML = items.map(item => {
        const isFav = item.is_favorited;
        const heartIcon = isFav ? 'favorite' : 'favorite_border';
        const heartClass = isFav ? 'heart-active' : '';

        return `
        <div class="food-card cursor-pointer" onclick="openFoodModal(${item.id})">
            <img src="${item.image_url || 'https://via.placeholder.com/500'}" alt="${item.name}" onerror="this.src='https://via.placeholder.com/500?text=No+Image'" />
            <div class="food-info">
                <h3>${item.name}</h3>
                <p>₱${parseFloat(item.price).toFixed(2)}</p>
                <div class="food-meta">
                    <div style="flex:1;"></div> 
                    <div>
                        <button class="favorite-btn ${heartClass}" id="fav-btn-${item.id}" onclick="toggleFavorite(event, ${item.id})">
                            <i class="material-icons">${heartIcon}</i>
                        </button>
                        <button class="add-to-cart" onclick="quickAddToCart(event, ${item.id}, '${item.name}')">
                            <i class="material-icons">add_shopping_cart</i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

// 3. Modal Logic
function openFoodModal(id) {
    const item = allMenuItems.find(i => i.id === id);
    if (!item) return;

    const modal = document.getElementById('foodDetailsModal');

    document.getElementById('detailImage').src = item.image_url || 'https://via.placeholder.com/500';
    document.getElementById('detailName').textContent = item.name;
    document.getElementById('detailPrice').textContent = '₱' + parseFloat(item.price).toFixed(2);
    document.getElementById('detailDesc').textContent = item.description || 'No description available.';
    document.getElementById('detailPrep').textContent = (item.preparation_time || '15') + ' mins';
    document.getElementById('detailCat').textContent = item.category ? item.category.name : 'General';

    modal.querySelector('.quantity').textContent = '1';
    modal.classList.remove('hidden');

    const addBtn = document.getElementById('detailAddBtn');
    const newBtn = addBtn.cloneNode(true);
    addBtn.parentNode.replaceChild(newBtn, addBtn);

    newBtn.onclick = () => {
        const qty = parseInt(modal.querySelector('.quantity').textContent);
        addToCart(item.id, qty, item.name);
        modal.classList.add('hidden');
    };
}

// 4. Favorite Logic
function toggleFavorite(e, id) {
    e.stopPropagation();
    const btn = document.getElementById(`fav-btn-${id}`);
    const icon = btn.querySelector('i');
    const isCurrentlyActive = btn.classList.contains('heart-active');

    // Optimistic UI Update
    if (isCurrentlyActive) {
        btn.classList.remove('heart-active');
        icon.textContent = 'favorite_border';
    } else {
        btn.classList.add('heart-active');
        icon.textContent = 'favorite';
    }

    fetch('/user/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({
            menu_item_id: id
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'added') {
            showNotification("Added to Favorites!", "success");
        } else if (data.status === 'removed') {
            showNotification("Removed from Favorites", "info");
        } else {
            // Revert on logic error
            if (isCurrentlyActive) {
                btn.classList.add('heart-active');
                icon.textContent = 'favorite';
            } else {
                btn.classList.remove('heart-active');
                icon.textContent = 'favorite_border';
            }
            showNotification(data.message || "Error", "error");
        }
    })
    .catch(err => {
        console.error(err);
        // Revert on connection error
        if (isCurrentlyActive) {
            btn.classList.add('heart-active');
            icon.textContent = 'favorite';
        } else {
            btn.classList.remove('heart-active');
            icon.textContent = 'favorite_border';
        }
        showNotification("Connection Error", "error");
    });
}

// 5. Cart Logic
function addToCart(id, qty, name) {
    showNotification(`Adding ${name}...`, 'info');
    fetch('/user/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({
            menu_item_id: id,
            quantity: qty
        })
    })
    .then(async res => {
        if (!res.ok) throw new Error('Failed');
        showNotification(`Added ${qty}x ${name} to cart!`, 'success');
        if (window.updateCartBadge) window.updateCartBadge();
    })
    .catch(err => showNotification('Error adding to cart', 'error'));
}

function quickAddToCart(e, id, name) {
    e.stopPropagation();
    addToCart(id, 1, name);
}

// 6. UI Helpers
function showNotification(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-gray-600'
    };
    const toast = document.createElement('div');
    toast.className = `fixed bottom-5 right-5 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-xl z-50 transition-all duration-300 translate-y-10 opacity-0`;
    toast.textContent = message;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function setupSearchAndFilters() {
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');

    searchInput.addEventListener('input', (e) => filterData(e.target.value.toLowerCase(), getActiveCategory()));
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filterData(searchInput.value.toLowerCase(), btn.dataset.category);
        });
    });
}

function getActiveCategory() {
    return document.querySelector('.filter-btn.active').dataset.category;
}

function filterData(term, cat) {
    const filtered = allMenuItems.filter(item => {
        const matchesSearch = item.name.toLowerCase().includes(term);
        const matchesCat = cat === 'all' || (item.category && item.category.name.toLowerCase() === cat.toLowerCase());
        return matchesSearch && matchesCat;
    });
    renderFoodGrid(filtered);
}

// Modal Events
const modal = document.getElementById('foodDetailsModal');
const closeBtn = document.getElementById('closeDetailsBtn');
if(closeBtn) closeBtn.onclick = () => modal.classList.add('hidden');

modal.onclick = (e) => {
    if (e.target === modal) modal.classList.add('hidden');
};
modal.addEventListener('click', e => {
    const btn = e.target.closest('.quantity-btn');
    if (!btn) return;
    const qtyEl = modal.querySelector('.quantity');
    let qty = parseInt(qtyEl.textContent);
    if (btn.dataset.action === 'plus') qty++;
    if (btn.dataset.action === 'minus' && qty > 1) qty--;
    qtyEl.textContent = qty;
});