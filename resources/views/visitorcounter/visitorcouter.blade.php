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
                        <div class="mb-2">
                            <i class="bi {{ $stat['icon'] }} fs-1"></i>
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
