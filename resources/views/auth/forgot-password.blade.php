@extends('layouts.user')

@section('title', __('Forgot Password'))

@section('content')
<div class="max-w-md mx-auto mt-10 box box--stacked p-8">
    <h2 class="text-xl font-semibold mb-4">{{ __('Forgot your password?') }}</h2>
    <p class="text-slate-600 mb-6">{{ __('Enter your email address and we will send you a password reset link.') }}</p>

    @if (session('status'))
        <div class="alert alert-success mb-4">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="form-control" />
            @error('email')
                <p class="text-danger mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full">{{ __('Email Password Reset Link') }}</button>
    </form>
</div>
@endsection
