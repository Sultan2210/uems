@push('styles')
<link href="{{ asset('assets/css/login.css') }}" rel="stylesheet" />
@endpush

<x-guest-layout>
  <div class="ie-login-wrap">
    <div class="ie-login-card">

      {{-- Left panel --}}
      <div class="ie-login-left">
        <div class="ie-brand">
          <img src="{{ asset('assets/img/favicon.png') }}" alt="iEvent">
          <span>iEvent</span>
        </div>
      </div>

      {{-- Right panel --}}
      <div class="ie-login-right">
        <a href="{{ url('/') }}" class="ie-close" aria-label="Close">&times;</a>

        <h1 class="ie-title">Login</h1>

        <form method="POST" action="{{ route('login') }}" class="ie-form">
          @csrf



          {{-- Email --}}
          <div class="ie-field">
            <div class="ie-label">Email or Matric Number</div>
            <input id="email"
                   type="text"
                   name="email"
                   class="ie-input"
                   value="{{ old('email') }}"
                   required autofocus
                   autocomplete="username"
                   placeholder="Enter email or matric number">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          {{-- Password --}}
          <div class="ie-field">
            <div class="ie-label">Password</div>
            <div class="ie-password-wrap">
              <input id="password"
                     type="password"
                     name="password"
                     class="ie-input"
                     required
                     autocomplete="current-password"
                     placeholder="Enter password">
              <span class="ie-eye">👁</span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          <button type="submit" class="ie-btn">Login</button>

          <div class="ie-bottom">
            Don’t have an account? <a href="{{ route('register') }}">Sign up</a>
          </div>
        </form>
      </div>

    </div>
  </div>
</x-guest-layout>
