<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth hover effects for news cards
    const newsCards = document.querySelectorAll('.news-item');
    newsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
            this.style.boxShadow = 'var(--gov-shadow-lg)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'var(--gov-shadow)';
        });
    });
    
    // Enhanced category hover effects
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.category-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
                icon.style.background = 'var(--gov-gradient)';
                icon.style.color = 'white';
                icon.style.boxShadow = '0 4px 20px rgba(14, 155, 213, 0.3)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.category-icon');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
                icon.style.background = 'linear-gradient(135deg, var(--gov-light) 0%, #e6f7ff 100%)';
                icon.style.color = 'var(--gov-primary)';
                icon.style.boxShadow = '0 2px 10px rgba(14, 155, 213, 0.1)';
            }
        });
    });
    
    // Search form enhancement
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                searchInput.focus();
                searchInput.style.borderColor = '#e53e3e';
                searchInput.style.boxShadow = '0 0 0 3px rgba(229, 62, 62, 0.1)';
                setTimeout(() => {
                    searchInput.style.borderColor = 'var(--gov-border)';
                    searchInput.style.boxShadow = 'none';
                }, 2000);
            }
        });
        
        searchInput.addEventListener('focus', function() {
            this.style.borderColor = 'var(--gov-primary)';
            this.style.boxShadow = '0 0 0 3px rgba(14, 155, 213, 0.1)';
        });
        
        searchInput.addEventListener('blur', function() {
            this.style.borderColor = 'var(--gov-border)';
            this.style.boxShadow = 'none';
        });
    }
    
    // Professional loading indicator for links
    const governmentLinks = document.querySelectorAll('.gov-btn, .read-more, .category-item');
    governmentLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.href && !this.href.includes('#')) {
                this.style.position = 'relative';
                this.classList.add('loading');
                const originalText = this.innerHTML;
                
                // Create loading spinner
                const spinner = document.createElement('div');
                spinner.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Inapakia...';
                spinner.style.display = 'flex';
                spinner.style.alignItems = 'center';
                spinner.style.gap = '0.5rem';
                
                this.innerHTML = '';
                this.appendChild(spinner);
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('loading');
                }, 1500);
            }
        });
    });
    
    // Keyboard navigation for accessibility
    document.addEventListener('keydown', function(e) {
        // Alt + S to focus search
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.focus();
                searchInput.style.boxShadow = '0 0 0 3px rgba(14, 155, 213, 0.3)';
            }
        }
        
        // Alt + H to scroll to top
        if (e.altKey && e.key === 'h') {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Alt + C to focus on categories
        if (e.altKey && e.key === 'c') {
            e.preventDefault();
            const firstCategory = document.querySelector('.category-item');
            if (firstCategory) {
                firstCategory.focus();
                firstCategory.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    // Enhanced lazy loading for images with fade effect
    const images = document.querySelectorAll('img[src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.5s ease';
                
                const tempImg = new Image();
                tempImg.onload = () => {
                    img.style.opacity = '1';
                };
                tempImg.src = img.src;
                
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Static elements - no scroll animations
    const animateElements = document.querySelectorAll('.news-item, .category-item, .latest-news-item');
    animateElements.forEach((element) => {
        element.style.opacity = '1';
        element.style.transform = 'none';
        element.style.transition = 'none';
    });
    
    // Dynamic category count updates with animation
    const categoryLinks = document.querySelectorAll('.category-link');
    categoryLinks.forEach(link => {
        const countElement = link.querySelector('.category-count');
        if (countElement) {
            const originalValue = countElement.textContent;
            
            link.addEventListener('mouseenter', function() {
                countElement.style.transform = 'scale(1.2)';
                countElement.style.background = 'linear-gradient(135deg, #0a7ba8 0%, #155a85 100%)';
            });
            
            link.addEventListener('mouseleave', function() {
                countElement.style.transform = 'scale(1)';
                countElement.style.background = 'var(--gov-gradient)';
            });
        }
    });
    
    // Sidebar remains static - no scroll effects
    const sidebar = document.querySelector('.government-sidebar');
    if (sidebar) {
        // Keep sidebar static without any scroll-based animations
        sidebar.style.transform = 'none';
        sidebar.style.opacity = '1';
    }
    
    // Professional hover effects for sidebar widgets
    const widgetHeaders = document.querySelectorAll('.widget-header');
    widgetHeaders.forEach(header => {
        header.addEventListener('mouseenter', function() {
            this.style.background = 'linear-gradient(135deg, #0a7ba8 0%, #155a85 100%)';
            this.style.transform = 'translateY(-2px)';
        });
        
        header.addEventListener('mouseleave', function() {
            this.style.background = 'var(--gov-gradient)';
            this.style.transform = 'translateY(0)';
        });
    });
    
    console.log('HESLB Government News Center with enhanced #0e9bd5 theme initialized successfully');
});

// Professional error handling with user feedback

// Performance monitoring
if ('PerformanceObserver' in window) {
    const observer = new PerformanceObserver((list) => {
        for (const entry of list.getEntries()) {
            if (entry.entryType === 'largest-contentful-paint') {
                console.log('LCP:', entry.startTime);
            }
        }
    });
    observer.observe({entryTypes: ['largest-contentful-paint']});
}
</script>