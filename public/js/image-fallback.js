/**
 * Image Fallback Handler
 * Handles cases where profile images are not found and fallback to default image
 */
function handleImageError(img) {
    // Set fallback image
    img.src = '/images/static_files/nodp.png';
    // Prevent infinite loop if fallback image also fails
    img.onerror = null;
}

// Auto-apply to all images with onerror attribute when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[onerror*="handleImageError"]');
    images.forEach(function(img) {
        img.onerror = function() {
            handleImageError(this);
        };
    });
});
