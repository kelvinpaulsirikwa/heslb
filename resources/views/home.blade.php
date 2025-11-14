@extends('layouts.app')

@section('content')

    <!-- Your main page content -->
         @include('homepage.welcomepage')

        @include('homepage.countdowntime')
        @include('ceoremarks.ceoremarks')
        @include('homepage.homeqns')
        @include('homepage.ourproduct')

    <!-- Include the partner section here -->
    @include('visitorcounter.visitorcouter')
    @include('homepage.feedbackpage')
    @include('partials.partner')

@endsection
