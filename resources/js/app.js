import './bootstrap';

// Dark mode is enabled by default
document.documentElement.classList.add('dark');

// Cookie consent handling
document.addEventListener('DOMContentLoaded', function() {
    const cookieConsent = localStorage.getItem('cookieConsent');
    
    if (!cookieConsent) {
        showCookieConsent();
    }
});

function showCookieConsent() {
    const banner = document.getElementById('cookie-consent');
    if (banner) {
        banner.classList.remove('hidden');
    }
}

window.acceptCookies = function() {
    localStorage.setItem('cookieConsent', 'accepted');
    const banner = document.getElementById('cookie-consent');
    if (banner) {
        banner.classList.add('hidden');
    }
};

// Modal handling
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};

// Dropdown handling
window.toggleDropdown = function(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
};

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('[id$="-dropdown"]');
    dropdowns.forEach(dropdown => {
        if (!event.target.closest(`[onclick*="${dropdown.id}"]`) && 
            !event.target.closest(`#${dropdown.id}`)) {
            dropdown.classList.add('hidden');
        }
    });
});
