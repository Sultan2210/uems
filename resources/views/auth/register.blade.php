<head>
    <!-- Link to register-specific CSS -->
    <link href="{{ asset('assets/css/register.css') }}" rel="stylesheet" />
</head>

<x-guest-layout>
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
        <!-- Left Section (Logo) -->
        <div class="col-md-6 bg-dark text-white d-flex flex-column justify-content-center p-4 rounded-start">
            <div class="logo mb-4">
                <!-- Logo Image (Ensure the correct path for your logo) -->
<img src="{{ asset('assets/img/favicon.png') }}" alt="iEvent Logo" class="img-fluid">
            </div>
            <h1>Create Account</h1>
        </div>

        <!-- Right Section (Form) -->
        <div class="col-md-6 p-4 bg-light">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input
                        id="name"
                        class="form-control"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Enter your full name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('E-mail Address')" />
                    <x-text-input
                        id="email"
                        class="form-control"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="username"
                        placeholder="Enter email or matric number" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input
                        id="password"
                        class="form-control"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Enter password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input
                        id="password_confirmation"
                        class="form-control"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Select your role:</label>
                    <div class="d-flex">
                        <label class="inline-flex items-center">
                            <input type="radio" name="role" value="student" checked>
                            <span class="ml-2">Student</span>
                        </label>
                        <label class="inline-flex items-center mx-4">
                            <input type="radio" name="role" value="organizer">
                            <span class="ml-2">Organizer</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="role" value="admin">
                            <span class="ml-2">Admin</span>
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <x-primary-button class="btn btn-dark w-100">
                        {{ __('Register') }}
                    </x-primary-button>
                    <a class="text-sm text-muted" href="{{ route('login') }}">
                        {{ __('Already have an account? Log In') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
