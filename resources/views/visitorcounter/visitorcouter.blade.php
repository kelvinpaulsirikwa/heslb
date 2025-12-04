<style>
    /* Custom calendar SVG icon with integrated text */
    .custom-calendar-icon {
        display: inline-block;
        width: 64px;
        height: 64px;
        margin: 0 auto;
    }
    
    .custom-calendar-icon svg {
        width: 100%;
        height: 100%;
    }
    
    .calendar-day-text,
    .calendar-month-text {
        font-family: Arial, sans-serif;
        font-weight: bold;
        font-size: 6px; /* Reduced from 7px to 6px */
        fill: white;
        text-anchor: middle;
    }
</style>

<section class="visitor-stats-hero">
    <div class="container text-white text-center">
        <h1 class="display-4 font-weight-bold mb-3">{{ __('stats.title') }}</h1>
                <p class="lead mb-5">
            {{ __('stats.description') }}
        </p>

        <div class="row justify-content-center mb-4">
            @foreach ($stats as $stat)
                <div class="col-md-2 col-6 mb-4">
                    <div class="stat-box">
                        <div class="mb-2 position-relative">
                            @if(isset($stat['day']))
                                <!-- Custom calendar icon with day text -->
                                <div class="custom-calendar-icon">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- Calendar base -->
                                        <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                        <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2"/>
                                        <!-- Calendar rings -->
                                        <circle cx="7" cy="7" r="1" fill="currentColor"/>
                                        <circle cx="12" cy="7" r="1" fill="currentColor"/>
                                        <circle cx="17" cy="7" r="1" fill="currentColor"/>
                                        <!-- Day text inside calendar -->
                                        <text x="12" y="18" class="calendar-day-text">{{ $stat['day'] }}</text>
                                    </svg>
                                </div>
                            @elseif(isset($stat['month']))
                                <!-- Custom calendar icon with month text -->
                                <div class="custom-calendar-icon">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- Calendar base -->
                                        <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                        <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2"/>
                                        <!-- Calendar rings -->
                                        <circle cx="7" cy="7" r="1" fill="currentColor"/>
                                        <circle cx="12" cy="7" r="1" fill="currentColor"/>
                                        <circle cx="17" cy="7" r="1" fill="currentColor"/>
                                        <!-- Month text inside calendar -->
                                        <text x="12" y="18" class="calendar-month-text">{{ $stat['month'] }}</text>
                                    </svg>
                                </div>
                            @else
                                <!-- Regular calendar icon -->
                                <i class="bi {{ $stat['icon'] }} fs-1"></i>
                            @endif
                        </div>
                        <h2 class="mt-2 mb-0 font-weight-bold">
                            <span class="count" data-target="{{ $stat['value'] }}">0</span>
                        </h2>
                        <div class="text-light mt-1">{{ $stat['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
