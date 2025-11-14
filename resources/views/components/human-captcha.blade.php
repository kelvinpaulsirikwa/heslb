@php
    // Generate random category for this captcha session
    $categories = ['waterbodies', 'mountain', 'car', 'forest'];
    $selectedCategory = $categories[array_rand($categories)];
    
    // Create session for captcha validation
    session(['captcha_category' => $selectedCategory]);
    
    // Get all images for the selected category (always SVG)
    $categoryImages = [];
    $imageSuffixes = ['one', 'two', 'three'];
    foreach ($imageSuffixes as $suffix) {
        $imageBase = $selectedCategory . $suffix;
        $categoryImages[] = $imageBase . '.svg';
    }
    
    // Get other category images to fill the grid (always SVG)
    $otherImages = [];
    foreach ($categories as $cat) {
        if ($cat !== $selectedCategory) {
            foreach ($imageSuffixes as $suffix) {
                $imageBase = $cat . $suffix;
                $otherImages[] = $imageBase . '.svg';
            }
        }
    }
    
    // Shuffle and select 6 images from other categories
    shuffle($otherImages);
    $selectedOtherImages = array_slice($otherImages, 0, 6);
    
    // Combine and shuffle all images (3 from selected category + 6 from others = 9 total)
    $allImages = array_merge($categoryImages, $selectedOtherImages);
    shuffle($allImages);
    
    // Store the correct answers in session for validation
    session(['captcha_correct_answers' => $categoryImages]);
    
    // Store image indices for controller validation
    $correctIndices = [];
    foreach ($allImages as $index => $image) {
        if (in_array($image, $categoryImages)) {
            $correctIndices[] = $index;
        }
    }
    session(['image_captcha_cats' => $correctIndices]);
@endphp

<div class="captcha-container">
    <div class="captcha-header">
        <h4><i class="fas fa-shield-alt me-2"></i>{{ __('contactservice.human_verification') }}</h4>
        <p>{{ __('contactservice.select_all_category_images', ['category' => __('contactservice.' . $selectedCategory)]) }}</p>
    </div>
    
    <div class="captcha-grid">
        @foreach($allImages as $index => $image)
            @php
                $isCorrect = in_array($image, $categoryImages);
                // Image path is already set correctly in the array above
                $imagePath = '/images/storage/captureimages/' . $image;
            @endphp
            <div class="captcha-item" data-correct="{{ $isCorrect ? 'true' : 'false' }}" data-image="{{ $image }}" data-index="{{ $index }}">
                <img src="{{ $imagePath }}" alt="{{ __('contactservice.captcha_image_alt') }}" class="captcha-image">
                <div class="captcha-overlay">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="captcha-actions">
        <button type="button" class="btn btn-primary" id="verify-captcha" data-correct-indices="{{ json_encode($correctIndices) }}">{{ __('contactservice.verify_selection') }}</button>
        <button type="button" class="btn btn-secondary" id="refresh-captcha">{{ __('contactservice.refresh') }}</button>
    </div>
    
    <div class="captcha-result" id="captcha-result"></div>
    
    <!-- Hidden input to store selected images -->
    <input type="hidden" name="image_captcha_selection" id="image_captcha_selection" value="">
    <input type="hidden" name="captcha_category" value="{{ $selectedCategory }}">
</div>

<style>
.captcha-container {
    max-width: 400px;
    margin: 15px auto;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.captcha-header {
    text-align: center;
    margin-bottom: 15px;
}

.captcha-header h4 {
    color: #0c1f38;
    margin-bottom: 8px;
    font-size: 1.1rem;
}

.captcha-header p {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 0;
}

.captcha-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
    gap: 6px;
    margin-bottom: 15px;
}

.captcha-item {
    position: relative;
    aspect-ratio: 1;
    border: 2px solid #dee2e6;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    overflow: hidden;
}

.captcha-item:hover {
    border-color: #0e9bd5;
    transform: scale(1.01);
}

.captcha-item.selected {
    border-color: #28a745;
    box-shadow: 0 0 8px rgba(40, 167, 69, 0.25);
}

.captcha-item.selected .captcha-overlay {
    opacity: 1;
}

.captcha-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
    display: block;
}

.captcha-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(40, 167, 69, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.captcha-overlay i {
    color: white;
    font-size: 1.5rem;
}

.captcha-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-bottom: 12px;
}

.captcha-result {
    text-align: center;
    padding: 8px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
    display: none;
}

.captcha-result.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

.captcha-result.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

.captcha-result.warning {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
    display: block;
}

/* Responsive design for smaller screens */
@media (max-width: 480px) {
    .captcha-container {
        max-width: 320px;
        padding: 12px;
        margin: 10px auto;
    }
    
    .captcha-header h4 {
        font-size: 1rem;
    }
    
    .captcha-header p {
        font-size: 0.85rem;
    }
    
    .captcha-grid {
        gap: 4px;
        margin-bottom: 12px;
    }
    
    .captcha-actions {
        gap: 6px;
        margin-bottom: 10px;
    }
    
    .captcha-actions .btn {
        padding: 6px 12px;
        font-size: 0.85rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const captchaItems = document.querySelectorAll('.captcha-item');
    const verifyBtn = document.getElementById('verify-captcha');
    const refreshBtn = document.getElementById('refresh-captcha');
    const resultDiv = document.getElementById('captcha-result');
    const selectedInput = document.getElementById('image_captcha_selection');
    
    // Get translations from data attributes
    const translations = {
        selectAtLeastOne: "{{ __('contactservice.select_at_least_one') }}",
        verificationSuccessful: "{{ __('contactservice.verification_successful') }}",
        incorrectSelection: "{{ __('contactservice.incorrect_selection') }}"
    };
    
    let selectedItems = [];
    
    // Handle item selection
    captchaItems.forEach(item => {
        item.addEventListener('click', function() {
            const imageIndex = parseInt(this.dataset.index);
            const isSelected = this.classList.contains('selected');
            
            if (isSelected) {
                this.classList.remove('selected');
                selectedItems = selectedItems.filter(idx => idx !== imageIndex);
            } else {
                this.classList.add('selected');
                selectedItems.push(imageIndex);
            }
            
            selectedInput.value = JSON.stringify(selectedItems);
        });
    });
    
    // Verify selection
    verifyBtn.addEventListener('click', function() {
        // Get correct indices from button data attribute
        const correctIndicesData = JSON.parse(verifyBtn.getAttribute('data-correct-indices'));
        
        // Check if all correct indices are selected
        const allCorrectSelected = correctIndicesData.every(idx => selectedItems.includes(idx));
        const noIncorrectSelected = selectedItems.every(idx => correctIndicesData.includes(idx));
        
        if (selectedItems.length === 0) {
            showResult(translations.selectAtLeastOne, 'warning');
        } else if (allCorrectSelected && noIncorrectSelected) {
            showResult(translations.verificationSuccessful, 'success');
            // Enable form submission or proceed with action
            const form = document.querySelector('form');
            if (form) {
                form.classList.add('captcha-verified');
                // Dispatch custom event
                form.dispatchEvent(new Event('captcha-verified'));
            }
        } else {
            showResult(translations.incorrectSelection, 'error');
            // Clear selection
            captchaItems.forEach(item => item.classList.remove('selected'));
            selectedItems = [];
            selectedInput.value = '';
        }
    });
    
    // Refresh captcha
    refreshBtn.addEventListener('click', function() {
        window.location.reload();
    });
    
    function showResult(message, type) {
        resultDiv.textContent = message;
        resultDiv.className = `captcha-result ${type}`;
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            resultDiv.style.display = 'none';
        }, 3000);
    }
});
</script>


