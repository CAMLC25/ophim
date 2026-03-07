import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Global functions
window.toggleFavorite = function(slug, name, poster) {
    const button = event.target.closest('button');
    const isFavorited = button.textContent.includes('Đã thích');
    
    if (isFavorited) {
        fetch(`/yeu-thich/${slug}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        }).then(response => {
            if (response.ok) {
                button.classList.remove('bg-red-600');
                button.classList.add('border', 'border-red-600');
                button.textContent = '🤍 Thích';
            }
        });
    } else {
        fetch('/yeu-thich/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                movie_slug: slug,
                movie_name: name,
                poster_url: poster
            })
        }).then(response => {
            if (response.ok) {
                button.classList.remove('border', 'border-red-600');
                button.classList.add('bg-red-600');
                button.textContent = '❤️ Đã thích';
            }
        });
    }
};

// Handle CSRF token in AJAX
document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    }
});

// Toast notifications auto-hide
document.querySelectorAll('[role="alert"]').forEach(element => {
    if (element.classList.contains('toast')) {
        setTimeout(() => {
            element.style.opacity = '0';
            element.style.transition = 'opacity 0.3s';
            setTimeout(() => element.remove(), 300);
        }, 3000);
    }
});

// Lazy load images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('fade-in');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));
}

// Search form handling
document.querySelector('form')?.addEventListener('submit', function(e) {
    const searchInput = this.querySelector('input[name="q"]');
    if (searchInput && !searchInput.value.trim()) {
        e.preventDefault();
        searchInput.focus();
    }
});

console.log('OPhim Application Ready! 🎬');
