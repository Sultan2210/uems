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

          {{-- Role Selection (shown when multiple roles exist) --}}
          @if(session('multiple_roles'))
            <div class="ie-field">
              <div class="ie-label">Select Login Type</div>
              <div class="ie-role-selection" style="display: flex; gap: 20px; margin-bottom: 15px;">
                @if(session('has_organizer'))
                  <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="login_role" value="organizer" required 
                           style="margin-right: 8px;" {{ old('login_role') === 'organizer' ? 'checked' : '' }}>
                    <span>Organizer</span>
                  </label>
                @endif
                @if(session('has_student'))
                  <label style="display: flex; align-items: center; cursor: pointer;">
                    <input type="radio" name="login_role" value="student" required 
                           style="margin-right: 8px;" {{ old('login_role') === 'student' ? 'checked' : '' }}>
                    <span>Student</span>
                  </label>
                @endif
              </div>
              <x-input-error :messages="$errors->get('login_role')" class="mt-2" />
              <div style="margin-bottom: 15px; padding: 10px; background-color: #f0f0f0; border-radius: 5px; font-size: 14px;">
                You have accounts registered as both organizer and student. Please select which account you want to log in with.
              </div>
            </div>
          @endif

          {{-- Email --}}
          <div class="ie-field">
            <div class="ie-label">Email or Matric Number</div>
            <input id="email"
                   type="text"
                   name="email"
                   class="ie-input"
                   value="{{ old('email') }}"
                   required {{ !session('multiple_roles') ? 'autofocus' : '' }}
                   autocomplete="username"
                   placeholder="Enter email or matric number"
                   {{ session('multiple_roles') ? 'readonly' : '' }}>
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
                     placeholder="Enter password"
                     {{ session('multiple_roles') ? 'autofocus' : '' }}>
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
