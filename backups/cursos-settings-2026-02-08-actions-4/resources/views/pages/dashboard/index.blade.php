@extends('layouts.page')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/dashboard/style.css') }}">
@endpush

<div class="dashboard-container">
    <h1>{{ __('Dashboard') }}</h1>
    
    <div class="dashboard-content">
        <div class="welcome-card">
            <h2>{{ __("You're logged in!") }}</h2>
            <p>Welcome to your dashboard. Select your role area to continue.</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('pages/dashboard/script.js') }}" defer></script>
@endpush
@endsection
