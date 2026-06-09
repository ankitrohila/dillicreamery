import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';

// ─── Alpine.js ────────────────────────────────────────────────────────────────
Alpine.plugin(focus);
window.Alpine = Alpine;

// Global cart store
Alpine.store('cart', {
    count: 0,
    init() {
        // Sync with server on load
        fetch('/cart/count', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '' } })
            .then(r => r.json())
            .then(d => { if (d.count !== undefined) this.count = d.count; })
            .catch(() => {});
    },
    increment() { this.count++; },
    set(n) { this.count = n; },
});

Alpine.start();

// ─── GSAP Scroll Animations ───────────────────────────────────────────────────
gsap.registerPlugin(ScrollTrigger);

// Animate elements with .reveal class on scroll
document.addEventListener('DOMContentLoaded', () => {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });

    // GSAP scroll reveals
    const revealElements = document.querySelectorAll('.reveal');
    revealElements.forEach((el, i) => {
        gsap.fromTo(el,
            { opacity: 0, y: 40 },
            {
                opacity: 1, y: 0, duration: 0.7, ease: 'power2.out',
                delay: (i % 3) * 0.1,
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                },
            }
        );
    });

    // Number counter animation
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        gsap.fromTo(el, { textContent: 0 }, {
            textContent: target, duration: 2, ease: 'power1.out',
            snap: { textContent: 1 },
            scrollTrigger: { trigger: el, start: 'top 80%' },
        });
    });

    // 3D card tilt effect
    document.querySelectorAll('.card-3d').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            gsap.to(card, {
                rotateY: x * 10, rotateX: -y * 10,
                transformPerspective: 1000, duration: 0.3, ease: 'power2.out',
            });
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(card, { rotateY: 0, rotateX: 0, duration: 0.5, ease: 'elastic.out(1, 0.5)' });
        });
    });

    // Init Swiper carousels
    document.querySelectorAll('.swiper').forEach(el => {
        new Swiper(el, {
            modules: [Navigation, Pagination, Autoplay],
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: { delay: 4000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });
    });

    // Hero parallax
    const hero = document.querySelector('[data-parallax]');
    if (hero) {
        window.addEventListener('scroll', () => {
            const y = window.scrollY;
            hero.style.transform = `translateY(${y * 0.4}px)`;
        }, { passive: true });
    }

    // Navbar scroll effect
    const navbar = document.querySelector('header');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }, { passive: true });
    }
});

// ─── Cart utility ─────────────────────────────────────────────────────────────
window.addToCart = function(productId, variationId = null, quantity = 1) {
    const token = document.querySelector('meta[name=csrf-token]')?.content ?? '';
    return fetch('/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
        body: JSON.stringify({ product_id: productId, variation_id: variationId, quantity }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Alpine.store('cart').set(data.count);
            if (window.Livewire) Livewire.dispatch('cart-updated');
            showToast('Added to cart! 🛒', 'success');
        }
        return data;
    });
};

window.showToast = function(message, type = 'success') {
    const colors = { success: '#5B9B8A', error: '#ef4444', warning: '#f59e0b', info: '#3b82f6' };
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;
        padding: 0.875rem 1.5rem; border-radius: 1rem; color: white; font-weight: 600;
        background: ${colors[type] || colors.success}; box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        transform: translateY(100px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-size: 0.9rem; max-width: 300px;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    requestAnimationFrame(() => { toast.style.transform = 'translateY(0)'; });
    setTimeout(() => {
        toast.style.transform = 'translateY(100px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
};
