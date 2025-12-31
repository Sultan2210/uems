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

        <form method="POST" action="{{ route('register') }}" class="ie-form" enctype="multipart/form-data">
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
                   placeholder="Enter email address">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>

          {{-- Matric Number / Staff Number --}}
          <div class="ie-field">
            <div class="ie-label">Matric Number / Staff Number</div>
            <input id="matric_no"
                   class="ie-input"
                   type="text"
                   name="matric_no"
                   value="{{ old('matric_no') }}"
                   placeholder="Enter matric number or staff number">
            <x-input-error :messages="$errors->get('matric_no')" class="mt-2" />
          </div>

          {{-- Kulliyyah --}}
          <div class="ie-field">
            <div class="ie-label">Kulliyyah</div>
            <input id="kulliyyah"
                   class="ie-input"
                   type="text"
                   name="kulliyyah"
                   value="{{ old('kulliyyah') }}"
                   placeholder="Enter your kulliyyah">
            <x-input-error :messages="$errors->get('kulliyyah')" class="mt-2" />
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
              <input type="radio" name="role" value="student" id="role_student"
                     {{ old('role','student')=='student' ? 'checked' : '' }}>
              Student
            </label>

            <label>
              <input type="radio" name="role" value="organizer" id="role_organizer"
                     {{ old('role')=='organizer' ? 'checked' : '' }}>
              Organizer
            </label>

            <label>
              <input type="radio" name="role" value="admin" id="role_admin"
                     {{ old('role')=='admin' ? 'checked' : '' }}>
              Admin
            </label>
          </div>

          {{-- Picture Upload (shown only when admin is selected) --}}
          <div class="ie-field" id="picture_upload_field" style="display: none;">
            <div class="ie-label">Matric Card Picture</div>
            <input id="picture"
                   class="ie-input"
                   type="file"
                   name="picture"
                   accept="image/*">
            <x-input-error :messages="$errors->get('picture')" class="mt-2" />
          </div>

          <button type="submit" class="ie-btn">Create Account</button>

          <div class="ie-bottom">
            Already have an account? <a href="{{ route('login') }}">Log In</a>
          </div>
        </form>

      </div>

    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const roleRadios = document.querySelectorAll('input[name="role"]');
      const pictureField = document.getElementById('picture_upload_field');
      const roleAdmin = document.getElementById('role_admin');

      // Show/hide picture upload based on role selection
      function togglePictureField() {
        if (roleAdmin.checked) {
          pictureField.style.display = 'block';
          document.getElementById('picture').setAttribute('required', 'required');
        } else {
          pictureField.style.display = 'none';
          document.getElementById('picture').removeAttribute('required');
        }
      }

      // Check initial state
      togglePictureField();

      // Add event listeners to all role radio buttons
      roleRadios.forEach(radio => {
        radio.addEventListener('change', togglePictureField);
      });
    });
  </script>
</x-guest-layout>
