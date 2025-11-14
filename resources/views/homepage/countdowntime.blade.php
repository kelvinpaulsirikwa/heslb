@php
    // Check if windowapplications data exists, otherwise use fallback values
    $hasData = isset($windowapplications) && is_array($windowapplications) && !empty($windowapplications);
    
    if ($hasData) {
        $programs = $windowapplications['programs'] ?? [];
        $window = $windowapplications['window'] ?? 'Loan Application Window';
        $opening = $windowapplications['opening_date'] ?? \Carbon\Carbon::now()->addMonth();
        $closing = $windowapplications['closing_date'] ?? \Carbon\Carbon::now()->addMonths(2);
        $academicyear = $windowapplications['academic_year'] ?? date('Y') . '/' . (date('Y') + 1);
        $applicationStatus = ($windowapplications['is_open'] ?? false) ? 'open' : 'closed';
        $countdownDate = isset($windowapplications['countdown_date']) ? $windowapplications['countdown_date']->toDateString() : \Carbon\Carbon::now()->addMonth()->toDateString();
        $nextOpeningDate = isset($windowapplications['next_opening_date']) ? $windowapplications['next_opening_date']->toDateString() : \Carbon\Carbon::now()->addMonth()->toDateString();
    } else {
        // Fallback values when no data is found
        $programs = ['all'];
        $window = 'Loan Application Window';
        
        // Calculate academic year based on current date
        $currentYear = date('Y');
        $currentMonth = date('n'); // 1-12
        
        // If we're in June-August, use current year/next year
        // If we're in September-May, use previous year/current year
        if ($currentMonth >= 6 && $currentMonth <= 8) {
            $academicyear = $currentYear . '/' . ($currentYear + 1);
        } else {
            $academicyear = ($currentYear - 1) . '/' . $currentYear;
        }
        
        // Check if we're in June-August period (applications open)
        $isJuneToAugust = ($currentMonth >= 6 && $currentMonth <= 8);
        
        if ($isJuneToAugust) {
            // Applications are open from June to August
            $opening = \Carbon\Carbon::create(date('Y'), 6, 1); // June 1st
            $closing = \Carbon\Carbon::create(date('Y'), 8, 31); // August 31st
            $applicationStatus = 'open';
            $countdownDate = $closing->toDateString();
            $nextOpeningDate = \Carbon\Carbon::create(date('Y') + 1, 6, 1)->toDateString(); // Next year June 1st
        } else {
            // Applications are closed outside June-August
            $opening = \Carbon\Carbon::create(date('Y') + 1, 6, 1); // Next year June 1st
            $closing = \Carbon\Carbon::create(date('Y') + 1, 8, 31); // Next year August 31st
            $applicationStatus = 'closed';
            $countdownDate = $opening->toDateString();
            $nextOpeningDate = $opening->toDateString();
        }
    }
    
    $now = \Carbon\Carbon::now();

    // Normalize programs to an array if a delimited string was provided
    if (is_string($programs)) {
        $programs = preg_split('/[,+|;]+/', $programs);
    }
    // Clean up values
    $programs = array_values(array_filter(array_map(function ($p) {
        return trim($p);
    }, is_array($programs) ? $programs : [])));
@endphp

{{-- Hidden countdown date for JS --}}
<div id="countdown-data" 
     data-countdown-date="{{ $countdownDate }}" 
     data-next-opening-date="{{ $nextOpeningDate }}"
     data-likely-opens-in="{{ __('loan.likely_opens_in') }}"
     data-time-remaining="{{ __('loan.time_remaining_open') }}"
     data-applications-closed="{{ __('loan.applications_closed') }}"
     data-deadline-passed="{{ __('loan.application_ended') }}"
     data-application-ended="{{ __('loan.application_ended') }}"
     style="display:none;">
</div>

<div class="education-loan-wrapper">
    {{-- Main Application Card --}}
    <div class="main-loan-card">
        @if($applicationStatus === 'open')
            <div class="main-content">
            <h1 class="loan-title">
                    {{ $academicyear }} <br> Loan Application Window
                    {{ __('loan.now_open') }}<br>
                    <div class="progrxam-list">
                        @if(in_array('all', $programs))
                           
                            
                        @else
                            @foreach(collect($programs)->chunk(2) as $programChunk)
                            <br>
                                <div class="program-row">
                                    @foreach($programChunk as $program)
                                        <span class="program-item">{{ strtoupper(str_replace('_', ' ', $program)) }}</span>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                </h1>
                           

                <p class="loan-description">
                  Please note that applications for the {{ $academicyear }} academic year are currently Open
                    <br>
                    <span class="deadline-highlight">Application Deadline: {{ $closing->format('d-F-Y') }}.</span>
                </p>
            </div>
            
            <a href="{{ config('links.heslb_systems.olams_pre_applicant') }}" class="apply-btn" target="_blank">
                {{ __('loan.click_apply') }}
            </a>
        @else
            <div class="main-content">
                <h1 class="loan-title">
                    {{ $academicyear }} <br> Loan Application Window
                    {{ __('loan.currently_closed') }}<br>
                </h1>
                <br>
                
                <p class="loan-description">
                    Please note that applications for the {{ $academicyear }} academic year are currently closed.
                    @if($now < $opening)
                        <span class="deadline-highlight">{{ __('loan.will_reopen_on', ['date' => $opening->format('d-F-Y')]) }}</span>
                    @else
                    <br><br><br>
                        <span ></span>
                    @endif
                </p>

                <a href="#"
                   class="apply-btn"
                   onclick="return false;"
                   aria-disabled="true"
                   style="pointer-events: none; opacity: 0.5; cursor: not-allowed; text-decoration: none;">
                   {{ __('loan.applications_closed') }}
                </a>
            </div>
        @endif
    </div>

    {{-- Clock Section --}}
    <div class="clock-section">
        <div class="clock-container">
            {{-- Analog Clock --}}
            <div class="analog-clock">
                <div class="clock-face">
                    <div class="clock-number">12</div>
                    <div class="clock-number">3</div>
                    <div class="clock-number">6</div>
                    <div class="clock-number">9</div>
                    
                    <div class="clock-hand hour-hand" id="hour-hand"></div>
                    <div class="clock-hand minute-hand" id="minute-hand"></div>
                    <div class="clock-hand second-hand" id="second-hand"></div>
                    <div class="clock-center"></div>
                </div>
            </div>
            
            {{-- Digital Countdown --}}
            <div class="digital-countdown">
                @if($applicationStatus === 'open')
                    <div class="countdown-title">{{ __('loan.application_deadline') }}</div>
                @else
                    <div class="countdown-title">
                        @if($now < $opening)
                            {{ __('loan.likely_opens_in') }}
                        @else
                            {{ __('loan.applications_closed') }}
                        @endif
                    </div>
                @endif
                
                <div class="countdown-digits" id="countdown-display">
                    <div class="digit-box">
                        <span class="digit-value" id="days">00</span>
                        <span class="digit-text">{{ __('timecount.days') }}</span>
                    </div>
                    <div class="digit-box">
                        <span class="digit-value" id="hours">00</span>
                        <span class="digit-text">{{ __('timecount.hours') }}</span>
                    </div>
                    <div class="digit-box">
                        <span class="digit-value" id="minutes">00</span>
                        <span class="digit-text">{{ __('timecount.minutes') }}</span>
                    </div>
                    <div class="digit-box">
                        <span class="digit-value" id="seconds">00</span>
                        <span class="digit-text">{{ __('timecount.seconds') }}</span>
                    </div>
                </div>
                
                <div class="deadline-info">
                    <i class="fas fa-clock"></i> 
                    @if($applicationStatus === 'open')
                        {{ __('loan.time_remaining_submit') }}
                    @else
                        @if($now < $opening)
                            {{ __('loan.time_remaining_open') }}
                        @else
                            {{ __('loan.application_ended') }}
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Right Sidebar Cards --}}
    <div class="right-sidebar">
        <a href="{{ route('loanapplication.guideline') }}" class="side-card">
            <div class="card-image compliance-image"></div>
            <div class="card-content">
                <h3 class="card-title">{{ __('loan.guidelines_title') }}</h3>
                <!-- @if(isset($currentGuideline) && $currentGuideline)
                    <p class="card-subtitle">{{ $currentGuideline->academic_year }}</p>
                @endif -->
                <div class="card-btn">
                    {{ __('loan.guidelines_btn') }}
                </div>
            </div>
        </a>

        <a href="{{ config('links.heslb_systems.olams_pre_applicant') }}" class="side-card repay-card" target="_blank">
            <div class="card-image repay-image"></div>
            <div class="card-content">
                <h3 class="card-title">{{ __('loan.olams_title') }}</h3>
                <div class="card-btn">
                    {{ __('loan.olams_btn') }}
                </div>
            </div>
        </a>
    </div>
</div>
