<style>
        /* Main Container */
        .floating-menu-container {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(20px);
        }

        .floating-menu-container.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Main Toggle Button */
        .main-toggle-btn {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #005b94;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            animation: mainPulse 3s ease-in-out infinite;
            user-select: none; /* Prevent text selection */
            pointer-events: auto; /* But allow hover */
        }

        @keyframes mainPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .main-toggle-btn i {
            font-size: 1.8rem;
            transition: all 0.3s ease;
        }

        .floating-menu-container:hover .main-toggle-btn {
            transform: scale(1.1);
            background: #005b94;
            animation: none;
        }

        .floating-menu-container.expanded .main-toggle-btn i {
            transform: scale(1.1);
        }
        
        /* Visual indicator when menu is locked (closed) */
        .floating-menu-container.menu-locked .main-toggle-btn {
            background: #003d66; /* Darker when locked */
            opacity: 0.7;
        }
        
        .floating-menu-container.menu-locked .main-toggle-btn:hover {
            background: #005b94; /* Return to normal on hover */
            opacity: 1;
        }

        /* Menu Items Container */
        .menu-items {
            position: absolute;
            bottom: 70px;
            left: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding-bottom: 25px;
            opacity: 1;
            visibility: hidden;
            pointer-events: none;
            transition: visibility 0s;
        }

        /* Show menu on hover (only if not locked) */
        .floating-menu-container:hover .menu-items {
            visibility: visible !important;
            pointer-events: all !important;
        }
        
        /* Hide menu when locked (clicked to close) */
        .floating-menu-container.menu-locked .menu-items {
            visibility: hidden !important;
            pointer-events: none !important;
        }
        
        .floating-menu-container.menu-locked:hover .menu-items {
            visibility: hidden !important;
            pointer-events: none !important;
        }

        /* Individual Menu Item - LADDER ANIMATION */
        .menu-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transform: translateY(20px) scale(0.8);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* FALLBACK: Ensure menu items are visible on hover even without animation */
        .floating-menu-container:hover .menu-item {
            opacity: 1 !important;
            transform: translateY(0) scale(1) !important;
        }
        
        /* Hide menu items when locked */
        .floating-menu-container.menu-locked .menu-item {
            opacity: 0 !important;
            transform: translateY(20px) scale(0.8) !important;
            animation: none !important;
        }
        
        .floating-menu-container.menu-locked:hover .menu-item {
            opacity: 0 !important;
            animation: none !important;
        }

        /* LADDER EFFECT - Each item pops up one by one with bounce! */
        .floating-menu-container:hover .menu-item:nth-child(1) {
            animation: ladderPop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.05s forwards;
        }

        .floating-menu-container:hover .menu-item:nth-child(2) {
            animation: ladderPop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.1s forwards;
        }

        .floating-menu-container:hover .menu-item:nth-child(3) {
            animation: ladderPop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.15s forwards;
        }

        .floating-menu-container:hover .menu-item:nth-child(4) {
            animation: ladderPop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.2s forwards;
        }

        @keyframes ladderPop {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.5);
            }
            70% {
                transform: translateY(-5px) scale(1.05);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Extended hover area for submenu */
        .menu-item.has-submenu {
            padding-right: 250px; /* Extend hover area to cover social icons */
        }

        /* Menu Button */
        .menu-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: none;
            cursor: default; /* Changed from pointer to default for non-clickable items */
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            user-select: none;
        }
        
        /* Links should show pointer cursor */
        a.menu-btn {
            cursor: pointer;
        }

        .menu-btn i,
        .menu-btn img {
            transition: all 0.3s ease;
        }

        .menu-btn i {
            font-size: 1.5rem;
        }

        .menu-btn:hover {
            transform: translateX(-5px) scale(1.15);
        }

        .menu-btn:hover i {
            transform: scale(1.2) rotate(15deg);
        }

        .menu-btn:hover img {
            transform: scale(1.2);
        }

        /* Make loan button images white */
        .loan-insurance-btn img,
        .loan-repayment-btn img {
            filter: brightness(0) invert(1);
        }

        /* Button Colors */
        .loan-insurance-btn {
            background: linear-gradient(135deg, #0c1f38 0%, #1a3d5c 100%);
        }

        .loan-insurance-btn:hover {
            background: linear-gradient(135deg, #1a3d5c 0%, #0c1f38 100%);
        }

        .loan-repayment-btn {
            background: linear-gradient(135deg, #005b94 0%, #0077b6 100%);
        }

        .loan-repayment-btn:hover {
            background: linear-gradient(135deg, #0077b6 0%, #005b94 100%);
        }

        .call-center-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .call-center-btn:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
        }

        .social-media-btn {
            background: linear-gradient(135deg, #004a75 0%, #005b94 100%);
        }

        .social-media-btn:hover {
            background: linear-gradient(135deg, #005b94 0%, #004a75 100%);
        }

        /* Menu Label */
        .menu-label {
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
            position: absolute;
            left: 75px;
        }

        .menu-item:hover .menu-label {
            opacity: 1;
            visibility: visible;
        }

         /* Social Icons Container */
         .social-icons-container {
             position: absolute;
             left: 70px;
             top: 0;
             display: flex;
             gap: 10px;
             align-items: center;
             opacity: 1;
             visibility: hidden;
             pointer-events: none;
             transition: visibility 0s;
             padding-left: 10px; /* Bridge gap between button and icons */
             padding-right: 10px;
             height: 60px; /* Match button height */
         }

         /* Show social icons on hover */
         .menu-item.has-submenu:hover .social-icons-container {
             visibility: visible !important;
             pointer-events: all !important;
         }

        /* Ensure the social media button keeps hover state */
         .menu-item.has-submenu:hover .menu-btn.social-media-btn,
         .social-icons-container:hover ~ .menu-btn.social-media-btn {
             transform: translateX(-5px) scale(1.15);
         }

        /* Social Button - LADDER POP ANIMATION */
        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            opacity: 0;
            transform: translateX(-20px) scale(0.7);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* FALLBACK: Ensure social icons are visible on hover */
        .menu-item.has-submenu:hover .social-btn {
            opacity: 1 !important;
            transform: translateX(0) scale(1) !important;
        }

        /* LADDER EFFECT for social icons - Pop one by one from left! */
        .menu-item.has-submenu:hover .social-btn:nth-child(1) {
            animation: socialPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.05s forwards;
        }

        .menu-item.has-submenu:hover .social-btn:nth-child(2) {
            animation: socialPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.1s forwards;
        }

        .menu-item.has-submenu:hover .social-btn:nth-child(3) {
            animation: socialPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.15s forwards;
        }

        .menu-item.has-submenu:hover .social-btn:nth-child(4) {
            animation: socialPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.2s forwards;
        }

        @keyframes socialPop {
            0% {
                opacity: 0;
                transform: translateX(-30px) scale(0.3);
            }
            70% {
                transform: translateX(5px) scale(1.1);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        .social-btn:hover {
            transform: translateY(-5px) scale(1.2);
        }

        .social-btn i,
        .social-btn svg {
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-btn:hover i,
        .social-btn:hover svg {
            transform: scale(1.3);
        }

        /* Social Button Colors */
        .youtube-btn {
            background: linear-gradient(135deg, #FF0000, #CC0000);
        }

        .twitter-btn {
            background: linear-gradient(135deg, #1DA1F2, #0d8bd9);
        }

        .instagram-btn {
            background: linear-gradient(135deg, #E4405F, #C13584);
        }

        .facebook-btn {
            background: linear-gradient(135deg, #1877F2, #166fe5);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .floating-menu-container {
                bottom: 20px;
                left: 20px;
            }

            .main-toggle-btn {
                width: 60px;
                height: 60px;
            }

            .main-toggle-btn i {
                font-size: 1.5rem;
            }

            .menu-btn {
                width: 55px;
                height: 55px;
            }

            .social-btn {
                width: 40px;
                height: 40px;
            }

            .menu-label {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
 
 
    <div class="floating-menu-container" id="floatingMenu">
        <!-- Expandable Menu Items (appear on hover above main button) -->
        <div class="menu-items">
            <!-- Loan Insurance -->
            <div class="menu-item">
                <a href="{{ route('loanapplication.applicationlink') }}" class="menu-btn loan-insurance-btn" aria-label="Loan Insurance">
                    <img src="{{ asset('images/static_files/loanissuance.png') }}" alt="Loan Issuance" style="width: 35px; height: 35px; object-fit: contain;">
                </a>
                <span class="menu-label">Loan Insurance</span>
            </div>

            <!-- Loan Repayment -->
            <div class="menu-item">
                <a href="{{ route('loanrepayment.repaymentlink') }}" class="menu-btn loan-repayment-btn" aria-label="Loan Repayment">
                    <img src="{{ asset('images/static_files/loanrepayment.png') }}" alt="Loan Repayment" style="width: 35px; height: 35px; object-fit: contain;">
                </a>
                <span class="menu-label">Loan Repayment</span>
            </div>

            <!-- Call Center -->
            <div class="menu-item">
                <a href="{{ config('links.heslb_systems.call_center') }}" target="_blank" rel="noopener noreferrer" class="menu-btn call-center-btn" aria-label="Call Center">
                    <i class="fas fa-phone-alt"></i>
                </a>
                <span class="menu-label">Call Center</span>
            </div>

            <!-- Social Media with Submenu -->
            <div class="menu-item has-submenu">
                <div class="menu-btn social-media-btn" aria-label="Social Media" role="button" tabindex="0">
                    <i class="fas fa-share-alt"></i>
                </div>
                
                <div class="social-icons-container">
                    <a href="{{ config('links.social_media.youtube') }}" target="_blank" rel="noopener noreferrer" class="social-btn youtube-btn" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    
                    <a href="{{ config('links.social_media.twitter') }}" target="_blank" rel="noopener noreferrer" class="social-btn twitter-btn" aria-label="Twitter/X">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    
                    <a href="{{ config('links.social_media.instagram') }}" target="_blank" rel="noopener noreferrer" class="social-btn instagram-btn" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    
                    <a href="{{ config('links.social_media.facebook') }}" target="_blank" rel="noopener noreferrer" class="social-btn facebook-btn" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Toggle Button (No click action - hover only) -->
        <div class="main-toggle-btn" aria-label="Quick Links Menu" role="button" tabindex="0">
            <i class="fas fa-user-graduate"></i>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floatingMenu = document.getElementById('floatingMenu');
            
            if (!floatingMenu) return;
            
            let isVisible = false;
            let ticking = false;

            function handleScroll() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const shouldShow = scrollTop > 200;

                        if (shouldShow && !isVisible) {
                            floatingMenu.classList.add('visible');
                            isVisible = true;
                        } else if (!shouldShow && isVisible) {
                            floatingMenu.classList.remove('visible');
                            isVisible = false;
                        }
                        
                        ticking = false;
                    });
                    ticking = true;
                }
            }

            window.addEventListener('scroll', handleScroll, { passive: true });
            handleScroll();

            // Click to close menu functionality
            const mainBtn = document.querySelector('.main-toggle-btn');
            let isMenuLocked = false; // Track if menu is locked closed
            
            if (mainBtn) {
                mainBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle locked state
                    isMenuLocked = !isMenuLocked;
                    
                    if (isMenuLocked) {
                        // Close and lock menu
                        floatingMenu.classList.add('menu-locked');
                    } else {
                        // Unlock menu (can be opened by hover)
                        floatingMenu.classList.remove('menu-locked');
                    }
                    
                    return false;
                });
            }
        });
    </script>