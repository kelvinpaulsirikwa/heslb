document.addEventListener('DOMContentLoaded', function () {
    // Get countdown date from the hidden HTML element
    const countdownEl = document.getElementById('countdown-data');
    const countdownDate = countdownEl ? countdownEl.getAttribute('data-countdown-date') : null;
    const deadline = countdownDate ? new Date(countdownDate + 'T23:59:59').getTime() : null;

    // Get language translations from data attributes or global variables
    const translations = {
        likelyOpensIn: countdownEl ? countdownEl.getAttribute('data-likely-opens-in') || 'Likely opens in' : 'Likely opens in',
        timeRemaining: countdownEl ? countdownEl.getAttribute('data-time-remaining') || 'Time remaining: expected to open in' : 'Time remaining: expected to open in',
        applicationsClosed: countdownEl ? countdownEl.getAttribute('data-applications-closed') || 'Applications Closed' : 'Applications Closed',
        deadlinePassed: countdownEl ? countdownEl.getAttribute('data-deadline-passed') || 'Application Deadline Passed' : 'Application Deadline Passed',
        applicationEnded: countdownEl ? countdownEl.getAttribute('data-application-ended') || 'Application period has ended' : 'Application period has ended'
    };

    // --------------------------
    // Update Clock Hands - Real time clock
    // --------------------------
    function updateClock() {
        const now = new Date();
        const hours = now.getHours() % 12;
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        const hourAngle = (hours * 30) + (minutes * 0.5);
        const minuteAngle = minutes * 6;
        const secondAngle = seconds * 6;

        const hourHand = document.getElementById('hour-hand');
        const minuteHand = document.getElementById('minute-hand');
        const secondHand = document.getElementById('second-hand');

        if (hourHand) hourHand.style.transform = `rotate(${hourAngle}deg)`;
        if (minuteHand) minuteHand.style.transform = `rotate(${minuteAngle}deg)`;
        if (secondHand) secondHand.style.transform = `rotate(${secondAngle}deg)`;
    }

    // --------------------------
    // Update Countdown
    // --------------------------
  function updateCountdown() {
    if (!deadline) return;

    const now = new Date().getTime();
    const timeLeft = deadline - now;

    const countdownDisplay = document.getElementById('countdown-display');
    const countdownTitle = document.querySelector('.countdown-title');
    const deadlineInfo = document.querySelector('.deadline-info');

    if (timeLeft > 0) {
        // Show countdown normally
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        updateDigitWithAnimation('days', days);
        updateDigitWithAnimation('hours', hours);
        updateDigitWithAnimation('minutes', minutes);
        updateDigitWithAnimation('seconds', seconds);

        // Ensure countdown digits are visible
        if (countdownDisplay) {
            countdownDisplay.style.display = 'grid';
        }
    } else {
        // Deadline passed — check if we can count until next opening
        // Assume you have a separate "nextOpeningDate" available via data attribute or hardcode here
        // For example, you can store next opening date in another hidden element or pass it from backend

        // For demo, let's assume next opening date is in 'data-next-opening-date' attribute
        const nextOpeningEl = document.getElementById('countdown-data');
        const nextOpeningDateStr = nextOpeningEl ? nextOpeningEl.getAttribute('data-next-opening-date') : null;

        if (nextOpeningDateStr) {
            const nextOpeningTime = new Date(nextOpeningDateStr + 'T00:00:00').getTime();
            const timeUntilOpen = nextOpeningTime - now;

            if (timeUntilOpen > 0) {
                // Count down until next opening
                const days = Math.floor(timeUntilOpen / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeUntilOpen % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeUntilOpen % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeUntilOpen % (1000 * 60)) / 1000);

                updateDigitWithAnimation('days', days);
                updateDigitWithAnimation('hours', hours);
                updateDigitWithAnimation('minutes', minutes);
                updateDigitWithAnimation('seconds', seconds);

                if (countdownTitle) countdownTitle.textContent = translations.likelyOpensIn;
                if (deadlineInfo) deadlineInfo.textContent = translations.timeRemaining;

                if (countdownDisplay) {
                    countdownDisplay.style.display = 'grid';
                }
                return;
            }
        }

        // If no next opening date or next opening already passed, show deadline passed message
        if (countdownDisplay) {
            countdownDisplay.innerHTML =
                '<div style="grid-column: span 4; color: #e74c3c; font-weight: bold; text-align: center; padding: 10px;">' +
                '<i class="fas fa-exclamation-triangle"></i> ' + translations.deadlinePassed + '</div>';
        }

        if (countdownTitle) countdownTitle.textContent = translations.applicationsClosed;
        if (deadlineInfo) deadlineInfo.textContent = translations.applicationEnded;
    }
}


    // --------------------------
    // Digit Animation
    // --------------------------
    function updateDigitWithAnimation(elementId, value) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const newValue = value.toString().padStart(2, '0');

        if (element.textContent !== newValue) {
            element.style.transform = 'scale(1.2)';
            element.style.color = '#f39c12';
            element.textContent = newValue;

            setTimeout(() => {
                element.style.transform = 'scale(1)';
                element.style.color = 'white';
            }, 200);
        }
    }

    // --------------------------
    // Initialize and start intervals
    // --------------------------
    updateClock();
    updateCountdown();
    setInterval(updateClock, 1000);
    setInterval(updateCountdown, 1000);

    // --------------------------
    // Interactive elements hover effect
    // --------------------------
    const interactiveElements = document.querySelectorAll('.apply-btn, .side-card, .card-btn');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px) scale(1.02)';
        });
        element.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // --------------------------
    // Prevent horizontal scroll on mobile
    // --------------------------
    function preventHorizontalScroll() {
        const wrapper = document.querySelector('.education-loan-wrapper');
        if (wrapper && wrapper.scrollWidth > wrapper.clientWidth) {
            wrapper.style.overflowX = 'hidden';
        }
    }
    window.addEventListener('resize', preventHorizontalScroll);
    preventHorizontalScroll();

    // --------------------------
    // Touch interactions for mobile
    // --------------------------
    let touchStartX = 0;
    let touchStartY = 0;

    document.addEventListener('touchstart', function (e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    });

    document.addEventListener('touchmove', function (e) {
        if (!touchStartX || !touchStartY) return;

        let touchEndX = e.touches[0].clientX;
        let touchEndY = e.touches[0].clientY;
        let diffX = touchStartX - touchEndX;
        let diffY = touchStartY - touchEndY;

        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffY) < 50) {
            e.preventDefault();
        }

        touchStartX = null;
        touchStartY = null;
    });
});

// --------------------------
// Orientation change optimization
// --------------------------
window.addEventListener('orientationchange', function () {
    setTimeout(function () {
        window.scrollTo(0, 0);

        const clockFace = document.querySelector('.clock-face');
        if (clockFace) {
            clockFace.style.display = 'none';
            clockFace.offsetHeight; // Trigger reflow
            clockFace.style.display = '';
        }

        const analogClock = document.querySelector('.analog-clock');
        if (analogClock) {
            const container = analogClock.closest('.clock-container');
            if (container) {
                const containerWidth = container.offsetWidth - 50;
                const maxSize = Math.min(containerWidth, 280);

                if (window.innerWidth <= 480) {
                    analogClock.style.width = Math.min(maxSize, 160) + 'px';
                    analogClock.style.height = Math.min(maxSize, 160) + 'px';
                } else if (window.innerWidth <= 768) {
                    analogClock.style.width = Math.min(maxSize, 200) + 'px';
                    analogClock.style.height = Math.min(maxSize, 200) + 'px';
                }
            }
        }
    }, 100);
});

// --------------------------
// Fix logo sizing on window resize
// --------------------------
window.addEventListener('resize', function () {
    clearTimeout(window.resizeTimeout);
    window.resizeTimeout = setTimeout(function () {
        const clockFace = document.querySelector('.clock-face');
        if (clockFace) {
            clockFace.style.transform = 'scale(0.999)';
            setTimeout(() => {
                clockFace.style.transform = '';
            }, 10);
        }
    }, 150);
});

// --------------------------
// Performance optimization for animations
// --------------------------
if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const style = document.createElement('style');
    style.textContent = `
        .education-loan-wrapper * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    `;
    document.head.appendChild(style);
}
