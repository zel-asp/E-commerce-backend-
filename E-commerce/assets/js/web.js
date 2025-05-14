// Enhanced Search Functionality
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('product-search');
    const clearSearchBtn = document.getElementById('clear-search');
    const productCards = document.querySelectorAll('.product-card');
    const resultsCount = document.getElementById('search-results-count');
    const noResultsMessage = document.getElementById('no-results-message');

    // Debounce function to limit how often search executes
    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this,
                args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Search function
    function performSearch() {
        const query = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        if (query === '') {
            clearSearchBtn.style.display = 'none';
        } else {
            clearSearchBtn.style.display = 'flex';
        }

        productCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const description = card.getAttribute('data-description');
            const category = card.getAttribute('data-category');

            // Search in name, description, and category
            if (name.includes(query) ||
                description.includes(query) ||
                (category && category.includes(query))) {
                card.style.display = 'flex';
                card.classList.remove('opacity-50');
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update results count
        if (query.length > 0) {
            resultsCount.textContent = `${visibleCount} ${visibleCount === 1 ? 'result' : 'results'} found`;
            resultsCount.classList.remove('hidden');

            if (visibleCount === 0) {
                noResultsMessage.classList.remove('hidden');
            } else {
                noResultsMessage.classList.add('hidden');
            }
        } else {
            resultsCount.classList.add('hidden');
            noResultsMessage.classList.add('hidden');
        }
    }

    // Event listeners
    searchInput.addEventListener('input', debounce(performSearch, 300));

    clearSearchBtn.addEventListener('click', function () {
        searchInput.value = '';
        searchInput.focus();
        clearSearchBtn.style.display = 'none';
        performSearch();
    });

    // Initial search in case page loads with search term
    if (searchInput.value.trim() !== '') {
        performSearch();
    }
});

const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');

mobileMenuButton.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});