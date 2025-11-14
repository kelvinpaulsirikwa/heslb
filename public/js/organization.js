document.addEventListener('DOMContentLoaded', function() {
    const fullHeader = document.getElementById('full-header');
    const compactHeader = document.getElementById('compact-header');
    const headerSpacer = document.getElementById('header-spacer');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const closeNavBtn = document.getElementById('closeNavBtn');
    
    let lastScrollTop = 0;
    let headerTransitioned = false;
    let fullHeaderHeight = 0;
    let isScrolling = false;

    // Calculate full header height
    function calculateHeaderHeight() {
        if (fullHeader) {
            fullHeaderHeight = fullHeader.offsetHeight;
        }
    }

    // Initial calculation
    calculateHeaderHeight();

    // Enhanced scroll behavior for desktop only - NO MOBILE SCROLL BEHAVIOR
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        isScrolling = true;
        
        scrollTimeout = setTimeout(function() {
            isScrolling = false;
        }, 100);
        
        // ONLY apply scroll behavior on desktop (992px and above)
        if (window.innerWidth >= 992) { 
            const threshold = fullHeaderHeight * 0.8;
            
            if (scrollTop > threshold && !headerTransitioned) {
                // Transitioning to compact
                fullHeader.classList.add('hidden');
                headerSpacer.classList.add('active');
                
                setTimeout(() => {
                    compactHeader.classList.add('show');
                }, 200);
                
                headerTransitioned = true;
            } else if (scrollTop <= threshold * 0.6 && headerTransitioned) {
                // Transitioning back to full
                compactHeader.classList.remove('show');
                
                setTimeout(() => {
                    fullHeader.classList.remove('hidden');
                    headerSpacer.classList.remove('active');
                }, 200);
                
                headerTransitioned = false;
            }
        }
        // NO MOBILE SCROLL BEHAVIOR - header stays completely static
        
        lastScrollTop = scrollTop;
    });

    // Mobile menu toggle
    mobileMenuBtn?.addEventListener('click', function() {
        mobileNavOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    });

    // Close mobile menu
    closeNavBtn?.addEventListener('click', closeMobileNav);
    
    mobileNavOverlay?.addEventListener('click', function(e) {
        if (e.target === mobileNavOverlay) {
            closeMobileNav();
        }
    });

    function closeMobileNav() {
        mobileNavOverlay.classList.remove('show');
        document.body.style.overflow = '';
        
        // Close all open dropdowns and remove active states when closing mobile nav
        document.querySelectorAll('.nav-section-header.active').forEach(header => {
            header.classList.remove('active');
            const subsection = header.nextElementSibling;
            if (subsection) {
                subsection.classList.remove('show');
                subsection.style.maxHeight = '0px';
            }
        });
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        calculateHeaderHeight();
        
        if (window.innerWidth >= 992) {
            closeMobileNav();
            
            if (window.pageYOffset <= fullHeaderHeight * 0.8) {
                fullHeader.classList.remove('hidden');
                compactHeader.classList.remove('show');
                headerSpacer.classList.remove('active');
                headerTransitioned = false;
            }
        } else {
            // Mobile initialization - ALWAYS show compact header static, hide full header
            compactHeader.classList.add('show');
            compactHeader.classList.remove('mobile-hidden'); // Ensure it's never hidden
            fullHeader.classList.add('hidden');
            headerSpacer.classList.remove('active');
            headerTransitioned = true;
        }
    });

    // Close mobile nav on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileNavOverlay.classList.contains('show')) {
            closeMobileNav();
        }
    });

    // Initialize mobile view on load
    if (window.innerWidth < 992) {
        compactHeader.classList.add('show');
        compactHeader.classList.remove('mobile-hidden'); // Ensure it's never hidden
        fullHeader.classList.add('hidden');
        headerTransitioned = true;
    }

    // Prevent dropdown links from closing on click (desktop)
    document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
        });
    });

    // Enhanced dropdown hover behavior for desktop
    document.querySelectorAll('.dropdown').forEach(function(dropdown) {
        let hoverTimeout;
        
        dropdown.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
            const menu = this.querySelector('.dropdown-menu');
            if (menu) {
                menu.style.display = 'block';
                setTimeout(() => {
                    menu.classList.add('show');
                }, 10);
            }
        });
        
        dropdown.addEventListener('mouseleave', function() {
            const menu = this.querySelector('.dropdown-menu');
            if (menu) {
                menu.classList.remove('show');
                hoverTimeout = setTimeout(() => {
                    menu.style.display = 'none';
                }, 300);
            }
        });
    });

    // Initialize mobile dropdowns
    initializeMobileDropdowns();
});

// Initialize mobile dropdown functionality
function initializeMobileDropdowns() {
    // Add click event listeners to ALL mobile dropdown sections (entire clickable area)
    document.querySelectorAll('.nav-section-header').forEach(function(header) {
        header.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileDropdown(this);
        });
    });

    // Remove any existing active states on page load
    document.querySelectorAll('.nav-section-header').forEach(function(header) {
        header.classList.remove('active');
        const subsection = header.nextElementSibling;
        if (subsection) {
            subsection.classList.remove('show');
            subsection.style.maxHeight = '0px';
        }
    });
}

// Enhanced Mobile dropdown toggle function
function toggleMobileDropdown(element) {
    const subsection = element.nextElementSibling;
    const isActive = element.classList.contains('active');
    
    // Close all other dropdowns first
    document.querySelectorAll('.nav-section-header.active').forEach(header => {
        if (header !== element) {
            header.classList.remove('active');
            header.setAttribute('aria-expanded', 'false');
            const otherSubsection = header.nextElementSibling;
            if (otherSubsection) {
                otherSubsection.classList.remove('show');
                otherSubsection.style.maxHeight = '0px';
            }
        }
    });
    
    // Toggle current dropdown
    if (isActive) {
        // Close current dropdown
        element.classList.remove('active');
        element.setAttribute('aria-expanded', 'false');
        if (subsection) {
            subsection.classList.remove('show');
            subsection.style.maxHeight = '0px';
        }
    } else {
        // Open current dropdown
        element.classList.add('active');
        element.setAttribute('aria-expanded', 'true');
        if (subsection) {
            // Calculate the actual height needed
            const scrollHeight = subsection.scrollHeight;
            subsection.style.maxHeight = (scrollHeight + 100) + 'px';
            subsection.classList.add('show');
            
            // Ensure smooth opening animation
            setTimeout(() => {
                if (subsection.classList.contains('show')) {
                    subsection.style.maxHeight = 'none'; // Allow natural height
                }
            }, 500);
        }
    }
}

// Function to recalculate dropdown heights
function recalculateDropdownHeights() {
    document.querySelectorAll('.nav-subsection.show').forEach(function(subsection) {
        const scrollHeight = subsection.scrollHeight;
        subsection.style.maxHeight = (scrollHeight + 100) + 'px';
    });
}

// Language switching function
function changeLanguage(locale) {
    // Redirect to the language switch route
    window.location.href = `/lang/${locale}`;
}