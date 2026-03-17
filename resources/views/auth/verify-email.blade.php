@extends('layouts.user')

@section('title', __('Verify Email'))

@section('content')
<div class="max-w-lg mx-auto mt-10 box box--stacked p-8">
    <h2 class="text-xl font-semibold mb-4">{{ __('Verify Your Email Address') }}</h2>
    <p class="text-slate-600 mb-6">
        {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">{{ __('Log Out') }}</button>
        </form>
    </div>
</div>
@endsection
