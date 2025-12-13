// public/js/favorites.js

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.addEventListener('DOMContentLoaded', () => {
    loadFavorites();
});

// 1. Load Data
function loadFavorites() {
    const grid = document.getElementById('favoritesGrid');
    const loading = document.getElementById('loading');
    const empty = document.getElementById('emptyState');

    fetch('/user/favorites/json')
        .then(res => res.json())
        .then(data => {
            loading.style.display = 'none';
            
            if (data.length === 0) {
                grid.style.display = 'none';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';
            grid.style.display = 'grid'; // Ensures CSS grid applies
            
            grid.innerHTML = data.map(item => `
                <div class="food-card relative transition-all duration-300" id="card-${item.id}">
                    <img src="${item.image_url || 'https://via.placeholder.com/500'}" 
                         alt="${item.name}" 
                         onerror="this.src='https://via.placeholder.com/500?text=No+Image'" />
                    
                    <div class="food-info">
                        <h3>${item.name}</h3>
                        <p>₱${parseFloat(item.price).toFixed(2)}</p>
                        <div class="food-meta">
                            <div class="rating">
                                ${generateStars(item.rating || 5)}
                                <span>(${item.review_count || 0})</span>
                            </div>
                            <div>
                                <button class="favorite-btn" 
                                        style="color: #ef4444;"
                                        onclick="removeFavorite(${item.id})"
                                        title="Remove from favorites">
                                    <i class="material-icons">favorite</i>
                                </button>
                                
                                <button class="add-to-cart" onclick="addToCart(${item.id}, '${item.name}')">
                                    <i class="material-icons">add_shopping_cart</i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(err => console.error(err));
}

// 2. Remove Function
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
        // Animate removal
        const card = document.getElementById(`card-${id}`);
        if(card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.remove();
                // Check if grid is empty now
                if(document.getElementById('favoritesGrid').children.length === 0) {
                    document.getElementById('favoritesGrid').style.display = 'none';
                    document.getElementById('emptyState').style.display = 'block';
                }
            }, 300);
            showNotification("Removed from favorites", "info");
        }
    })
    .catch(err => showNotification("Error removing item", "error"));
}

// 3. Add to Cart Function
function addToCart(id, name) {
    showNotification(`Adding ${name}...`, 'info');
    
    fetch('/user/cart/add', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': CSRF_TOKEN 
        },
        body: JSON.stringify({ 
            menu_item_id: id, 
            quantity: 1 
        })
    })
    .then(async res => {
        if (!res.ok) throw new Error('Failed');
        showNotification(`Added ${name} to cart!`, 'success');
        // Update badge if you have one in sidebar
        if(window.parent && window.parent.updateCartBadge) {
            window.parent.updateCartBadge();
        }
    })
    .catch(err => showNotification('Error adding to cart', 'error'));
}

// 4. Notification Helper
function showNotification(message, type = 'success') {
    const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-gray-600' };
    const toast = document.createElement('div');
    toast.className = `fixed bottom-5 right-5 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-xl z-50 transition-all duration-300 translate-y-10 opacity-0`;
    toast.innerHTML = `<span class="font-medium text-sm">${message}</span>`;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
    setTimeout(() => { toast.classList.add('translate-y-10', 'opacity-0'); setTimeout(() => toast.remove(), 300); }, 3000);
}

// 5. Star Helper
function generateStars(rating) {
    let stars = '';
    for(let i=1; i<=5; i++) {
        stars += i <= rating 
            ? '<i class="material-icons" style="color:#f59e0b; font-size:16px;">star</i>' 
            : '<i class="material-icons" style="color:#d1d5db; font-size:16px;">star_border</i>';
    }
    return stars;
}