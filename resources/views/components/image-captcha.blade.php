@php
    // Build a 3-of-9 image captcha using icon placeholders (cats vs others)
    $totalTiles = 9;
    $catCount = 3;
    $indices = range(0, $totalTiles - 1);
    shuffle($indices);
    $catIndices = array_slice($indices, 0, $catCount);
    sort($catIndices);
    session(['image_captcha_cats' => $catIndices, 'image_captcha_generated_at' => now()->timestamp]);

    // Non-cat icons to mix in
    $otherIcons = ['fa-dog', 'fa-fish', 'fa-hippo', 'fa-crow', 'fa-dragon', 'fa-frog'];
@endphp

<style>
    .captcha-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .captcha-tile { position: relative; border: 2px solid #e5e7eb; border-radius: 10px; padding: 18px; text-align: center; background: #ffffff; cursor: pointer; transition: all .2s ease; }
    .captcha-tile:hover { box-shadow: 0 6px 16px rgba(0,0,0,.08); transform: translateY(-1px); }
    .captcha-tile.selected { border-color: #0e9bd5; background: linear-gradient(180deg, #f0fbff, #e6f7ff); }
    .captcha-icon { font-size: 34px; color: #1f2937; }
    .captcha-instruction { margin-bottom: 10px; font-weight: 600; }
    .captcha-error { color: #dc3545; font-size: .9rem; display: none; margin-top: 6px; }
</style>

<div class="card form-card">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-shield-cat me-2"></i>Human Verification (Select all tiles with a cat)
    </div>
    <div class="card-body">
        <div class="captcha-instruction">Please select the 3 tiles that contain a cat.</div>
        <div class="captcha-grid" id="imageCaptchaGrid">
            @for ($i = 0; $i < $totalTiles; $i++)
                @php
                    $isCat = in_array($i, $catIndices, true);
                    $icon = $isCat ? 'fa-cat' : $otherIcons[$i % count($otherIcons)];
                @endphp
                <div class="captcha-tile" data-index="{{ $i }}" data-iscat="{{ $isCat ? '1' : '0' }}">
                    <i class="fas {{ $icon }} captcha-icon" aria-hidden="true"></i>
                </div>
            @endfor
        </div>

        <input type="hidden" name="image_captcha_selection" id="imageCaptchaSelection" value="[]">
        <div class="captcha-error" id="imageCaptchaError">Please select exactly the 3 tiles that contain a cat.</div>
    </div>
</div>

<script>
    (function(){
        var grid = document.getElementById('imageCaptchaGrid');
        if (!grid) return;
        var selectionInput = document.getElementById('imageCaptchaSelection');
        var errorEl = document.getElementById('imageCaptchaError');
        var selected = new Set();

        grid.addEventListener('click', function(e){
            var tile = e.target.closest('.captcha-tile');
            if (!tile) return;
            var idx = tile.getAttribute('data-index');
            if (selected.has(idx)) { selected.delete(idx); tile.classList.remove('selected'); }
            else { selected.add(idx); tile.classList.add('selected'); }
            selectionInput.value = JSON.stringify(Array.from(selected).map(Number).sort(function(a,b){return a-b;}));
            if (errorEl) errorEl.style.display = 'none';
        });
    })();
</script>


