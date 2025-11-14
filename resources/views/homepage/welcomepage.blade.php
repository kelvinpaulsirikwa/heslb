<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .dynamic-content-page {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #0e9bd5 0%, rgba(14, 155, 213, 0.9) 100%);
        min-height: 60vh; /* Reduced from 70vh */
        overflow-x: hidden;
        overflow-y: auto;
        position: relative;
        width: 100%;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    }

    .dynamic-content-page::-webkit-scrollbar {
        width: 8px;
    }

    .dynamic-content-page::-webkit-scrollbar-track {
        background: transparent;
    }

    .dynamic-content-page::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }

    .dynamic-content-page::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    .dynamic-content-page .main-container {
        position: relative;
        width: 100%;
        min-height: 70vh;
        display: flex;
        flex-direction: column;
    }

    .dynamic-content-page .background-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 60vh; /* Reduced from 70vh */
        object-fit: cover;
        opacity: 0.15;
        z-index: 1;
        transition: all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        filter: blur(1px);
    }

    .dynamic-content-page .content-wrapper {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 50vh; /* Reduced from 60vh */
        padding: 40px 60px; /* Reduced top/bottom padding from 60px */
        flex: 1;
    }

    .dynamic-content-page .content-section {
        flex: 1;
        max-width: 45%;
        padding-right: 30px;
    }

    .dynamic-content-page .title {
        font-size: 3.5rem;
        font-weight: bold;
        color: white;
        margin-bottom: 20px;
        opacity: 0;
        transform: translateY(50px);
        animation: dynamicFadeInUp 1s ease forwards;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        min-height: auto;
    }

    .dynamic-content-page .description {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.95);
        line-height: 1.6;
        opacity: 0;
        animation: dynamicFadeInUp 1s ease 0.5s forwards;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        min-height: auto;
    }

    .dynamic-content-page .typing-text {
        border-right: 3px solid white;
        animation: dynamicBlink 1s infinite;
    }

    .dynamic-content-page .nav-arrow {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 5;
        backdrop-filter: blur(10px);
    }

    .dynamic-content-page .nav-arrow:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.7);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 5px 20px rgba(255, 255, 255, 0.2);
    }

    .dynamic-content-page .nav-arrow.prev {
        left: 20px;
    }

    .dynamic-content-page .nav-arrow.next {
        right: 20px;
    }

    .dynamic-content-page .nav-arrow svg {
        width: 20px;
        height: 20px;
        fill: white;
        transition: transform 0.3s ease;
    }

    .dynamic-content-page .nav-arrow:hover svg {
        transform: scale(1.2);
    }

    .dynamic-content-page .image-section {
        flex: 1;
        max-width: 50%;
        height: 50vh;
        position: relative;
        margin-right: 20px;
    }

    .dynamic-content-page .focused-image-container {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        transform: perspective(1000px) rotateY(-5deg);
        transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .dynamic-content-page .focused-image-container:hover {
        transform: perspective(1000px) rotateY(0deg) scale(1.02);
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
    }

    .dynamic-content-page .focused-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transform: scale(1.1) rotate(2deg);
        transition: all 1.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .dynamic-content-page .focused-image.active {
        opacity: 0.85;
        transform: scale(1) rotate(0deg);
    }

    .dynamic-content-page .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, rgba(14, 155, 213, 0.2) 0%, transparent 60%);
        z-index: 1;
        transition: all 0.6s ease;
    }

    .dynamic-content-page .focused-image-container:hover .image-overlay {
        background: linear-gradient(45deg, rgba(14, 155, 213, 0.1) 0%, transparent 70%);
    }

    .dynamic-content-page .background-pattern {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dynamicGrid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23dynamicGrid)"/></svg>');
        z-index: 0;
        pointer-events: none;
    }

    .dynamic-content-page .dots-indicator {
        position: fixed;
        bottom: 30px;
        left: 40px;
        display: flex;
        gap: 12px;
        z-index: 3;
    }

    .dynamic-content-page .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .dynamic-content-page .dot.active {
        background: white;
        transform: scale(1.2);
        border: 2px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
    }

    .dynamic-content-page .dot:hover {
        background: rgba(255, 255, 255, 0.7);
        transform: scale(1.1);
    }

    @keyframes dynamicFadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes dynamicBlink {
        0%, 50% { border-color: transparent; }
        51%, 100% { border-color: white; }
    }

    @keyframes dynamicSlideInRight {
        from {
            transform: translateX(100%) scale(1.1);
            opacity: 0;
        }
        to {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes dynamicSlideInLeft {
        from {
            transform: translateX(-100%) scale(1.1);
            opacity: 0;
        }
        to {
            transform: translateX(0) scale(1);
            opacity: 1;
        }
    }

    @keyframes dynamicZoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .dynamic-content-page .additional-content {
        position: relative;
        z-index: 2;
        background: rgba(255, 255, 255, 0.95);
        min-height: 40vh;
        padding: 40px 60px; /* Reduced from 60px */
        margin-top: 40px; /* Reduced from 60px */
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .dynamic-content-page .content-wrapper {
            padding: 40px;
        }

        .dynamic-content-page .title {
            font-size: 3rem;
        }
    }

    @media (max-width: 1024px) {
        .dynamic-content-page .content-wrapper {
            flex-direction: column;
            justify-content: center;
            padding: 30px 40px; /* Reduced from 40px */
            min-height: auto;
            padding-top: 30px; /* Reduced from 40px */
            padding-bottom: 30px; /* Reduced from 40px */
        }

        .dynamic-content-page .content-section {
            max-width: 100%;
            text-align: center;
            margin-bottom: 30px;
            padding-right: 0;
        }

        .dynamic-content-page .image-section {
            max-width: 80%;
            height: 40vh;
            margin-right: 0;
        }

        .dynamic-content-page .title {
            font-size: 2.5rem;
        }

        .dynamic-content-page .nav-arrow {
            top: 30%;
        }
    }

    @media (max-width: 768px) {
        .dynamic-content-page {
            min-height: 35vh; /* Reduced from 40vh */
        }

        .dynamic-content-page .main-container {
            min-height: 35vh; /* Reduced from 40vh */
        }

        .dynamic-content-page .background-image {
            height: 35vh; /* Reduced from 40vh */
        }

        .dynamic-content-page .content-wrapper {
            min-height: 25vh; /* Reduced from 30vh */
            padding: 15px 20px; /* Reduced from 20px */
            padding-top: 20px; /* Reduced from 30px */
            padding-bottom: 20px; /* Reduced from 30px */
        }

        .dynamic-content-page .title {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .dynamic-content-page .description {
            font-size: 1rem;
        }

        .dynamic-content-page .image-section {
            max-width: 100%;
            height: 30vh;
        }

        .dynamic-content-page .dots-indicator {
            left: 20px;
            bottom: 20px;
        }

        .dynamic-content-page .additional-content {
            padding: 20px 20px; /* Reduced from 30px 20px */
            margin-top: 20px; /* Reduced from 30px */
        }

        .dynamic-content-page .nav-arrow {
            width: 40px;
            height: 40px;
        }

        .dynamic-content-page .nav-arrow.prev {
            left: 10px;
        }

        .dynamic-content-page .nav-arrow.next {
            right: 10px;
        }
    }

    @media (max-width: 480px) {
        .dynamic-content-page .title {
            font-size: 1.8rem;
        }

        .dynamic-content-page .description {
            font-size: 1rem;
        }

        .dynamic-content-page .content-wrapper {
            padding: 8px 10px; /* Reduced from 10px */
            padding-top: 15px; /* Reduced from 20px */
            padding-bottom: 15px; /* Reduced from 20px */
        }
    }
</style>


  
  <div class="dynamic-content-page">
        <div class="main-container">
            <!-- Background Pattern -->
            <div class="background-pattern"></div>
            
            <img class="background-image" id="background-image" src="images/static_files/heslbbuilding.jpg" alt="Background">
            
           
            
            <!-- Main Content Wrapper -->
            <div class="content-wrapper">
                <!-- Left Side - Text Content -->
                <div class="content-section">
                    <h1 class="title" id="title-text"></h1>
                    <p class="description">
                        <span class="typing-text" id="description-text"></span>
                    </p>
                </div>
                
                <!-- Right Side - Focused Image -->
                <div class="image-section">
                    <div class="focused-image-container">
                        <div class="image-overlay"></div>
                        <img class="focused-image" id="focused-image" src="images/static_files/heslbbuilding.jpg" alt="Focused Content">
                    </div>
                </div>
            </div>
            
           
        </div>
    </div>

    <script>
        (function() {
            'use strict';
            
            const DynamicContentPage = {
                
                contentData: [
                    {
            name: "{{ __('welcomenote.welcome_title') }}",
            explanation: "{{ __('welcomenote.welcome_text') }}",
            image: "{{ asset('images/static_files/welcomehomeimage.JPG') }}",
            animation: "slideInRight"
        },
        {
            name: "{{ __('welcomenote.who_we_are_title') }}",
            explanation: "{{ __('welcomenote.who_we_are_text') }}",
            image: "{{ asset('images/static_files/welcomes.jpg') }}",
            animation: "zoomIn"
        },
                   ],
                
                currentIndex: 0,
                isTyping: false,
                intervalId: null,
                isTransitioning: false,

                init: function() {
                    this.createDots();
                    this.showContent(0);
                    this.startAutoSlide();
                    this.bindEvents();
                },

                createDots: function() {
                    const dotsContainer = document.getElementById('dots-indicator');
                    if (!dotsContainer) return;
                    
                    this.contentData.forEach((_, index) => {
                        const dot = document.createElement('div');
                        dot.className = 'dot';
                        if (index === 0) dot.classList.add('active');
                        dot.addEventListener('click', () => this.showContent(index));
                        dotsContainer.appendChild(dot);
                    });
                },

                showContent: function(index, direction = 'next') {
                    if (this.isTyping || this.isTransitioning) return;
                    
                    this.isTransitioning = true;
                    const prevIndex = this.currentIndex;
                    this.currentIndex = index;
                    const content = this.contentData[index];
                    
                    // Update dots
                    document.querySelectorAll('.dynamic-content-page .dot').forEach((dot, i) => {
                        dot.classList.toggle('active', i === index);
                    });
                    
                    // Enhanced image transition
                    this.updateImagesWithAnimation(content.image, direction, () => {
                        this.isTransitioning = false;
                    });
                    
                    // Type the title with backspace effect
                    this.typeTextWithBackspace('title-text', content.name, () => {
                        this.typeTextWithBackspace('description-text', content.explanation);
                    });
                },

                updateImagesWithAnimation: function(imageSrc, direction, callback) {
                    const backgroundImage = document.getElementById('background-image');
                    const focusedImage = document.getElementById('focused-image');
                    
                    if (!backgroundImage || !focusedImage) return;
                    
                    // Background image transition (smoother)
                    backgroundImage.style.transition = 'opacity 1.2s ease, filter 1.2s ease';
                    backgroundImage.style.opacity = '0.05';
                    
                    setTimeout(() => {
                        backgroundImage.src = imageSrc;
                        backgroundImage.style.opacity = '0.15';
                    }, 300);
                    
                    // Enhanced focused image transition
                    const slideOutClass = direction === 'next' ? 'slide-out-left' : 'slide-out-right';
                    const slideInClass = direction === 'next' ? 'slide-in-right' : 'slide-in-left';
                    
                    focusedImage.classList.remove('active');
                    focusedImage.classList.add(slideOutClass);
                    
                    setTimeout(() => {
                        focusedImage.src = imageSrc;
                        focusedImage.classList.remove(slideOutClass);
                        focusedImage.classList.add(slideInClass);
                        
                        setTimeout(() => {
                            focusedImage.classList.remove(slideInClass);
                            focusedImage.classList.add('active');
                            if (callback) callback();
                        }, 50);
                    }, 600);
                },

                typeTextWithBackspace: function(elementId, text, callback) {
                    this.isTyping = true;
                    const element = document.getElementById(elementId);
                    if (!element) return;
                    
                    const currentText = element.textContent;
                    
                    // First backspace the current text
                    if (currentText.length > 0) {
                        this.backspaceText(element, currentText, () => {
                            this.typeText(element, text, callback);
                        });
                    } else {
                        this.typeText(element, text, callback);
                    }
                },

                backspaceText: function(element, text, callback) {
                    let charIndex = text.length;
                    const backspaceSpeed = 30;
                    
                    const backspace = () => {
                        if (charIndex > 0) {
                            element.textContent = text.substring(0, charIndex - 1);
                            charIndex--;
                            setTimeout(backspace, backspaceSpeed);
                        } else {
                            if (callback) callback();
                        }
                    };
                    
                    setTimeout(backspace, 200);
                },

                typeText: function(element, text, callback) {
                    let charIndex = 0;
                    const typingSpeed = 50;
                    const variations = [40, 50, 60, 70, 45, 55]; // Variable typing speeds
                    
                    const type = () => {
                        if (charIndex < text.length) {
                            element.textContent += text.charAt(charIndex);
                            charIndex++;
                            const speed = variations[charIndex % variations.length] || typingSpeed;
                            setTimeout(type, speed);
                        } else {
                            this.isTyping = false;
                            if (callback) callback();
                        }
                    };
                    
                    setTimeout(type, 300);
                },

                nextSlide: function() {
                    if (this.isTyping || this.isTransitioning) return;
                    const nextIndex = (this.currentIndex + 1) % this.contentData.length;
                    this.showContent(nextIndex, 'next');
                },

                prevSlide: function() {
                    if (this.isTyping || this.isTransitioning) return;
                    const prevIndex = (this.currentIndex - 1 + this.contentData.length) % this.contentData.length;
                    this.showContent(prevIndex, 'prev');
                },

                startAutoSlide: function() {
                    this.intervalId = setInterval(() => {
                        if (!this.isTyping && !this.isTransitioning) {
                            this.nextSlide();
                        }
                    }, 8000);
                },

                stopAutoSlide: function() {
                    if (this.intervalId) {
                        clearInterval(this.intervalId);
                        this.intervalId = null;
                    }
                },

                bindEvents: function() {
                    // Arrow navigation - check if elements exist first
                    const prevArrow = document.getElementById('prev-arrow');
                    const nextArrow = document.getElementById('next-arrow');
                    
                    if (prevArrow) {
                        prevArrow.addEventListener('click', () => {
                            this.stopAutoSlide();
                            this.prevSlide();
                            setTimeout(() => this.startAutoSlide(), 2000);
                        });
                    }

                    if (nextArrow) {
                        nextArrow.addEventListener('click', () => {
                            this.stopAutoSlide();
                            this.nextSlide();
                            setTimeout(() => this.startAutoSlide(), 2000);
                        });
                    }

                    // Keyboard navigation
                    const keyHandler = (e) => {
                        if (!document.querySelector('.dynamic-content-page')) return;
                        
                        if (e.key === 'ArrowRight') {
                            this.stopAutoSlide();
                            this.nextSlide();
                            setTimeout(() => this.startAutoSlide(), 2000);
                        } else if (e.key === 'ArrowLeft') {
                            this.stopAutoSlide();
                            this.prevSlide();
                            setTimeout(() => this.startAutoSlide(), 2000);
                        }
                    };
                    
                    document.addEventListener('keydown', keyHandler);

                    // Pause on hover - check if container exists
                    const container = document.querySelector('.dynamic-content-page');
                    if (container) {
                        container.addEventListener('mouseenter', () => this.stopAutoSlide());
                        container.addEventListener('mouseleave', () => this.startAutoSlide());
                    }

                    // Handle window resize
                    const resizeHandler = () => {
                        if (!document.querySelector('.dynamic-content-page')) return;
                        const vh = window.innerHeight * 0.01;
                        document.documentElement.style.setProperty('--vh', `${vh}px`);
                    };
                    
                    window.addEventListener('resize', resizeHandler);
                    resizeHandler();

                    // Cleanup
                    window.addEventListener('beforeunload', () => {
                        this.stopAutoSlide();
                    });
                }
            };

            // Initialize when page loads
            document.addEventListener('DOMContentLoaded', function() {
                if (document.querySelector('.dynamic-content-page')) {
                    DynamicContentPage.init();
                }
            });

            // Debug mode
            if (window.DEBUG_MODE) {
                window.DynamicContentPage = DynamicContentPage;
            }
        })();
    </script>