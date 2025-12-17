const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let favoritesData = []; // Store data globally to access in modal
let currentModalItemId = null; // Store currently open item ID

document.addEventListener('DOMContentLoaded', () => {
    loadFavorites();
    setupModalListeners();
});

// 1. Load Data
function loadFavorites() {
    const grid = document.getElementById('favoritesGrid');
    const loading = document.getElementById('loading');
    const empty = document.getElementById('emptyState');

    fetch('/user/favorites/json')
        .then(res => res.json())
        .then(data => {
            if(loading) loading.style.display = 'none';
            
            // Filter out items where the menu_item is null
            const validItems = data.filter(item => item.menu_item != null);
            
            // Store globally
            favoritesData = validItems;

            if (validItems.length === 0) {
                if(grid) grid.style.display = 'none';
                if(empty) empty.style.display = 'block';
                return;
            }

            if(empty) empty.style.display = 'none';
            if(grid) {
                grid.style.display = 'grid'; 
                
                grid.innerHTML = validItems.map(item => {
                    const food = item.menu_item; 

                    return `
                    <div class="food-card relative transition-all duration-300" id="card-${food.id}">
                        <div class="food-img-container" onclick="openModal(${food.id})" style="cursor:pointer">
                            <img src="${food.image_url || 'https://via.placeholder.com/500'}" 
                                 alt="${food.name}" 
                                 onerror="this.src='https://via.placeholder.com/500?text=No+Image'" />
                        </div>
                        
                        <div class="food-info">
                            <h3 onclick="openModal(${food.id})" style="cursor:pointer">${food.name}</h3>
                            <p>₱${parseFloat(food.price).toFixed(2)}</p>
                            
                            <div class="food-meta" style="justify-content: flex-end; margin-top: 10px;">
                                <div style="display:flex; gap:10px; width:100%; justify-content: flex-end;">
                                    <button class="favorite-btn" 
                                            style="color: #ef4444;"
                                            onclick="removeFavorite(${food.id})"
                                            title="Remove from favorites">
                                        <i class="material-icons">favorite</i>
                                    </button>
                                    
                                    <button class="add-to-cart" onclick="addToCart(${food.id}, '${food.name}')">
                                        <i class="material-icons">add_shopping_cart</i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `}).join('');
            }
        })
        .catch(err => console.error(err));
}

// 2. Open Modal Function
function openModal(id) {
    // Find item in the global data
    const favItem = favoritesData.find(item => item.menu_item.id === id);
    if (!favItem) return;
    
    const food = favItem.menu_item;
    currentModalItemId = food.id; // Set current ID for the add button

    // Populate Modal Elements
    document.getElementById('detailImage').src = food.image_url || 'https://via.placeholder.com/500';
    document.getElementById('detailName').textContent = food.name;
    document.getElementById('detailPrice').textContent = `₱${parseFloat(food.price).toFixed(2)}`;
    document.getElementById('detailDesc').textContent = food.description || 'No description available.';
    document.getElementById('detailPrep').textContent = (food.preparation_time || '15-20') + ' mins';
    document.getElementById('detailCat').textContent = food.category || 'General';
    
    // Reset Quantity
    document.getElementById('detailQty').textContent = '1';

    // Show Modal (Remove hidden class)
    document.getElementById('foodDetailsModal').classList.remove('hidden');
}

// 3. Modal Listeners
function setupModalListeners() {
    const modal = document.getElementById('foodDetailsModal');
    const closeBtn = document.getElementById('closeDetailsBtn');
    const qtySpan = document.getElementById('detailQty');
    const addBtn = document.getElementById('detailAddBtn');

    // Close Button
    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Close on click outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Quantity Buttons
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let current = parseInt(qtySpan.textContent);
            if (this.dataset.action === 'plus') {
                qtySpan.textContent = current + 1;
            } else if (this.dataset.action === 'minus' && current > 1) {
                qtySpan.textContent = current - 1;
            }
        });
    });

    // Add to Cart from Modal
    addBtn.addEventListener('click', function() {
        if (!currentModalItemId) return;
        const qty = parseInt(qtySpan.textContent);
        const name = document.getElementById('detailName').textContent;
        
        // Use the existing add to cart logic but with custom quantity
        addToCart(currentModalItemId, name, qty);
        
        // Close modal after adding
        modal.classList.add('hidden');
    });
}

// 4. Remove Function
function removeFavorite(id) {
    if(!confirm('Remove this item from your favorites?')) return;

    fetch('/user/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ menu_item_id: id })
    })
    .then(res => res.json())
    .then(data => {
        const card = document.getElementById(`card-${id}`);
        if(card) {
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();
                // If grid is empty
                const grid = document.getElementById('favoritesGrid');
                if(grid && grid.children.length === 0) {
                    grid.style.display = 'none';
                    document.getElementById('emptyState').style.display = 'block';
                }
            }, 300);
            showNotification("Removed from favorites", "info");
        }
    })
    .catch(err => showNotification("Error removing item", "error"));
}

// 5. Add to Cart Function (Modified to accept quantity)
function addToCart(id, name, quantity = 1) {
    showNotification(`Adding ${quantity}x ${name}...`, 'info');
    
    fetch('/user/cart/add', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': CSRF_TOKEN 
        },
        body: JSON.stringify({ 
            menu_item_id: id, 
            quantity: quantity 
        })
    })
    .then(async res => {
        if (!res.ok) throw new Error('Failed');
        showNotification(`Added ${name} to cart!`, 'success');
        if(window.parent && window.parent.updateCartBadge) {
            window.parent.updateCartBadge();
        }
    })
    .catch(err => showNotification('Error adding to cart', 'error'));
}

// 6. Notification Helper
function showNotification(message, type = 'success') {
    const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-gray-600' };
    const toast = document.createElement('div');
    toast.className = `fixed bottom-5 right-5 text-white px-6 py-3 rounded-lg shadow-xl z-[10000]`;
    
    let bgColor = '#4b5563'; 
    if(type === 'success') bgColor = '#10b981'; 
    if(type === 'error') bgColor = '#ef4444'; 
    
    toast.style.backgroundColor = bgColor;
    toast.style.transition = 'all 0.3s ease';
    toast.style.transform = 'translateY(20px)';
    toast.style.opacity = '0';
    
    toast.innerHTML = `<span class="font-medium text-sm">${message}</span>`;
    document.body.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    });

    setTimeout(() => { 
        toast.style.transform = 'translateY(20px)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300); 
    }, 3000);
}