    document.addEventListener('DOMContentLoaded', function() {
        const lsfWrapper = document.querySelector('.loan-services-functions-wrapper');
        if (!lsfWrapper) return;

        let lsfCurrentActiveService = 0;
        const lsfTotalServices = document.querySelectorAll('.loan-services-functions-wrapper .lsf-service-panel').length;
        let lsfAutoAdvanceInterval;
        const isMobile = window.innerWidth <= 768;

        function lsfSetActiveService(index) {
            lsfCurrentActiveService = index;
            
            document.querySelectorAll('.loan-services-functions-wrapper .lsf-service-panel').forEach((panel, i) => {
                panel.classList.toggle('lsf-active', i === index);
            });
        }

        function lsfNextService() {
            lsfCurrentActiveService = (lsfCurrentActiveService + 1) % lsfTotalServices;
            lsfSetActiveService(lsfCurrentActiveService);
        }

        function lsfPreviousService() {
            lsfCurrentActiveService = (lsfCurrentActiveService - 1 + lsfTotalServices) % lsfTotalServices;
            lsfSetActiveService(lsfCurrentActiveService);
        }

        function lsfStartAutoAdvance() {
            if (isMobile) return;
            lsfAutoAdvanceInterval = setInterval(() => {
                lsfNextService();
            }, 6000);
        }

        function lsfStopAutoAdvance() {
            clearInterval(lsfAutoAdvanceInterval);
        }

        // Event Listeners
        const prevBtn = document.getElementById('lsfPrevBtn');
        const nextBtn = document.getElementById('lsfNextBtn');

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                lsfPreviousService();
                lsfStopAutoAdvance();
                setTimeout(lsfStartAutoAdvance, 3000);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                lsfNextService();
                lsfStopAutoAdvance();
                setTimeout(lsfStartAutoAdvance, 3000);
            });
        }

        // Panel click handlers
        document.querySelectorAll('.loan-services-functions-wrapper .lsf-service-panel').forEach((panel, index) => {
            panel.addEventListener('click', () => {
                lsfSetActiveService(index);
                lsfStopAutoAdvance();
                setTimeout(lsfStartAutoAdvance, 3000);
            });
        });

        // Auto-advance controls (desktop only)
        const lsfServicesGallery = document.querySelector('.loan-services-functions-wrapper .lsf-services-gallery');
        if (lsfServicesGallery && !isMobile) {
            lsfServicesGallery.addEventListener('mouseenter', lsfStopAutoAdvance);
            lsfServicesGallery.addEventListener('mouseleave', lsfStartAutoAdvance);
        }

        // Touch support
        let lsfTouchStartX = 0;
        let lsfTouchEndX = 0;

        if (lsfServicesGallery) {
            lsfServicesGallery.addEventListener('touchstart', (e) => {
                lsfTouchStartX = e.changedTouches[0].screenX;
                lsfStopAutoAdvance();
            });

            lsfServicesGallery.addEventListener('touchend', (e) => {
                lsfTouchEndX = e.changedTouches[0].screenX;
                const diff = lsfTouchStartX - lsfTouchEndX;
                
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        lsfNextService();
                    } else {
                        lsfPreviousService();
                    }
                }
                
                if (!isMobile) {
                    setTimeout(lsfStartAutoAdvance, 3000);
                }
            });
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            const wasMobile = isMobile;
            const newIsMobile = window.innerWidth <= 768;
            
            if (wasMobile !== newIsMobile) {
                lsfStopAutoAdvance();
                if (!newIsMobile) {
                    lsfStartAutoAdvance();
                }
            }
        });

        // Initialize
        lsfSetActiveService(0);
        lsfStartAutoAdvance();
    });
    