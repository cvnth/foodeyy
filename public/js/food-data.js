// data/food-data.js
function getFoodDataById(id) {
    const foodDatabase = {
        1: {
            id: 1,
            name: "Beef Steak",
            price: "₱250",
            image: "https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80",
            rating: 4.5,
            reviews: 42,
            description: "Juicy and tender beef steak grilled to perfection. Served with roasted vegetables and your choice of sauce. Our premium cut is sourced from local farms and seasoned with a special blend of herbs.",
            prepTime: "20-25 mins",
            calories: "650 kcal",
            spiceLevel: "Mild",
            category: "Western"
        },
        2: {
            id: 2,
            name: "Chicken Teriyaki",
            price: "₱180",
            image: "https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=500&q=80",
            rating: 5,
            reviews: 56,
            description: "Grilled chicken glazed with our signature teriyaki sauce. Served with steamed rice and fresh vegetables. Our teriyaki sauce is made from a secret family recipe that has been passed down for generations.",
            prepTime: "15-20 mins",
            calories: "520 kcal",
            spiceLevel: "Mild",
            category: "Japanese"
        },
        3: {
            id: 3,
            name: "Sweet and Sour Pork",
            price: "₱210",
            image: "https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=500&q=80",
            rating: 4,
            reviews: 38,
            description: "Crispy pork pieces tossed in a tangy sweet and sour sauce with bell peppers, onions, and pineapple. This classic Chinese dish balances flavors perfectly for a satisfying meal.",
            prepTime: "25-30 mins",
            calories: "580 kcal",
            spiceLevel: "Medium",
            category: "Chinese"
        }
    };
    
    return foodDatabase[id] || foodDatabase[1];
}

function getFoodDataByName(name) {
    const foodDatabase = {
        1: {
            id: 1,
            name: "Beef Steak",
            price: "₱250",
            image: "https://images.unsplash.com/photo-1546964124-0cce460f38ef?auto=format&fit=crop&w=500&q=80",
            rating: 4.5,
            reviews: 42,
            description: "Juicy and tender beef steak grilled to perfection. Served with roasted vegetables and your choice of sauce. Our premium cut is sourced from local farms and seasoned with a special blend of herbs.",
            prepTime: "20-25 mins",
            calories: "650 kcal",
            spiceLevel: "Mild",
            category: "Western"
        },
        2: {
            id: 2,
            name: "Chicken Teriyaki",
            price: "₱180",
            image: "https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=500&q=80",
            rating: 5,
            reviews: 56,
            description: "Grilled chicken glazed with our signature teriyaki sauce. Served with steamed rice and fresh vegetables. Our teriyaki sauce is made from a secret family recipe that has been passed down for generations.",
            prepTime: "15-20 mins",
            calories: "520 kcal",
            spiceLevel: "Mild",
            category: "Japanese"
        },
        3: {
            id: 3,
            name: "Sweet and Sour Pork",
            price: "₱210",
            image: "https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=500&q=80",
            rating: 4,
            reviews: 38,
            description: "Crispy pork pieces tossed in a tangy sweet and sour sauce with bell peppers, onions, and pineapple. This classic Chinese dish balances flavors perfectly for a satisfying meal.",
            prepTime: "25-30 mins",
            calories: "580 kcal",
            spiceLevel: "Medium",
            category: "Chinese"
        }
    };
    
    // Find food by name
    for (const id in foodDatabase) {
        if (foodDatabase[id].name === name) {
            return foodDatabase[id];
        }
    }
    
    return null;
}

function generateStarRating(rating) {
    let stars = '';
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="material-icons">star</i>';
    }
    
    if (hasHalfStar) {
        stars += '<i class="material-icons">star_half</i>';
    }
    
    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="material-icons">star_border</i>';
    }
    
    return stars;
}

// Mock Data Generator for FoodHub Admin Dashboard
class MockDataGenerator {
    constructor() {
        this.products = [];
        this.users = [];
        this.orders = [];
        this.init();
    }

    init() {
        this.generateProducts();
        this.generateUsers();
        this.generateOrders();
        this.saveToLocalStorage();
    }

    generateProducts() {
        this.products = [
            {
                id: '1',
                name: 'Classic Beef Burger',
                price: '₱120',
                originalPrice: '₱150',
                category: 'Burgers',
                image: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop',
                description: 'Juicy beef patty with fresh lettuce, tomato, and special sauce',
                ingredients: ['Beef Patty', 'Lettuce', 'Tomato', 'Cheese', 'Special Sauce'],
                preparationTime: 15,
                calories: 450,
                rating: 4.5,
                reviews: 128,
                available: true,
                featured: true,
                tags: ['spicy', 'popular', 'new']
            },
            {
                id: '2',
                name: 'Margherita Pizza',
                price: '₱299',
                originalPrice: '₱350',
                category: 'Pizza',
                image: 'https://images.unsplash.com/photo-1604068549290-dea0e4a305ca?w=400&h=300&fit=crop',
                description: 'Classic pizza with fresh tomato sauce, mozzarella, and basil',
                ingredients: ['Pizza Dough', 'Tomato Sauce', 'Mozzarella', 'Basil'],
                preparationTime: 20,
                calories: 800,
                rating: 4.3,
                reviews: 89,
                available: true,
                featured: false,
                tags: ['vegetarian', 'classic']
            },
            {
                id: '3',
                name: 'Chicken Caesar Salad',
                price: '₱180',
                originalPrice: '₱200',
                category: 'Salads',
                image: 'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=400&h=300&fit=crop',
                description: 'Fresh romaine lettuce with grilled chicken, parmesan, and caesar dressing',
                ingredients: ['Romaine Lettuce', 'Grilled Chicken', 'Parmesan', 'Croutons', 'Caesar Dressing'],
                preparationTime: 10,
                calories: 320,
                rating: 4.2,
                reviews: 67,
                available: true,
                featured: true,
                tags: ['healthy', 'low-calorie']
            },
            {
                id: '4',
                name: 'Spaghetti Carbonara',
                price: '₱220',
                originalPrice: '₱250',
                category: 'Pasta',
                image: 'https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?w=400&h=300&fit=crop',
                description: 'Creamy pasta with bacon, eggs, and parmesan cheese',
                ingredients: ['Spaghetti', 'Bacon', 'Eggs', 'Parmesan', 'Cream'],
                preparationTime: 18,
                calories: 650,
                rating: 4.4,
                reviews: 94,
                available: true,
                featured: false,
                tags: ['creamy', 'italian']
            },
            {
                id: '5',
                name: 'Fish and Chips',
                price: '₱190',
                originalPrice: '₱220',
                category: 'Seafood',
                image: 'https://images.unsplash.com/photo-1579208030886-b937da7c3635?w=400&h=300&fit=crop',
                description: 'Crispy fried fish served with golden fries and tartar sauce',
                ingredients: ['White Fish', 'Batter', 'Potatoes', 'Tartar Sauce'],
                preparationTime: 25,
                calories: 720,
                rating: 4.1,
                reviews: 56,
                available: true,
                featured: false,
                tags: ['british', 'fried']
            },
            {
                id: '6',
                name: 'Chocolate Brownie',
                price: '₱90',
                originalPrice: '₱110',
                category: 'Desserts',
                image: 'https://images.unsplash.com/photo-1564355808539-22fda35bed7e?w=400&h=300&fit=crop',
                description: 'Rich chocolate brownie with walnut pieces, served warm',
                ingredients: ['Chocolate', 'Flour', 'Eggs', 'Walnuts', 'Butter'],
                preparationTime: 5,
                calories: 380,
                rating: 4.7,
                reviews: 203,
                available: true,
                featured: true,
                tags: ['sweet', 'chocolate', 'popular']
            },
            {
                id: '7',
                name: 'Iced Caramel Macchiato',
                price: '₱140',
                originalPrice: '₱160',
                category: 'Beverages',
                image: 'https://images.unsplash.com/photo-1561047029-3000c68339ca?w=400&h=300&fit=crop',
                description: 'Chilled coffee with caramel syrup and milk',
                ingredients: ['Espresso', 'Milk', 'Caramel Syrup', 'Ice'],
                preparationTime: 5,
                calories: 180,
                rating: 4.6,
                reviews: 178,
                available: true,
                featured: true,
                tags: ['coffee', 'cold', 'sweet']
            },
            {
                id: '8',
                name: 'BBQ Chicken Wings',
                price: '₱160',
                originalPrice: '₱180',
                category: 'Appetizers',
                image: 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=400&h=300&fit=crop',
                description: 'Crispy chicken wings glazed with BBQ sauce',
                ingredients: ['Chicken Wings', 'BBQ Sauce', 'Spices'],
                preparationTime: 20,
                calories: 420,
                rating: 4.3,
                reviews: 112,
                available: true,
                featured: false,
                tags: ['spicy', 'appetizer']
            }
        ];
    }

    generateUsers() {
        this.users = [
            {
                id: '1',
                name: 'Maria Santos',
                email: 'maria.santos@email.com',
                phone: '+639171234567',
                joinDate: '2024-01-15',
                lastOrder: '2024-03-15',
                totalOrders: 12,
                totalSpent: '₱4,560',
                membership: 'premium',
                status: 'active',
                avatar: 'MS',
                address: {
                    street: '123 Main Street',
                    city: 'Manila',
                    zipCode: '1000'
                },
                preferences: ['burgers', 'pizza', 'coffee']
            },
            {
                id: '2',
                name: 'Juan Dela Cruz',
                email: 'juan.dc@email.com',
                phone: '+639182345678',
                joinDate: '2024-02-01',
                lastOrder: '2024-03-14',
                totalOrders: 8,
                totalSpent: '₱2,890',
                membership: 'regular',
                status: 'active',
                avatar: 'JC',
                address: {
                    street: '456 Oak Avenue',
                    city: 'Quezon City',
                    zipCode: '1100'
                },
                preferences: ['seafood', 'salads']
            },
            {
                id: '3',
                name: 'Anna Reyes',
                email: 'anna.reyes@email.com',
                phone: '+639193456789',
                joinDate: '2024-01-20',
                lastOrder: '2024-03-10',
                totalOrders: 15,
                totalSpent: '₱6,780',
                membership: 'vip',
                status: 'active',
                avatar: 'AR',
                address: {
                    street: '789 Pine Road',
                    city: 'Makati',
                    zipCode: '1200'
                },
                preferences: ['desserts', 'pasta', 'beverages']
            },
            {
                id: '4',
                name: 'Carlos Lopez',
                email: 'carlos.lopez@email.com',
                phone: '+639104567890',
                joinDate: '2024-02-15',
                lastOrder: '2024-03-08',
                totalOrders: 5,
                totalSpent: '₱1,450',
                membership: 'regular',
                status: 'active',
                avatar: 'CL',
                address: {
                    street: '321 Elm Street',
                    city: 'Pasig',
                    zipCode: '1600'
                },
                preferences: ['burgers', 'wings']
            },
            {
                id: '5',
                name: 'Sofia Garcia',
                email: 'sofia.garcia@email.com',
                phone: '+639115678901',
                joinDate: '2024-01-10',
                lastOrder: '2024-02-28',
                totalOrders: 3,
                totalSpent: '₱890',
                membership: 'regular',
                status: 'inactive',
                avatar: 'SG',
                address: {
                    street: '654 Maple Drive',
                    city: 'Taguig',
                    zipCode: '1630'
                },
                preferences: ['pizza', 'salads']
            },
            {
                id: '6',
                name: 'Miguel Torres',
                email: 'miguel.torres@email.com',
                phone: '+639126789012',
                joinDate: '2024-03-01',
                lastOrder: '2024-03-16',
                totalOrders: 7,
                totalSpent: '₱2,340',
                membership: 'premium',
                status: 'active',
                avatar: 'MT',
                address: {
                    street: '987 Cedar Lane',
                    city: 'Mandaluyong',
                    zipCode: '1550'
                },
                preferences: ['seafood', 'appetizers']
            },
            {
                id: '7',
                name: 'Elena Mendoza',
                email: 'elena.mendoza@email.com',
                phone: '+639137890123',
                joinDate: '2024-02-20',
                lastOrder: '2024-03-12',
                totalOrders: 9,
                totalSpent: '₱3,120',
                membership: 'premium',
                status: 'suspended',
                avatar: 'EM',
                address: {
                    street: '147 Birch Avenue',
                    city: 'Paranaque',
                    zipCode: '1700'
                },
                preferences: ['desserts', 'beverages']
            },
            {
                id: '8',
                name: 'Roberto Lim',
                email: 'roberto.lim@email.com',
                phone: '+639148901234',
                joinDate: '2024-01-25',
                lastOrder: '2024-03-13',
                totalOrders: 11,
                totalSpent: '₱4,150',
                membership: 'vip',
                status: 'active',
                avatar: 'RL',
                address: {
                    street: '258 Walnut Street',
                    city: 'San Juan',
                    zipCode: '1500'
                },
                preferences: ['pizza', 'pasta', 'wings']
            }
        ];
    }

    generateOrders() {
        const statuses = ['pending', 'preparing', 'ready', 'delivered', 'picked-up', 'cancelled'];
        const paymentMethods = ['Credit Card', 'GCash', 'PayMaya', 'Cash on Delivery'];
        
        this.orders = [
            {
                id: 'ORD-001',
                customerName: 'Maria Santos',
                customerId: '1',
                customerPhone: '+639171234567',
                items: [
                    { id: '1', name: 'Classic Beef Burger', quantity: 2, price: '₱120' },
                    { id: '7', name: 'Iced Caramel Macchiato', quantity: 1, price: '₱140' }
                ],
                total: '₱380',
                status: 'delivered',
                paymentMethod: 'GCash',
                paymentStatus: 'paid',
                orderDate: '2024-03-15T14:30:00',
                deliveryAddress: {
                    street: '123 Main Street',
                    city: 'Manila',
                    zipCode: '1000'
                },
                deliveryType: 'delivery',
                estimatedDelivery: '2024-03-15T15:15:00',
                actualDelivery: '2024-03-15T15:10:00',
                specialInstructions: 'Please include extra napkins',
                riderName: 'Rider 001',
                riderRating: 5
            },
            {
                id: 'ORD-002',
                customerName: 'Juan Dela Cruz',
                customerId: '2',
                customerPhone: '+639182345678',
                items: [
                    { id: '5', name: 'Fish and Chips', quantity: 1, price: '₱190' },
                    { id: '3', name: 'Chicken Caesar Salad', quantity: 1, price: '₱180' }
                ],
                total: '₱370',
                status: 'preparing',
                paymentMethod: 'Credit Card',
                paymentStatus: 'paid',
                orderDate: '2024-03-15T13:45:00',
                deliveryAddress: {
                    street: '456 Oak Avenue',
                    city: 'Quezon City',
                    zipCode: '1100'
                },
                deliveryType: 'delivery',
                estimatedDelivery: '2024-03-15T14:45:00',
                specialInstructions: 'No mayo in the salad',
                riderName: 'Rider 002'
            },
            {
                id: 'ORD-003',
                customerName: 'Anna Reyes',
                customerId: '3',
                customerPhone: '+639193456789',
                items: [
                    { id: '2', name: 'Margherita Pizza', quantity: 1, price: '₱299' },
                    { id: '6', name: 'Chocolate Brownie', quantity: 2, price: '₱90' },
                    { id: '7', name: 'Iced Caramel Macchiato', quantity: 1, price: '₱140' }
                ],
                total: '₱619',
                status: 'ready',
                paymentMethod: 'PayMaya',
                paymentStatus: 'paid',
                orderDate: '2024-03-15T12:15:00',
                deliveryAddress: {
                    street: '789 Pine Road',
                    city: 'Makati',
                    zipCode: '1200'
                },
                deliveryType: 'pickup',
                estimatedDelivery: '2024-03-15T13:00:00',
                specialInstructions: 'Cut pizza into 8 slices'
            },
            {
                id: 'ORD-004',
                customerName: 'Carlos Lopez',
                customerId: '4',
                customerPhone: '+639104567890',
                items: [
                    { id: '1', name: 'Classic Beef Burger', quantity: 1, price: '₱120' },
                    { id: '8', name: 'BBQ Chicken Wings', quantity: 1, price: '₱160' }
                ],
                total: '₱280',
                status: 'pending',
                paymentMethod: 'Cash on Delivery',
                paymentStatus: 'pending',
                orderDate: '2024-03-15T14:00:00',
                deliveryAddress: {
                    street: '321 Elm Street',
                    city: 'Pasig',
                    zipCode: '1600'
                },
                deliveryType: 'delivery',
                estimatedDelivery: '2024-03-15T15:00:00',
                specialInstructions: 'Extra BBQ sauce for wings'
            },
            {
                id: 'ORD-005',
                customerName: 'Miguel Torres',
                customerId: '6',
                customerPhone: '+639126789012',
                items: [
                    { id: '4', name: 'Spaghetti Carbonara', quantity: 2, price: '₱220' },
                    { id: '8', name: 'BBQ Chicken Wings', quantity: 1, price: '₱160' }
                ],
                total: '₱600',
                status: 'picked-up',
                paymentMethod: 'GCash',
                paymentStatus: 'paid',
                orderDate: '2024-03-14T18:30:00',
                deliveryAddress: {
                    street: '987 Cedar Lane',
                    city: 'Mandaluyong',
                    zipCode: '1550'
                },
                deliveryType: 'pickup',
                estimatedDelivery: '2024-03-14T19:15:00',
                actualDelivery: '2024-03-14T19:10:00'
            },
            {
                id: 'ORD-006',
                customerName: 'Roberto Lim',
                customerId: '8',
                customerPhone: '+639148901234',
                items: [
                    { id: '2', name: 'Margherita Pizza', quantity: 1, price: '₱299' },
                    { id: '4', name: 'Spaghetti Carbonara', quantity: 1, price: '₱220' },
                    { id: '8', name: 'BBQ Chicken Wings', quantity: 1, price: '₱160' }
                ],
                total: '₱679',
                status: 'cancelled',
                paymentMethod: 'Credit Card',
                paymentStatus: 'refunded',
                orderDate: '2024-03-14T19:00:00',
                deliveryAddress: {
                    street: '258 Walnut Street',
                    city: 'San Juan',
                    zipCode: '1500'
                },
                deliveryType: 'delivery',
                cancellationReason: 'Customer changed mind',
                cancelledAt: '2024-03-14T19:15:00'
            },
            {
                id: 'ORD-007',
                customerName: 'Elena Mendoza',
                customerId: '7',
                customerPhone: '+639137890123',
                items: [
                    { id: '6', name: 'Chocolate Brownie', quantity: 3, price: '₱90' },
                    { id: '7', name: 'Iced Caramel Macchiato', quantity: 2, price: '₱140' }
                ],
                total: '₱550',
                status: 'delivered',
                paymentMethod: 'PayMaya',
                paymentStatus: 'paid',
                orderDate: '2024-03-14T16:45:00',
                deliveryAddress: {
                    street: '147 Birch Avenue',
                    city: 'Paranaque',
                    zipCode: '1700'
                },
                deliveryType: 'delivery',
                estimatedDelivery: '2024-03-14T17:30:00',
                actualDelivery: '2024-03-14T17:25:00',
                riderName: 'Rider 003',
                riderRating: 4
            },
            {
                id: 'ORD-008',
                customerName: 'Sofia Garcia',
                customerId: '5',
                customerPhone: '+639115678901',
                items: [
                    { id: '3', name: 'Chicken Caesar Salad', quantity: 1, price: '₱180' },
                    { id: '2', name: 'Margherita Pizza', quantity: 1, price: '₱299' }
                ],
                total: '₱479',
                status: 'preparing',
                paymentMethod: 'GCash',
                paymentStatus: 'paid',
                orderDate: '2024-03-15T11:30:00',
                deliveryAddress: {
                    street: '654 Maple Drive',
                    city: 'Taguig',
                    zipCode: '1630'
                },
                deliveryType: 'delivery',
                estimatedDelivery: '2024-03-15T12:30:00',
                specialInstructions: 'Light dressing on salad'
            }
        ];
    }

    saveToLocalStorage() {
        localStorage.setItem('products', JSON.stringify(this.products));
        localStorage.setItem('users', JSON.stringify(this.users));
        localStorage.setItem('orders', JSON.stringify(this.orders));
        
        console.log('Mock data generated and saved to localStorage:');
        console.log('- Products:', this.products.length);
        console.log('- Users:', this.users.length);
        console.log('- Orders:', this.orders.length);
    }

    // Method to get sample data for charts
    getSalesData() {
        return {
            daily: [1200, 1800, 1500, 2200, 1900, 2500, 2800],
            weekly: [12500, 14200, 13800, 15600, 14900, 16800, 17500],
            monthly: [52000, 48000, 61000, 55000, 59000, 63000],
            categories: {
                Burgers: 35,
                Pizza: 25,
                Pasta: 15,
                Salads: 10,
                Desserts: 8,
                Beverages: 7
            }
        };
    }

    // Method to get popular items
    getPopularItems() {
        return [
            { id: '1', name: 'Classic Beef Burger', sales: 156, revenue: '₱18,720' },
            { id: '7', name: 'Iced Caramel Macchiato', sales: 142, revenue: '₱19,880' },
            { id: '6', name: 'Chocolate Brownie', sales: 128, revenue: '₱11,520' },
            { id: '2', name: 'Margherita Pizza', sales: 98, revenue: '₱29,302' },
            { id: '3', name: 'Chicken Caesar Salad', sales: 76, revenue: '₱13,680' }
        ];
    }
}

// Initialize mock data when needed
function initializeMockData() {
    // Only initialize if no data exists
    const existingProducts = localStorage.getItem('products');
    const existingUsers = localStorage.getItem('users');
    const existingOrders = localStorage.getItem('orders');
    
    if (!existingProducts || !existingUsers || !existingOrders) {
        new MockDataGenerator();
        console.log('Mock data initialized successfully!');
    } else {
        console.log('Data already exists in localStorage');
    }
}

// Export for use in other files
window.MockDataGenerator = MockDataGenerator;
window.initializeMockData = initializeMockData;

// Auto-initialize when this file is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeMockData();
});