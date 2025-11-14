@extends('aboutus.aboutus')

@section('aboutus-content')
<style>
    .mission-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
        align-items: stretch;
    }
    .mission-image-container,
    .mission-card-container {
        flex: 1 1 48%;
        transition: transform 1.5s ease-in-out, flex-basis 1.5s ease-in-out;
    }
    .mission-image-container img {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 1.5s ease-in-out;
    }
    .mission-card {
        background-color: #0e9bd5 ;
        color: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: transform 1.5s ease-in-out;
    }
    .scale-large { transform: scale(1.1); }
    .scale-small { transform: scale(0.9); }
</style>

<div class="container my-5">
    {{-- Mission Section --}}
    <div class="mission-wrapper mb-5">
        <div class="mission-image-container" id="mission-image-container">
            <img src="{{ asset('images/static_files/bg3.jpg') }}" alt="Mission Image" id="mission-image">
        </div>
        <div class="mission-card-container" id="mission-card-container">
            <div class="mission-card" id="mission-card">
                <h2 class="fw-bold">{{ __('messages.our_mission') }}</h2>
                <p class="mt-3">{{ __('messages.mission_text') }}</p>
            </div>
        </div>
    </div>

    {{-- Strategic Pillars --}}
    <div class="mb-5">
        <h4 class="fw-bold">{{ __('messages.strategic_pillars') }}</h4>
        <hr class="border-3 border-primary opacity-75" style="width: 60px;">
        <ol class="mt-3 ps-3">
            <li>{{ __('messages.pillar_1') }}</li>
            <li>{{ __('messages.pillar_2') }}</li>
            <li>{{ __('messages.pillar_3') }}</li>
        </ol>
    </div>

    {{-- Core Values --}}
    <div>
        <h4 class="fw-bold">{{ __('messages.core_values') }}</h4>
        <hr class="border-3 border-primary opacity-75" style="width: 60px;">
        <div class="row mt-3">
            <div class="col-md-6">
                <ol class="ps-3">
                    <li>{{ __('messages.value_1') }}</li>
                    <li>{{ __('messages.value_2') }}</li>
                    <li>{{ __('messages.value_3') }}</li>
                </ol>
            </div>
            <div class="col-md-6">
                <ol start="4" class="ps-3">
                    <li>{{ __('messages.value_4') }}</li>
                    <li>{{ __('messages.value_5') }}</li>
                    <li>{{ __('messages.value_6') }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Animation Script --}}
<script>
    const img = document.getElementById('mission-image');
    const card = document.getElementById('mission-card');
    let zoomState = true;

    setInterval(() => {
        img.classList.remove('scale-large', 'scale-small');
        card.classList.remove('scale-large', 'scale-small');

        if (zoomState) {
            img.classList.add('scale-large');
            card.classList.add('scale-small');
        } else {
            img.classList.add('scale-small');
            card.classList.add('scale-large');
        }

        zoomState = !zoomState;
    }, 1500);
</script>
@endsection
