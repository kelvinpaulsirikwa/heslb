/**
 * Handle blocked user responses for AJAX requests
 * This script checks for 401 responses and redirects to login
 */

// Add this to your main JS file or include it globally
document.addEventListener('DOMContentLoaded', function() {
    // Global AJAX error handler
    if (typeof $ !== 'undefined') {
        // jQuery AJAX error handler
        $(document).ajaxError(function(event, xhr, settings) {
            if (xhr.status === 401) {
                const response = xhr.responseJSON;
                if (response && response.error && response.redirect) {
                    alert(response.error);
                    window.location.href = response.redirect;
                }
            } else if (xhr.status === 403) {
                const response = xhr.responseJSON;
                if (response && response.error) {
                    alert(response.error);
                }
            }
        });
    }

    // Fetch API error handler (for modern JS)
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args)
            .then(response => {
                if (response.status === 401) {
                    response.json().then(data => {
                        if (data.error && data.redirect) {
                            alert(data.error);
                            window.location.href = data.redirect;
                        }
                    }).catch(() => {
                        // Fallback if JSON parsing fails
                        window.location.href = '/login';
                    });
                } else if (response.status === 403) {
                    response.json().then(data => {
                        if (data.error) {
                            alert(data.error);
                        }
                    }).catch(() => {
                        alert('You are blocked and cannot make changes.');
                    });
                }
                return response;
            });
    };
});
