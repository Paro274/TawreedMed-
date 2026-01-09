// js/company-products.js

// Get company from URL
const urlParams = new URLSearchParams(window.location.search);
const companyId = urlParams.get('company');
const companyName = urlParams.get('name');

// All products (filter by company)
const allProductsByCompany = {
    'eva': [
        { id: 1, name: 'باراسيتامول 500 ملجم', category: 'pain', subCategory: 'paracetamol', company: 'eva', price: 150, image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=280&fit=crop', rating: 4.5, reviews: 120, available: true },
        { id: 7, name: 'ايبوبروفين 400 ملجم', category: 'pain', subCategory: 'ibuprofen', company: 'eva', price: 165, image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&h=280&fit=crop', rating: 4.6, reviews: 89, available: true }
    ],
    'pharco': [
        { id: 2, name: 'أموكسيسيللين 1 جم', category: 'antibiotics', subCategory: 'amoxicillin', company: 'pharco', price: 280, image: 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=400&h=280&fit=crop', rating: 4.8, reviews: 95, available: true },
        { id: 8, name: 'أزيثروميسين 500 ملجم', category: 'antibiotics', subCategory: 'azithromycin', company: 'pharco', price: 320, image: 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=400&h=280&fit=crop', rating: 4.7, reviews: 112, available: true }
    ],
    'eipico': [
        { id: 3, name: 'أوميبرازول 20 ملجم', category: 'stomach', subCategory: 'omeprazole', company: 'eipico', price: 195, image: 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400&h=280&fit=crop', rating: 4.6, reviews: 78, available: true }
    ],
    'global': [
        { id: 4, name: 'ميتفورمين 500 ملجم', category: 'diabetes', subCategory: 'metformin', company: 'global', price: 220, image: 'https://images.unsplash.com/photo-1550572017-edd951aa8f29?w=400&h=280&fit=crop', rating: 4.7, reviews: 156, available: true }
    ],
    'marcyrl': [
        { id: 5, name: 'فيتامين د 3 5000 وحدة', category: 'vitamins', subCategory: 'vitd', company: 'marcyrl', price: 180, image: 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=400&h=280&fit=crop', rating: 4.9, reviews: 234, available: true }
    ],
    'amoun': [
        { id: 6, name: 'أسبرين 100 ملجم', category: 'pain', subCategory: 'aspirin', company: 'amoun', price: 95, image: 'https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=400&h=280&fit=crop', rating: 4.4, reviews: 67, available: false }
    ]
};

let allProducts = allProductsByCompany[companyId] || [];
let filteredProducts = [...allProducts];
let currentView = 'grid';

const filterState = {
    search: '',
    categories: [],
    subCategories: [],
    minPrice: 0,
    maxPrice: 1000,
    availability: [],
    sortBy: 'default'
};

document.addEventListener('DOMContentLoaded', function() {
    // Update page title and header
    if (companyName) {
        document.getElementById('pageTitle').textContent = `منتجات ${companyName} - توريد ميد`;
        document.getElementById('companyName').textContent = companyName;
        document.getElementById('companyDesc').textContent = `تصفح منتجات ${companyName}`;
        document.getElementById('breadcrumbCompany').textContent = companyName;
    }
    
    renderProducts();
    initializeFilters();
    initializePriceSlider();
});

// Same functions as products.js
function renderProducts() {
    const productsGrid = document.getElementById('productsGrid');
    productsGrid.innerHTML = '';
    productsGrid.className = currentView === 'grid' ? 'products-grid' : 'products-grid list-view';

    if (filteredProducts.length === 0) {
        productsGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #64748b;"><h3>لا توجد منتجات تطابق معايير البحث</h3></div>';
        return;
    }

    filteredProducts.forEach(product => {
        const productCard = createProductCard(product);
        productsGrid.appendChild(productCard);
    });

    updateResultsCount();
}

function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-item';
    
    const stars = '★'.repeat(Math.floor(product.rating)) + '☆'.repeat(5 - Math.floor(product.rating));
    const outOfStock = !product.available ? '<div class="out-of-stock">غير متوفر</div>' : '';
    
    card.innerHTML = `
        <div class="product-image-wrapper">
            <img src="${product.image}" alt="${product.name}">
            ${outOfStock}
        </div>
        <div class="product-details">
            <div>
                <h3 class="product-name">${product.name}</h3>
                <div class="product-rating">
                    <span class="stars">${stars}</span>
                    <span class="rating-count">(${product.reviews})</span>
                </div>
                <div class="product-price-section">
                    <div>
                        <span class="product-price">${product.price}</span>
                        <span class="product-unit">جنيه</span>
                    </div>
                </div>
            </div>
            <div class="product-actions">
                <button class="btn-add-cart" ${!product.available ? 'disabled' : ''}>
                    ${product.available ? 'أضف للسلة' : 'غير متوفر'}
                </button>
                <button class="btn-wishlist" title="أضف للمفضلة">♡</button>
            </div>
        </div>
    `;

    const addToCartBtn = card.querySelector('.btn-add-cart');
    if (addToCartBtn && product.available) {
        addToCartBtn.addEventListener('click', () => alert(`تم إضافة "${product.name}" إلى السلة!`));
    }

    const wishlistBtn = card.querySelector('.btn-wishlist');
    wishlistBtn.addEventListener('click', function() {
        this.classList.toggle('active');
        this.textContent = this.classList.contains('active') ? '♥' : '♡';
    });

    return card;
}

function initializeFilters() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            filterState.search = e.target.value.toLowerCase();
            applyFilters();
        });
    }

    document.querySelectorAll('input[data-parent="true"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const subCategories = this.closest('.category-parent').querySelector('.sub-categories');
            if (subCategories) {
                if (this.checked) {
                    subCategories.classList.add('show');
                } else {
                    subCategories.classList.remove('show');
                    subCategories.querySelectorAll('input[type="checkbox"]').forEach(sub => sub.checked = false);
                }
            }
            updateCategoryFilters();
            applyFilters();
        });
    });

    document.querySelectorAll('.sub-categories input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateCategoryFilters();
            applyFilters();
        });
    });

    document.querySelectorAll('#available, #out-stock').forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });

    const sortSelect = document.getElementById('sortBy');
    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            filterState.sortBy = e.target.value;
            applyFilters();
        });
    }

    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentView = this.dataset.view;
            renderProducts();
        });
    });

    const clearBtn = document.querySelector('.clear-filters');
    if (clearBtn) {
        clearBtn.addEventListener('click', clearAllFilters);
    }

    document.querySelectorAll('.filter-title').forEach(title => {
        title.addEventListener('click', function() {
            this.closest('.filter-group').classList.toggle('collapsed');
        });
    });
}

function initializePriceSlider() {
    const minSlider = document.getElementById('minPriceSlider');
    const maxSlider = document.getElementById('maxPriceSlider');
    const minValue = document.getElementById('minPriceValue');
    const maxValue = document.getElementById('maxPriceValue');

    if (!minSlider || !maxSlider) return;

    minSlider.addEventListener('input', function() {
        let min = parseInt(this.value);
        let max = parseInt(maxSlider.value);
        if (min > max - 50) {
            min = max - 50;
            this.value = min;
        }
        minValue.textContent = min;
        filterState.minPrice = min;
        applyFilters();
    });

    maxSlider.addEventListener('input', function() {
        let max = parseInt(this.value);
        let min = parseInt(minSlider.value);
        if (max < min + 50) {
            max = min + 50;
            this.value = max;
        }
        maxValue.textContent = max;
        filterState.maxPrice = max;
        applyFilters();
    });
}

function updateCategoryFilters() {
    const mainCategories = Array.from(document.querySelectorAll('input[data-parent="true"]:checked')).map(cb => cb.value);
    const subCategories = Array.from(document.querySelectorAll('.sub-categories input[type="checkbox"]:checked')).map(cb => cb.value);
    filterState.categories = mainCategories;
    filterState.subCategories = subCategories;
}

function applyFilters() {
    filteredProducts = allProducts.filter(product => {
        if (filterState.search && !product.name.toLowerCase().includes(filterState.search)) return false;
        if (filterState.categories.length > 0 || filterState.subCategories.length > 0) {
            const matchesMainCategory = filterState.categories.includes(product.category);
            const matchesSubCategory = filterState.subCategories.includes(product.subCategory);
            if (!matchesMainCategory && !matchesSubCategory) return false;
        }
        if (product.price < filterState.minPrice || product.price > filterState.maxPrice) return false;
        
        const availableChecked = document.getElementById('available')?.checked;
        const outStockChecked = document.getElementById('out-stock')?.checked;
        if (availableChecked && !outStockChecked && !product.available) return false;
        if (!availableChecked && outStockChecked && product.available) return false;
        
        return true;
    });

    sortProducts();
    renderProducts();
}

function sortProducts() {
    switch (filterState.sortBy) {
        case 'price-low': filteredProducts.sort((a, b) => a.price - b.price); break;
        case 'price-high': filteredProducts.sort((a, b) => b.price - a.price); break;
        case 'name-asc': filteredProducts.sort((a, b) => a.name.localeCompare(b.name, 'ar')); break;
        case 'name-desc': filteredProducts.sort((a, b) => b.name.localeCompare(a.name, 'ar')); break;
        case 'rating': filteredProducts.sort((a, b) => b.rating - a.rating); break;
    }
}

function clearAllFilters() {
    document.getElementById('searchInput').value = '';
    document.querySelectorAll('.filter-option input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('.sub-categories').forEach(sub => sub.classList.remove('show'));
    document.getElementById('minPriceSlider').value = 0;
    document.getElementById('maxPriceSlider').value = 1000;
    document.getElementById('minPriceValue').textContent = 0;
    document.getElementById('maxPriceValue').textContent = 1000;
    
    filterState.search = '';
    filterState.categories = [];
    filterState.subCategories = [];
    filterState.minPrice = 0;
    filterState.maxPrice = 1000;
    filterState.sortBy = 'default';
    document.getElementById('sortBy').value = 'default';
    
    applyFilters();
}

function updateResultsCount() {
    document.getElementById('showingCount').textContent = filteredProducts.length;
    document.getElementById('totalCount').textContent = allProducts.length;
}

console.log('صفحة منتجات الشركة - تم تحميل السكريبتات بنجاح ✓');
