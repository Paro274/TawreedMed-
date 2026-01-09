// بيانات المنتجات مع خصم إجباري
const products = [
    {
        id: 1,
        name: 'باراسيتامول 500 ملجم',
        category: 'pain',
        subcategory: 'paracetamol',
        company: 'eva',
        price: 150,
        oldPrice: 176,
        discount: 15,
        image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=280&fit=crop',
        badge: 'جديد',
        available: true,
        rating: 4.5,
        reviews: 120
    },
    {
        id: 2,
        name: 'أموكسيسيللين 1 جم',
        category: 'antibiotics',
        subcategory: 'amoxicillin',
        company: 'pharco',
        price: 280,
        oldPrice: 350,
        discount: 20,
        image: 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=400&h=280&fit=crop',
        badge: 'خصم',
        available: true,
        rating: 4.7,
        reviews: 95
    },
    {
        id: 3,
        name: 'أوميبرازول 20 ملجم',
        category: 'stomach',
        subcategory: 'omeprazole',
        company: 'eipico',
        price: 195,
        oldPrice: 217,
        discount: 10,
        image: 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400&h=280&fit=crop',
        available: true,
        rating: 4.3,
        reviews: 78
    },
    {
        id: 4,
        name: 'ميتفورمين 500 ملجم',
        category: 'diabetes',
        subcategory: 'metformin',
        company: 'global',
        price: 220,
        oldPrice: 293,
        discount: 25,
        image: 'https://images.unsplash.com/photo-1550572017-edd951aa8f29?w=400&h=280&fit=crop',
        badge: 'مميز',
        available: true,
        rating: 4.8,
        reviews: 156
    },
    {
        id: 5,
        name: 'فيتامين د3 5000 وحدة',
        category: 'vitamins',
        subcategory: 'vitd',
        company: 'marcyrl',
        price: 180,
        oldPrice: 212,
        discount: 15,
        image: 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=400&h=280&fit=crop',
        available: true,
        rating: 4.6,
        reviews: 134
    },
    {
        id: 6,
        name: 'ايبوبروفين 400 ملجم',
        category: 'pain',
        subcategory: 'ibuprofen',
        company: 'amoun',
        price: 165,
        oldPrice: 188,
        discount: 12,
        image: 'https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=400&h=280&fit=crop',
        available: true,
        rating: 4.4,
        reviews: 89
    },
    {
        id: 7,
        name: 'أزيثروميسين 500 ملجم',
        category: 'antibiotics',
        subcategory: 'azithromycin',
        company: 'eva',
        price: 320,
        oldPrice: 390,
        discount: 18,
        image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=280&fit=crop',
        badge: 'جديد',
        available: true,
        rating: 4.5,
        reviews: 67
    },
    {
        id: 8,
        name: 'مضاد حموضة',
        category: 'stomach',
        subcategory: 'antacid',
        company: 'pharco',
        price: 85,
        oldPrice: 92,
        discount: 8,
        image: 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400&h=280&fit=crop',
        available: true,
        rating: 4.2,
        reviews: 45
    },
    {
        id: 9,
        name: 'أنسولين سريع المفعول',
        category: 'diabetes',
        subcategory: 'insulin',
        company: 'global',
        price: 450,
        oldPrice: 577,
        discount: 22,
        image: 'https://images.unsplash.com/photo-1550572017-edd951aa8f29?w=400&h=280&fit=crop',
        badge: 'مميز',
        available: false,
        rating: 4.9,
        reviews: 234
    },
    {
        id: 10,
        name: 'فيتامين سي 1000 ملجم',
        category: 'vitamins',
        subcategory: 'vitc',
        company: 'marcyrl',
        price: 95,
        oldPrice: 119,
        discount: 20,
        image: 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=400&h=280&fit=crop',
        available: true,
        rating: 4.7,
        reviews: 178
    },
    {
        id: 11,
        name: 'باراسيتامول شراب 120 مل',
        category: 'pain',
        subcategory: 'paracetamol',
        company: 'eipico',
        price: 45,
        oldPrice: 56,
        discount: 20,
        image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=280&fit=crop',
        available: true,
        rating: 4.6,
        reviews: 92
    },
    {
        id: 12,
        name: 'أموكسيسيللين شراب',
        category: 'antibiotics',
        subcategory: 'amoxicillin',
        company: 'amoun',
        price: 65,
        oldPrice: 81,
        discount: 20,
        image: 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=400&h=280&fit=crop',
        available: true,
        rating: 4.5,
        reviews: 56
    }
];

// State
let filteredProducts = [...products];
let currentPage = 1;
const productsPerPage = 12;

// عرض المنتجات
function displayProducts() {
    const grid = document.getElementById('productsGrid');
    const start = (currentPage - 1) * productsPerPage;
    const end = start + productsPerPage;
    const productsToShow = filteredProducts.slice(start, end);
    
    grid.innerHTML = productsToShow.map(product => `
        <div class="product-item" data-id="${product.id}">
            <div class="product-image-wrapper">
                <img src="${product.image}" alt="${product.name}">
                ${product.badge ? `<div class="product-badge ${product.badge === 'خصم' ? 'sale' : product.badge === 'جديد' ? 'new' : ''}">${product.badge}</div>` : ''}
                ${!product.available ? '<div class="out-of-stock">نفد من المخزون</div>' : ''}
            </div>
            <div class="product-details">
                <h3 class="product-name">${product.name}</h3>
                <div class="product-category">${getCategoryName(product.category)}</div>
                <div class="product-company">🏢 ${getCompanyName(product.company)}</div>
                <div class="product-rating">
                    <span class="stars">${'⭐'.repeat(Math.floor(product.rating))}</span>
                    <span class="rating-count">(${product.reviews})</span>
                </div>
                <div class="product-price-section">
                    <div>
                        <span class="product-price">${product.price}</span>
                        <span class="product-unit">جنيه</span>
                        ${product.oldPrice ? `<span class="old-price">${product.oldPrice}</span>` : ''}
                    </div>
                </div>
                <div class="product-discount">خصم ${product.discount}%</div>
                <div class="product-actions">
                    <button class="btn-add-cart" ${!product.available ? 'disabled' : ''}>
                        ${product.available ? 'أضف للسلة' : 'غير متوفر'}
                    </button>
                    <button class="btn-wishlist">❤</button>
                </div>
            </div>
        </div>
    `).join('');
    
    updateCounts();
}

// أسماء الفئات
function getCategoryName(cat) {
    const names = {
        'pain': 'مسكنات',
        'antibiotics': 'مضادات حيوية',
        'stomach': 'أدوية المعدة',
        'diabetes': 'أدوية السكري',
        'vitamins': 'فيتامينات'
    };
    return names[cat] || cat;
}

// أسماء الشركات
function getCompanyName(company) {
    const names = {
        'eva': 'إيفا فارما',
        'eipico': 'إيبيكو',
        'pharco': 'فاركو',
        'global': 'جلوبال نابى',
        'marcyrl': 'ماركيرل',
        'amoun': 'آمون'
    };
    return names[company] || company;
}

// تحديث العداد
function updateCounts() {
    document.getElementById('showingCount').textContent = Math.min(filteredProducts.length, productsPerPage);
    document.getElementById('totalCount').textContent = filteredProducts.length;
}

// الفلاتر
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const minPrice = parseInt(document.getElementById('minPriceSlider').value);
    const maxPrice = parseInt(document.getElementById('maxPriceSlider').value);
    
    // Categories
    const selectedCategories = Array.from(document.querySelectorAll('.filter-option input[type="checkbox"]:checked'))
        .filter(cb => cb.dataset.parent)
        .map(cb => cb.value);
    
    // Companies
    const selectedCompanies = Array.from(document.querySelectorAll('#company-eva, #company-eipico, #company-pharco, #company-global, #company-marcyrl, #company-amoun'))
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    
    // Availability
    const availableOnly = document.getElementById('available')?.checked;
    const outOfStockOnly = document.getElementById('out-stock')?.checked;
    
    filteredProducts = products.filter(product => {
        // Search
        if (searchTerm && !product.name.toLowerCase().includes(searchTerm)) return false;
        
        // Price
        if (product.price < minPrice || product.price > maxPrice) return false;
        
        // Category
        if (selectedCategories.length > 0 && !selectedCategories.includes(product.category) && !selectedCategories.includes(product.subcategory)) return false;
        
        // Company
        if (selectedCompanies.length > 0 && !selectedCompanies.includes(product.company)) return false;
        
        // Availability
        if (availableOnly && !product.available) return false;
        if (outOfStockOnly && product.available) return false;
        
        return true;
    });
    
    currentPage = 1;
    displayProducts();
}

// Price Slider
function initPriceSlider() {
    const minSlider = document.getElementById('minPriceSlider');
    const maxSlider = document.getElementById('maxPriceSlider');
    const minValue = document.getElementById('minPriceValue');
    const maxValue = document.getElementById('maxPriceValue');
    
    minSlider.addEventListener('input', (e) => {
        const value = parseInt(e.target.value);
        if (value >= parseInt(maxSlider.value)) {
            e.target.value = parseInt(maxSlider.value) - 10;
        }
        minValue.textContent = e.target.value;
        applyFilters();
    });
    
    maxSlider.addEventListener('input', (e) => {
        const value = parseInt(e.target.value);
        if (value <= parseInt(minSlider.value)) {
            e.target.value = parseInt(minSlider.value) + 10;
        }
        maxValue.textContent = e.target.value;
        applyFilters();
    });
}

// Sort
function sortProducts(sortBy) {
    switch(sortBy) {
        case 'price-low':
            filteredProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-high':
            filteredProducts.sort((a, b) => b.price - a.price);
            break;
        case 'name-asc':
            filteredProducts.sort((a, b) => a.name.localeCompare(b.name, 'ar'));
            break;
        case 'name-desc':
            filteredProducts.sort((a, b) => b.name.localeCompare(a.name, 'ar'));
            break;
        case 'rating':
            filteredProducts.sort((a, b) => b.rating - a.rating);
            break;
        case 'popular':
            filteredProducts.sort((a, b) => b.reviews - a.reviews);
            break;
    }
    displayProducts();
}

// Toggle Filters
function initFilterToggles() {
    document.querySelectorAll('.filter-title').forEach(title => {
        title.addEventListener('click', () => {
            title.parentElement.classList.toggle('collapsed');
        });
    });
    
    // Parent categories
    document.querySelectorAll('input[data-parent="true"]').forEach(checkbox => {
        checkbox.addEventListener('change', (e) => {
            const parent = e.target.parentElement.parentElement;
            const subCategories = parent.querySelector('.sub-categories');
            if (subCategories) {
                subCategories.classList.toggle('show', e.target.checked);
            }
        });
    });
}

// View Toggle
function initViewToggle() {
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            e.target.closest('.view-btn').classList.add('active');
            
            const view = e.target.closest('.view-btn').dataset.view;
            const grid = document.getElementById('productsGrid');
            
            if (view === 'list') {
                grid.classList.add('list-view');
            } else {
                grid.classList.remove('list-view');
            }
        });
    });
}

// Mobile Filter Toggle
function initMobileFilters() {
    const toggleBtn = document.querySelector('.filter-toggle-mobile');
    const sidebar = document.querySelector('.filters-sidebar');
    const overlay = document.createElement('div');
    overlay.className = 'filters-overlay';
    document.body.appendChild(overlay);
    
    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
    });
    
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    displayProducts();
    initPriceSlider();
    initFilterToggles();
    initViewToggle();
    initMobileFilters();
    
    // Search
    document.getElementById('searchInput')?.addEventListener('input', applyFilters);
    
    // Filters
    document.querySelectorAll('.filter-option input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', applyFilters);
    });
    
    // Sort
    document.getElementById('sortBy')?.addEventListener('change', (e) => {
        sortProducts(e.target.value);
    });
    
    // Clear Filters
    document.querySelector('.clear-filters')?.addEventListener('click', () => {
        document.querySelectorAll('.filter-option input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.getElementById('searchInput').value = '';
        document.getElementById('minPriceSlider').value = 0;
        document.getElementById('maxPriceSlider').value = 1000;
        document.getElementById('minPriceValue').textContent = 0;
        document.getElementById('maxPriceValue').textContent = 1000;
        applyFilters();
    });
});
