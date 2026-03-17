@extends('layouts.user')

@section('title', __('Confirm Password'))

@section('content')
<div class="max-w-md mx-auto mt-10 box box--stacked p-8">
    <h2 class="text-xl font-semibold mb-4">{{ __('Confirm Password') }}</h2>
    <p class="text-slate-600 mb-6">{{ __('Please confirm your password before continuing.') }}</p>

    <form method="POST" action="{{ url('confirm-password') }}" class="space-y-4">
        @csrf

        <div>
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" name="password" type="password" required autocomplete="current-password" class="form-control" />
            @error('password')
                <p class="text-danger mt-1 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full">{{ __('Confirm') }}</button>
    </form>
</div>
@endsection
