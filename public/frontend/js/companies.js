// js/companies.js

const companies = [
    { id: 'eva', name: 'إيفا فارما', productsCount: 45, logo: 'إ' },
    { id: 'pharco', name: 'فاركو', productsCount: 38, logo: 'ف' },
    { id: 'eipico', name: 'إيبيكو', productsCount: 32, logo: 'إ' },
    { id: 'global', name: 'جلوبال نابى', productsCount: 28, logo: 'ج' },
    { id: 'marcyrl', name: 'ماركيرل', productsCount: 25, logo: 'م' },
    { id: 'amoun', name: 'آمون', productsCount: 22, logo: 'آ' }
];

document.addEventListener('DOMContentLoaded', function() {
    renderCompanies(companies);
    initializeSearch();
});

function renderCompanies(companiesList) {
    const grid = document.getElementById('companiesGrid');
    
    grid.innerHTML = companiesList.map(company => `
        <div class="company-card" onclick="viewCompany('${company.id}', '${company.name}')">
            <div class="company-logo">${company.logo}</div>
            <h3 class="company-name">${company.name}</h3>
            <p class="company-products-count">${company.productsCount} منتج</p>
            <button class="btn-view-products">عرض المنتجات</button>
        </div>
    `).join('');
}

function viewCompany(companyId, companyName) {
    window.location.href = `company-products.html?company=${companyId}&name=${encodeURIComponent(companyName)}`;
}

function initializeSearch() {
    const searchInput = document.getElementById('companySearch');
    const searchBtn = document.querySelector('.btn-search');
    
    function performSearch() {
        const query = searchInput.value.toLowerCase();
        const filtered = companies.filter(c => c.name.includes(query));
        renderCompanies(filtered);
    }
    
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') performSearch();
    });
}

console.log('صفحة الشركات - تم تحميل السكريبتات بنجاح ✓');
