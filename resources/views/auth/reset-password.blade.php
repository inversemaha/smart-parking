@extends('layouts.user')

@section('title', __('Reset Password'))

@section('content')
<div class="max-w-md mx-auto mt-10 box box--stacked p-8">
    <h2 class="text-xl font-semibold mb-6">{{ __('Reset Password') }}</h2>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required class="form-control" />
            @error('email')
                <p class="text-danger mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" name="password" type="password" required class="form-control" />
            @error('password')
                <p class="text-danger mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="form-control" />
        </div>

        <button type="submit" class="btn btn-primary w-full">{{ __('Reset Password') }}</button>
    </form>
</div>
@endsection
