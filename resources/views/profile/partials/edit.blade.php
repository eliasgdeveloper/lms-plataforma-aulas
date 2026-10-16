<link rel="stylesheet" href="{{ asset('pages/profile/style.css') }}">

<div class="profile-container">
    <h1>{{ __('Profile') }}</h1>

    <div class="profile-sections">
        <section class="profile-section">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="profile-section">
            @include('profile.partials.update-password-form')
        </section>

        <section class="profile-section">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</div>

<script src="{{ asset('pages/profile/script.js') }}" defer></script>
