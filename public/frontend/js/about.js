// js/about.js

// Animate elements on scroll
const animateOnScroll = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, {
    threshold: 0.1,
    rootMargin: '0px'
});

// Apply animation to sections
document.querySelectorAll('.vmv-card, .team-card, .timeline-item, .award-card, .partner-logo').forEach(element => {
    element.style.opacity = '0';
    element.style.transform = 'translateY(30px)';
    element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    animateOnScroll.observe(element);
});

// Stats Counter Animation
const statsObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateStatNumber(entry.target);
            statsObserver.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.5
});

document.querySelectorAll('.stats-section .stat-number').forEach(stat => {
    statsObserver.observe(stat);
});

function animateStatNumber(element) {
    const text = element.textContent;
    const hasPlus = text.includes('+');
    const hasPercent = text.includes('%');
    const hasM = text.includes('M');
    const number = parseInt(text.replace(/\D/g, ''));
    
    let current = 0;
    const increment = number / 50;
    const timer = setInterval(() => {
        current += increment;
        if (current >= number) {
            current = number;
            clearInterval(timer);
        }
        
        let displayValue = Math.floor(current).toString();
        if (hasM) displayValue += 'M';
        if (hasPlus) displayValue += '+';
        if (hasPercent) displayValue += '%';
        
        element.textContent = displayValue;
    }, 30);
}

// Timeline Animation - appear from left/right
const timelineObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('timeline-appear');
        }
    });
}, {
    threshold: 0.2
});

document.querySelectorAll('.timeline-item').forEach(item => {
    timelineObserver.observe(item);
});

// Add CSS class for timeline animation
const style = document.createElement('style');
style.textContent = `
    .timeline-item {
        opacity: 0;
    }
    .timeline-item.timeline-appear {
        animation: timelineSlideIn 0.6s ease forwards;
    }
    @keyframes timelineSlideIn {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .timeline-item:nth-child(odd).timeline-appear {
        animation: timelineSlideInReverse 0.6s ease forwards;
    }
    @keyframes timelineSlideInReverse {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
`;
document.head.appendChild(style);

// Team Card Hover Effect
document.querySelectorAll('.team-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 20px 40px rgba(37,99,235,0.2)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.08)';
    });
});

// Smooth scroll for CTA buttons
document.querySelectorAll('.cta-buttons .btn').forEach(button => {
    button.addEventListener('click', function() {
        if (this.textContent.includes('تواصل')) {
            window.location.href = 'index.html#contact';
        } else if (this.textContent.includes('سجل')) {
            alert('سيتم فتح صفحة التسجيل قريباً!');
        }
    });
});

// Newsletter form
const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('شكراً لاشتراكك في النشرة البريدية!');
        this.reset();
    });
}

console.log('صفحة من نحن - تم تحميل جميع السكريبتات بنجاح ✓');
