@php
$currentUrl = url()->current();
$currentPath = request()->path();

// Helper closures to mark active states
$matches = function ($url) use ($currentUrl) {
    try {
        return rtrim((string)$url, '/') === rtrim((string)$currentUrl, '/');
    } catch (\Throwable $e) { return false; }
};

// Check if current path starts with a specific pattern (for sections)
$matchesSection = function ($pattern) use ($currentPath) {
    return str_starts_with($currentPath, $pattern);
};

$dropdownActive = function ($children, $sectionPattern = null, $sectionPatterns = null) use ($matches, $matchesSection) {
    // First check if any child URL matches exactly
    foreach ($children as $child) {
        if ($matches($child['url'] ?? null)) return true;
    }
    
    // If section pattern is provided, check if current path matches the section
    if ($sectionPattern && $matchesSection($sectionPattern)) {
        return true;
    }
    
    // If section patterns array is provided, check if current path matches any of the patterns
    if ($sectionPatterns && is_array($sectionPatterns)) {
        foreach ($sectionPatterns as $pattern) {
            if ($matchesSection($pattern)) {
                return true;
            }
        }
    }
    
    return false;
};

$navLinks = [
    ['title' => __('menu.home'), 'icon' => 'fa-home', 'url' => route('home')],
    [
        'title' => __('menu.about_us'), 
        'icon' => 'fa-info-circle', 
        'url' => route('aboutus.visionmission'),
        'section_pattern' => 'about-us'
    ],
    [
        'title' => __('menu.publications'), 
        'icon' => 'fa-book', 
        'url' => route('publications.all'),
        'section_patterns' => ['publications', 'category']
    ],
    [
        'title' => __('menu.loan'),
        'icon' => 'fa-file-alt',
        'section_patterns' => ['about-loan-application', 'about-loan-repayment'],
        'children' => [
            ['title' => __('menu.loan_application'), 'url' => route('loanapplication.applicationlink')],
            ['title' => __('menu.loan_repayment'), 'url' => route('loanrepayment.repaymentlink'), ],
            ['title' => __('menu.samiascholarship'), 'url' => route('scholarships.index'), 'featured' => true],
        ]
    ], 
    [
        'title' => __('menu.repayloan'),
        'icon' => 'fa-file-alt',
        'section_patterns' => ['loanrepayment'],
        'children' => [
            ['title' => __('menu.beneficiary'), 'url' => config('links.heslb_systems.olams_login')],
            ['title' => __('menu.employer'), 'url' => config('links.heslb_systems.olams_employer_login'), 'featured' => true]
        ]
    ],
    ['title' => __('menu.news'), 'icon' => 'fa-newspaper', 'url' => route('newscenter.fetchnews')],
    ['title' => __('menu.contacts'), 'icon' => 'fa-phone', 'url' => route('contactus.formandregion')],
    ['title' => __('menu.emrejesho'), 'icon' => 'fa-globe', 'url' => route('menu.emrejesho')],
];
@endphp

<div id="header-container">

    {{-- Full Desktop Header --}}
    <header id="full-header" class="full-header">
        <div class="container-fluid">
            <div class="row align-items-center py-2">
                <div class="col-md-2">
                    <img src="{{ asset('images/static_files/coatofarms.png') }}" alt="Tanzania Coat of Arms" class="logo-left">
                </div>
                <div class="col-md-8 text-center">
                    <h2 class="country-title">{{ __('menu.tanzania') }}</h2>
                    <h3 class="organization-title">{{ __('menu.heslblongform') }}</h3>
                    <p class="tagline">{{ __('menu.heslbmotto') }}</p>
                </div>
                <div class="col-md-2 text-end">
                    <img src="{{ asset('images/static_files/heslblogos.png') }}" alt="HESLB Logo" class="logo-right">
                </div>
            </div>
        </div>

        {{-- Desktop Navigation --}}
        <nav class="desktop-nav d-none d-lg-block">
            <div class="container-fluid">
                <ul class="nav-menu">
                    @foreach ($navLinks as $i => $item)
                        @if ($i > 0)
                            <li class="nav-item nav-divider">|</li>
                        @endif
                        @if (isset($item['children']))
                            @php $parentActive = $dropdownActive($item['children'], $item['section_pattern'] ?? null, $item['section_patterns'] ?? null); @endphp
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle {{ $parentActive ? 'active' : '' }}">
                                    {{ $item['title'] }} <i class="fas fa-chevron-down ms-1"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach ($item['children'] as $child)
                                        <li>
                                            <a href="{{ $child['url'] }}" class="dropdown-item {{ ($matches($child['url']) ? 'active' : '') }} {{ $child['featured'] ?? false ? 'featured' : '' }}">
                                                {{ $child['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            @php 
                                $isActive = $matches($item['url']);
                                if (isset($item['section_pattern'])) {
                                    $isActive = $isActive || $matchesSection($item['section_pattern']);
                                }
                                if (isset($item['section_patterns']) && is_array($item['section_patterns'])) {
                                    foreach ($item['section_patterns'] as $pattern) {
                                        if ($matchesSection($pattern)) {
                                            $isActive = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <li class="nav-item">
                                <a href="{{ $item['url'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">{{ $item['title'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </header>

    {{-- Compact Header --}}
    <header id="compact-header" class="compact-header bg-dark text-light py-2">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center me-3">
                    <img src="{{ asset('images/static_files/heslblogo.png') }}" alt="HESLB" style="height: 50px;">
                </div>
                <div class="d-flex flex-column me-auto">
                    <h5 class="mb-0 compact-title">HESLB</h5>
                    <small class="compact-subtitle">Higher Education Students' Loans Board</small>
                </div>

                {{-- Compact Nav --}}
                <nav class="d-none d-lg-block">
                    <ul class="nav text-light compact-nav-list">
                        @foreach ($navLinks as $i => $item)
                            @if ($i > 0)
                                <li class="nav-item"><span class="nav-link px-1">|</span></li>
                            @endif
                            @if (isset($item['children']))
                                @php $parentActive = $dropdownActive($item['children'], $item['section_pattern'] ?? null, $item['section_patterns'] ?? null); @endphp
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-light px-2 {{ $parentActive ? 'active' : '' }}" href="#">
                                        {{ $item['title'] }} <i class="fas fa-chevron-down ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach ($item['children'] as $child)
                                            <li>
                                                <a class="dropdown-item {{ ($matches($child['url']) ? 'active' : '') }} {{ $child['featured'] ?? false ? 'featured' : '' }}" href="{{ $child['url'] }}">
                                                    {{ $child['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                @php 
                                    $isActive = $matches($item['url']);
                                    if (isset($item['section_pattern'])) {
                                        $isActive = $isActive || $matchesSection($item['section_pattern']);
                                    }
                                    if (isset($item['section_patterns']) && is_array($item['section_patterns'])) {
                                        foreach ($item['section_patterns'] as $pattern) {
                                            if ($matchesSection($pattern)) {
                                                $isActive = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <li class="nav-item">
                                    <a href="{{ $item['url'] }}" class="nav-link px-2 text-light {{ $isActive ? 'active' : '' }}">{{ $item['title'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>

                {{-- Mobile Button --}}
                <div class="d-lg-none">
                    <button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-label="Open mobile menu">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Overlay Nav --}}
    <div id="mobileNavOverlay" class="mobile-nav-overlay">
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <div class="mobile-nav-logo">
                    <img src="{{ asset('images/static_files/heslblogos.png') }}" alt="HESLB" class="nav-logo">
                    <div class="nav-title">
                        <h6>HIGHER EDUCATION STUDENTS'</h6>
                        <h6>LOANS BOARD</h6>
                        <small>Investing in the Future</small>
                    </div>
                </div>
                <button class="close-nav-btn" id="closeNavBtn" type="button" aria-label="Close mobile menu">&times;</button>
            </div>

            <nav class="mobile-nav-menu">
                @foreach ($navLinks as $item)
                    @if (isset($item['children']))
                        @php $parentActive = $dropdownActive($item['children'], $item['section_pattern'] ?? null, $item['section_patterns'] ?? null); @endphp
                        <div class="nav-section">
                            <div class="nav-section-header {{ $parentActive ? 'active' : '' }}" role="button" tabindex="0" aria-expanded="false" aria-controls="dropdown-{{ $loop->index }}">
                                <div class="nav-link">
                                    <i class="fas {{ $item['icon'] }} me-3"></i>{{ $item['title'] }}
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </div>
                            <div class="nav-subsection" id="dropdown-{{ $loop->index }}">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['url'] }}" class="nav-sublink {{ ($matches($child['url']) ? 'active' : '') }} {{ $child['featured'] ?? false ? 'featured' : '' }}">
                                        {{ $child['title'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @php 
                            $isActive = $matches($item['url']);
                            if (isset($item['section_pattern'])) {
                                $isActive = $isActive || $matchesSection($item['section_pattern']);
                            }
                            if (isset($item['section_patterns']) && is_array($item['section_patterns'])) {
                                foreach ($item['section_patterns'] as $pattern) {
                                    if ($matchesSection($pattern)) {
                                        $isActive = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="nav-section">
                            <a href="{{ $item['url'] }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                                <i class="fas {{ $item['icon'] }} me-3"></i>{{ $item['title'] }}
                            </a>
                        </div>
                    @endif
                @endforeach
            </nav>

            {{-- Social Media & Language Section --}}
            <div class="mobile-nav-footer">
                {{-- Social Media Icons --}}
                <div class="social-media-section">
                    <h6 class="footer-title">Follow Us</h6>
                    <div class="social-icons">
                        <a href="{{ config('links.social_media.twitter') }}" target="_blank" class="social-icon" aria-label="X">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="{{ config('links.social_media.facebook') }}" target="_blank" class="social-icon" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="{{ config('links.social_media.instagram') }}" target="_blank" class="social-icon" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="{{ config('links.social_media.youtube') }}" target="_blank" class="social-icon" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                {{-- Language Switcher --}}
                <div class="language-section">
                    <h6 class="footer-title">Language</h6>
                    <div class="language-buttons">
                        <a href="{{ url('lang/en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                            <span class="lang-flag">🇺🇸</span>
                            <span class="lang-text">English</span>
                        </a>
                        <a href="{{ url('lang/sw') }}" class="lang-btn {{ app()->getLocale() === 'sw' ? 'active' : '' }}">
                            <span class="lang-flag">🇹🇿</span>
                            <span class="lang-text">Kiswahili</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Extra space at bottom for better scrolling --}}
            <div style="height: 20px;"></div>
        </div>
    </div>
</div>

<div id="header-spacer"></div>
