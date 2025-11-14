@extends('aboutus.aboutus')

@section('aboutus-content')
<!-- Original Board of Directors Image - Display First -->
<div class="text-center">
    <img src="{{ asset('images/static_files/boardofdirector.jpg') }}" 
         alt="Board of Directors" 
         class="img-fluid rounded shadow" 
         style="max-width: 100%; height: auto; margin-bottom: 0;">
</div>

<!-- Dynamic Board Members CRUD Section -->
<div class="container my-4">
    @if($boardMembers->count() > 0)
        <h3 class="text-center mb-4 fw-semibold">Board Members</h3>
        <div class="row">
            @foreach($boardMembers as $member)
                <div class="col-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-top-container">
                            @if($member->image)
                                <img src="{{ asset('images/storage/' . $member->image) }}" 
                                     alt="{{ $member->name }}" 
                                     class="card-img-top" 
                                     style="width: 100%; height: auto;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-user fa-6x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title mb-3">{{ $member->name }}</h5>
                            @if($member->description)
                                <div class="card-text flex-grow-1">{!! $member->description !!}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
    <hr class="my-4">
    @endif
</div>
@endsection
