document.addEventListener('DOMContentLoaded', () => {
    const remainingTime = window.loginRemainingTime; // passed from Blade
    const loginButton = document.querySelector('.btn-primary');
    const originalText = 'LOGIN';

    if (loginButton && remainingTime > 0) {
        let seconds = remainingTime * 60;

        loginButton.disabled = true;

        function updateCountdown() {
            if (seconds <= 0) {
                loginButton.disabled = false;
                loginButton.textContent = originalText;
                return;
            }
            let minutes = Math.floor(seconds / 60);
            let secs = seconds % 60;
            loginButton.textContent = `LOCKED OUT (${minutes}m ${secs}s)`;
            seconds--;
            setTimeout(updateCountdown, 1000);
        }

        updateCountdown();
    }
});
