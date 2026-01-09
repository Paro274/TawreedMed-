// js/cart.js

// Sample cart data (في المشروع الحقيقي هييجي من localStorage أو API)
let cartItems = [
    {
        id: 1,
        name: 'باراسيتامول 500 ملجم',
        company: 'إيفا فارما',
        price: 150,
        quantity: 2,
        image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=150&h=150&fit=crop'
    },
    {
        id: 2,
        name: 'أموكسيسيللين 1 جم',
        company: 'فاركو',
        price: 280,
        quantity: 1,
        image: 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=150&h=150&fit=crop'
    },
    {
        id: 3,
        name: 'فيتامين د 5000 وحدة',
        company: 'ماركيرل',
        price: 180,
        quantity: 3,
        image: 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=150&h=150&fit=crop'
    }
];

const SHIPPING_COST = 50;
let discountAmount = 0;
let discountPercent = 0;

document.addEventListener('DOMContentLoaded', function() {
    renderCart();
    updateSummary();

    // Clear cart button
    document.getElementById('clearCart')?.addEventListener('click', function() {
        if (confirm('هل أنت متأكد من إفراغ السلة؟')) {
            cartItems = [];
            renderCart();
            updateSummary();
        }
    });

    // Apply coupon
    document.getElementById('applyCoupon')?.addEventListener('click', applyCoupon);

    // Checkout button
    document.getElementById('checkoutBtn')?.addEventListener('click', function() {
        if (cartItems.length === 0) {
            alert('السلة فارغة!');
            return;
        }
        alert('جاري تحويلك لصفحة الدفع...');
        // window.location.href = 'checkout.html';
    });
});

function renderCart() {
    const container = document.getElementById('cartItemsContainer');
    const cartLayout = document.getElementById('cartLayout');
    const emptyCart = document.getElementById('emptyCart');

    if (cartItems.length === 0) {
        cartLayout.style.display = 'none';
        emptyCart.style.display = 'block';
        return;
    }

    cartLayout.style.display = 'grid';
    emptyCart.style.display = 'none';

    container.innerHTML = cartItems.map(item => `
        <div class="cart-item" data-id="${item.id}">
            <div class="item-details">
                <div class="item-image">
                    <img src="${item.image}" alt="${item.name}">
                </div>
                <div class="item-info">
                    <h3>${item.name}</h3>
                    <p>${item.company}</p>
                </div>
            </div>
            <div class="item-price">${item.price} جنيه</div>
            <div class="item-quantity">
                <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">−</button>
                <span class="qty-value">${item.quantity}</span>
                <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
            </div>
            <div class="item-total">${item.price * item.quantity} جنيه</div>
            <button class="item-remove" onclick="removeItem(${item.id})">×</button>
        </div>
    `).join('');
}

function updateQuantity(itemId, change) {
    const item = cartItems.find(i => i.id === itemId);
    if (!item) return;

    item.quantity += change;

    if (item.quantity < 1) {
        removeItem(itemId);
        return;
    }

    if (item.quantity > 99) {
        item.quantity = 99;
    }

    renderCart();
    updateSummary();
}

function removeItem(itemId) {
    cartItems = cartItems.filter(i => i.id !== itemId);
    renderCart();
    updateSummary();
}

function updateSummary() {
    const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    // Calculate discount
    if (discountPercent > 0) {
        discountAmount = subtotal * (discountPercent / 100);
    }
    
    const shipping = cartItems.length > 0 ? SHIPPING_COST : 0;
    const total = subtotal + shipping - discountAmount;

    document.getElementById('subtotal').textContent = `${subtotal} جنيه`;
    document.getElementById('shipping').textContent = cartItems.length > 0 ? `${shipping} جنيه` : 'مجاناً';
    document.getElementById('discount').textContent = `- ${discountAmount} جنيه`;
    document.getElementById('total').textContent = `${total} جنيه`;
}

function applyCoupon() {
    const couponInput = document.getElementById('couponInput');
    const couponCode = couponInput.value.trim().toUpperCase();
    const discountRow = document.getElementById('discountRow');

    // Sample coupons
    const coupons = {
        'SAVE10': 10,
        'SAVE20': 20,
        'WELCOME': 15
    };

    if (coupons[couponCode]) {
        discountPercent = coupons[couponCode];
        updateSummary();
        discountRow.style.display = 'flex';
        alert(`تم تطبيق كود الخصم! خصم ${discountPercent}%`);
        couponInput.value = '';
    } else if (couponCode === '') {
        alert('من فضلك أدخل كود الخصم');
    } else {
        alert('كود الخصم غير صحيح');
    }
}

console.log('صفحة السلة - تم تحميل السكريبتات بنجاح ✓');
