@extends('layouts.app')

@section('content')

<div class="container-fluid px-2 py-0">
    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-9">
            @include('newspage.partial.headers')
            @include('newspage.partial.category')
            @include('newspage.partial.newsgrid')
        </div>

        <!-- Government Sidebar -->
         
        <div class="col-lg-3">
            <br>
            @include('newspage.partial.sidebar')
        </div>
    </div>
</div>

@include('newspage.partial.script')
@endsection