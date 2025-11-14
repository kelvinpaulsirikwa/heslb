@extends('aboutus.aboutus')

@section('aboutus-content')
<div class="container my-2">
    <div class="p-4">
        <ol class="fs-5" style="line-height: 1.8;">
            @foreach(__('aboutus.functions') as $function)
                <li>{{ $function }}</li>
            @endforeach
        </ol>
    </div>
</div>
@endsection
