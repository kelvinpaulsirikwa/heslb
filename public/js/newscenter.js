
document.addEventListener('DOMContentLoaded', function() {
    // Event data for calendar
    const eventData = {
        '5': [
            { time: '09:00', title: 'Mkutano wa Bodi', description: 'Mkutano wa kila mwezi wa bodi ya uongozi' },
            { time: '14:00', title: 'Semina ya Mikopo', description: 'Semina ya elimu kuhusu mikopo ya wanafunzi' }
        ],
        '12': [
            { time: '10:00', title: 'Ziara ya Kimkakati', description: 'Ziara ya kimkakati kwa vyuo vya elimu ya juu' },
            { time: '16:00', title: 'Warsha ya Ubunifu', description: 'Warsha ya kuhamasisha ubunifu miongoni mwa wanafunzi' }
        ],
        '18': [
            { time: '08:30', title: 'Ufunguzi wa Maonesho', description: 'Ufunguzi wa maonesho ya vipawa vya wanafunzi' },
            { time: '11:00', title: 'Mkutano wa Wadau', description: 'Mkutano wa wadau wa elimu ya juu' },
            { time: '15:00', title: 'Uzinduzi wa Mradi', description: 'Uzinduzi wa mradi mpya wa maendeleo' }
        ],
        '25': [
            { time: '09:30', title: 'Kongamano la Elimu', description: 'Kongamano la taifa la elimu ya juu' },
            { time: '13:00', title: 'Hafla ya Graduation', description: 'Hafla ya graduation ya wanafunzi' }
        ]
    };

    // Modal functionality
    const modal = document.getElementById('eventModal');
    const closeBtn = document.querySelector('.close');
    const eventsList = document.getElementById('eventsList');

    // Close modal when clicking close button
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function() {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        });
    }

    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        });
    }

    // Calendar event clicks
    document.querySelectorAll('.calendar-cell.event').forEach(cell => {
        cell.addEventListener('click', function() {
            const day = this.dataset.day;
            const events = eventData[day] || [];
            
            // Populate modal with events
            if (eventsList) {
                eventsList.innerHTML = '';
                
                if (events.length > 0) {
                    events.forEach(event => {
                        const eventItem = document.createElement('div');
                        eventItem.className = 'event-item';
                        eventItem.innerHTML = `
                            <div class="event-time">${event.time}</div>
                            <div class="event-details">
                                <h6>${event.title}</h6>
                                <p>${event.description}</p>
                            </div>
                        `;
                        eventsList.appendChild(eventItem);
                    });
                } else {
                    eventsList.innerHTML = '<p class="text-center text-muted">Hakuna matukio ya siku hii.</p>';
                }
            }
            
            // Show modal
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }
        });
    });

    // Category card filtering
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const category = this.dataset.category;
            console.log('Filtering by category:', category);
            
            // Add visual feedback
            document.querySelectorAll('.category-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            // Filter news articles (you can implement actual filtering logic here)
            document.querySelectorAll('.masonry-item').forEach(item => {
                const itemCategory = item.querySelector('.news-category-badge').textContent.toLowerCase();
                if (itemCategory.includes(category) || category === 'all') {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Animated counter for stats
    function animateCounter(element) {
        const target = parseInt(element.dataset.count);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }

    // Intersection Observer for stats animation
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                statsObserver.unobserve(entry.target);
            }
        });
    });

    document.querySelectorAll('.stat-number').forEach(stat => {
        statsObserver.observe(stat);
    });

    // Search functionality
    const searchInput = document.querySelector('.search-input-modern');
    const searchBtn = document.querySelector('.search-btn-modern');
    
    function performSearch() {
        if (searchInput) {
            const query = searchInput.value.trim();
            if (query) {
                console.log('Searching for:', query);
                
                // Visual feedback
                if (searchBtn) {
                    searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                }
                
                // Simulate search delay
                setTimeout(() => {
                    if (searchBtn) {
                        searchBtn.innerHTML = '<i class="fas fa-search"></i>';
                    }
                 }, 1000);
            }
        }
    }
    
    if (searchBtn) {
        searchBtn.addEventListener('click', performSearch);
    }
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }

    // Calendar navigation
    document.querySelectorAll('.calendar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const isNext = this.querySelector('.fa-chevron-right') !== null;
            console.log(isNext ? 'Next month' : 'Previous month');
            
            // Add visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }, 100);
        });
    });

    // Page navigation
    document.querySelectorAll('.page-btn-modern').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.page-btn-modern').forEach(b => 
                b.classList.remove('active')
            );
            
            if (!this.querySelector('i')) {
                this.classList.add('active');
            }
            
            // Smooth scroll to top
            window.scrollTo({ 
                top: 0, 
                behavior: 'smooth' 
            });
        });
    });

    // Newsletter signup
    const newsletterBtn = document.querySelector('.newsletter-btn');
    const newsletterInput = document.querySelector('.newsletter-input');
    
    if (newsletterBtn) {
        newsletterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const email = newsletterInput ? newsletterInput.value : '';
            
            if (!email) {
                alert('Tafadhali ingiza barua pepe yako.');
                return;
            }
            
            if (!email.includes('@')) {
                alert('Tafadhali ingiza barua pepe sahihi.');
                return;
            }
            
            // Loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Inatuma...';
            this.disabled = true;
            
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check me-2"></i>Umejisajili!';
                if (newsletterInput) {
                    newsletterInput.value = '';
                }
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 2000);
            }, 1500);
        });
    }

    // Read more button loading effect
    document.querySelectorAll('.read-more-modern').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Inapakia...';
            this.style.pointerEvents = 'none';
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.pointerEvents = 'auto';
                // You can redirect to actual article page here
                console.log('Navigate to full article');
            }, 2000);
        });
    });

    // Trending news click tracking
    document.querySelectorAll('.trending-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const title = this.querySelector('h6').textContent;
            console.log('Clicked trending article:', title);
            
            // Add click animation
            this.style.transform = 'translateX(15px)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });

    // Dynamic placeholder for search
    const placeholders = [
        'Tafuta habari za HESLB...',
        'Tafuta matukio...',
        'Tafuta masasisho...',
        'Tafuta makala...',
        'Tafuta mikopo...',
        'Tafuta elimu...'
    ];
    
    let currentPlaceholder = 0;
    
    setInterval(() => {
        if (searchInput && !searchInput.value && document.activeElement !== searchInput) {
            searchInput.placeholder = placeholders[currentPlaceholder];
            currentPlaceholder = (currentPlaceholder + 1) % placeholders.length;
        }
    }, 3000);

    // Masonry item animations
    const masonryObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                masonryObserver.unobserve(entry.target);
            }
        });
    });

    document.querySelectorAll('.masonry-item').forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `all 0.6s ease ${index * 0.1}s`;
        masonryObserver.observe(item);
    });

    // Widget cards entrance animation
    const widgetObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                widgetObserver.unobserve(entry.target);
            }
        });
    });

    document.querySelectorAll('.widget-card').forEach((widget, index) => {
        widget.style.opacity = '0';
        widget.style.transform = 'translateY(20px)';
        widget.style.transition = `all 0.6s ease ${index * 0.2}s`;
        widgetObserver.observe(widget);
    });

    // Category badge clicks in sidebar
    document.querySelectorAll('.badge').forEach(badge => {
        badge.addEventListener('click', function(e) {
            e.preventDefault();
            const category = this.textContent.toLowerCase().replace(' news', '');
            
            // Visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Filter articles
            document.querySelectorAll('.masonry-item').forEach(item => {
                const itemCategory = item.querySelector('.news-category-badge').textContent.toLowerCase();
                if (itemCategory.includes(category)) {
                    item.style.display = 'block';
                    item.style.opacity = '1';
                } else {
                    item.style.opacity = '0.3';
                }
            });
            
            console.log('Filtering by:', category);
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
 

    // Weather widget click for more info
    const weatherWidget = document.querySelector('.weather-widget');
    if (weatherWidget) {
        weatherWidget.addEventListener('click', function() {
            alert('Hali ya Hewa ya Kina:\n\nJoto: 28°C\nUnyevu: 65%\nUpepo: 12 km/h\nMwelekeo: Kaskazini-Mashariki\nUonekano: Wazi\n\nSiku ya kesho: 30°C, Jua kali');
        });
    }
});

// Additional utility functions
function shareArticle(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
        window.open(shareUrl, '_blank');
    }
}

function bookmarkArticle(title) {
    // Simple bookmark simulation
    let bookmarks = JSON.parse(localStorage.getItem('heslb_bookmarks') || '[]');
    bookmarks.push({
        title: title,
        date: new Date().toISOString()
    });
    localStorage.setItem('heslb_bookmarks', JSON.stringify(bookmarks));
    alert('Makala imehifadhiwa kwenye vinavyopendwa!');
}

// Print functionality
function printPage() {
    window.print();
}


