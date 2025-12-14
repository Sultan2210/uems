@push('styles')
<link href="{{ asset('assets/css/register.css') }}" rel="stylesheet" />
@endpush

<x-guest-layout>
  <div class="ie-auth-wrap">
    <div class="ie-auth-card">

      {{-- LEFT --}}
      <div class="ie-auth-left">
        <div class="ie-brand">
          <img src="{{ asset('assets/img/favicon.png') }}" alt="iEvent">
          <span>iEvent</span>
        </div>
      </div>

      {{-- RIGHT --}}
      <div class="ie-auth-right">
        <h1 class="ie-title">Create Account</h1>

        <form method="POST" action="{{ route('register') }}" class="ie-form">
          @csrf

          {{-- Full Name --}}
          <div class="ie-field">
            <div class="ie-label">Full Name</div>
            <input id="name"
                   class="ie-input"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   placeholder="Enter your full name">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
          </div>

          {{-- Email --}}
          <div class="ie-field">
            <div class="ie-label">E-mail Address</div>
            <input id="email"
                   class="ie-input"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required autocomplete="username"
                   placeholder="Enter email or matric number">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          {{-- Password --}}
          <div class="ie-field">
            <div class="ie-label">Password</div>
            <input id="password"
                   class="ie-input"
                   type="password"
                   name="password"
                   required autocomplete="new-password"
                   placeholder="Enter password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          {{-- Confirm Password --}}
          <div class="ie-field">
            <div class="ie-label">Confirm Password</div>
            <input id="password_confirmation"
                   class="ie-input"
                   type="password"
                   name="password_confirmation"
                   required autocomplete="new-password"
                   placeholder="Confirm your password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
          </div>

          {{-- Role --}}
          <div class="ie-role-row">
            <label>
              <input type="radio" name="role" value="student"
                     {{ old('role','student')=='student' ? 'checked' : '' }}>
              Student
            </label>

            <label>
              <input type="radio" name="role" value="organizer"
                     {{ old('role')=='organizer' ? 'checked' : '' }}>
              Organizer
            </label>

            <label>
              <input type="radio" name="role" value="admin"
                     {{ old('role')=='admin' ? 'checked' : '' }}>
              Admin
            </label>
          </div>

          <button type="submit" class="ie-btn">Create Account</button>

          <div class="ie-bottom">
            Already have an account? <a href="{{ route('login') }}">Log In</a>
          </div>
        </form>

      </div>

    </div>
  </div>
</x-guest-layout>
