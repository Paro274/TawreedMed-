// js/checkout.js

// Sample order items (في المشروع الحقيقي هييجي من localStorage أو API)
const orderItems = [
    {
        id: 1,
        name: 'باراسيتامول 500 ملجم',
        company: 'إيفا فارما',
        price: 150,
        quantity: 2,
        image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=80&h=80&fit=crop'
    },
    {
        id: 2,
        name: 'فيتامين د 5000 وحدة',
        company: 'ماركيرل',
        price: 180,
        quantity: 3,
        image: 'https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=80&h=80&fit=crop'
    }
];

const SHIPPING_COST = 50;
let selectedPayment = 'cash';

document.addEventListener('DOMContentLoaded', function() {
    renderOrderSummary();
    initializePaymentMethods();
    initializeForm();
});

function renderOrderSummary() {
    const container = document.getElementById('summaryItems');
    
    container.innerHTML = orderItems.map(item => `
        <div class="summary-item">
            <div class="item-img">
                <img src="${item.image}" alt="${item.name}">
            </div>
            <div class="item-info-summary">
                <h4>${item.name}</h4>
                <p>${item.company} × ${item.quantity}</p>
            </div>
            <div class="item-price-summary">${item.price * item.quantity} جنيه</div>
        </div>
    `).join('');

    updateTotals();
}

function updateTotals() {
    const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = 0; // يمكن إضافة لوجيك الخصم هنا
    const total = subtotal + SHIPPING_COST - discount;

    document.getElementById('subtotal').textContent = `${subtotal} جنيه`;
    document.getElementById('shipping').textContent = `${SHIPPING_COST} جنيه`;
    document.getElementById('discount').textContent = `- ${discount} جنيه`;
    document.getElementById('total').textContent = `${total} جنيه`;
}

function initializePaymentMethods() {
    const paymentOptions = document.querySelectorAll('input[name="payment"]');
    const cardDetails = document.getElementById('cardDetails');
    const walletDetails = document.getElementById('walletDetails');

    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            selectedPayment = this.value;

            // Hide all payment details
            cardDetails.style.display = 'none';
            walletDetails.style.display = 'none';

            // Show selected payment details
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else if (this.value === 'wallet') {
                walletDetails.style.display = 'block';
            }
        });
    });
}

function initializeForm() {
    const form = document.getElementById('checkoutForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Get form data
        const formData = new FormData(form);
        const orderData = {
            shipping: {
                firstName: formData.get('firstName'),
                lastName: formData.get('lastName'),
                phone: formData.get('phone'),
                email: formData.get('email'),
                governorate: formData.get('governorate'),
                city: formData.get('city'),
                address: formData.get('address'),
                notes: formData.get('notes')
            },
            payment: selectedPayment,
            items: orderItems,
            totals: {
                subtotal: orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0),
                shipping: SHIPPING_COST,
                total: orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0) + SHIPPING_COST
            }
        };

        console.log('Order Data:', orderData);

        // في المشروع الحقيقي هترسل البيانات للسيرفر
        alert('تم تأكيد طلبك بنجاح!\nرقم الطلب: #' + Math.floor(Math.random() * 100000));
        
        // Redirect to success page or home
        // window.location.href = 'order-success.html';
    });
}

console.log('صفحة الدفع - تم تحميل السكريبتات بنجاح ✓');
