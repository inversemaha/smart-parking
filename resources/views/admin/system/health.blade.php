@extends('layouts.app')

@section('title', __('general.system_health'))
@section('page-title', __('general.system_health'))

@php
$breadcrumb = [
    ['title' => __('general.admin'), 'url' => route('admin.dashboard.index')],
    ['title' => __('general.system_health')]
];
@endphp

@section('content')
<div class="grid grid-cols-12 gap-6">
    <!-- System Health Overview -->
    <div class="col-span-12 xl:col-span-8">
        <div class="box p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium">{{ __('general.system_health') }}</h3>
                <button onclick="refreshHealth()" class="btn btn-outline-primary btn-sm">
                    <i data-lucide="refresh-ccw" class="w-4 h-4 mr-2"></i>
                    {{ __('general.refresh') }}
                </button>
            </div>

            <div class="space-y-6">
                <!-- Database Health -->
                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $health['database']['status'] === 'healthy' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                            <i data-lucide="database" class="w-5 h-5 {{ $health['database']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium">{{ __('general.database') }}</h4>
                            <p class="text-sm text-slate-600">{{ $health['database']['message'] }}</p>
                        </div>
                    </div>
                    <span class="flex items-center {{ $health['database']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        <div class="w-3 h-3 {{ $health['database']['status'] === 'healthy' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-2"></div>
                        {{ ucfirst($health['database']['status']) }}
                    </span>
                </div>

                <!-- Cache Health -->
                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $health['cache']['status'] === 'healthy' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                            <i data-lucide="hard-drive" class="w-5 h-5 {{ $health['cache']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium">{{ __('general.cache') }}</h4>
                            <p class="text-sm text-slate-600">{{ $health['cache']['message'] }}</p>
                        </div>
                    </div>
                    <span class="flex items-center {{ $health['cache']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        <div class="w-3 h-3 {{ $health['cache']['status'] === 'healthy' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-2"></div>
                        {{ ucfirst($health['cache']['status']) }}
                    </span>
                </div>

                <!-- Queue Health -->
                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 {{ $health['queue']['status'] === 'healthy' ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                            <i data-lucide="layers" class="w-5 h-5 {{ $health['queue']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium">{{ __('general.queue') }}</h4>
                            <p class="text-sm text-slate-600">{{ $health['queue']['message'] }}</p>
                        </div>
                    </div>
                    <span class="flex items-center {{ $health['queue']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        <div class="w-3 h-3 {{ $health['queue']['status'] === 'healthy' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-2"></div>
                        {{ ucfirst($health['queue']['status']) }}
                    </span>
                </div>

                <!-- Storage Health -->
                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-{{ $health['storage']['status'] === 'healthy' ? 'green' : ($health['storage']['status'] === 'warning' ? 'yellow' : 'red') }}-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="server" class="w-5 h-5 text-{{ $health['storage']['status'] === 'healthy' ? 'green' : ($health['storage']['status'] === 'warning' ? 'yellow' : 'red') }}-600"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium">{{ __('general.storage') }}</h4>
                            <p class="text-sm text-slate-600">{{ $health['storage']['message'] }}</p>
                            @if(isset($health['storage']['details']))
                                <div class="text-xs text-slate-500 mt-1">
                                    Free: {{ $health['storage']['details']['free'] }} |
                                    Total: {{ $health['storage']['details']['total'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <span class="flex items-center text-{{ $health['storage']['status'] === 'healthy' ? 'green' : ($health['storage']['status'] === 'warning' ? 'yellow' : 'red') }}-600">
                        <div class="w-3 h-3 bg-{{ $health['storage']['status'] === 'healthy' ? 'green' : ($health['storage']['status'] === 'warning' ? 'yellow' : 'red') }}-500 rounded-full mr-2"></div>
                        {{ ucfirst($health['storage']['status']) }}
                    </span>
                </div>

                <!-- Memory Usage -->
                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-{{ $health['memory']['status'] === 'healthy' ? 'green' : ($health['memory']['status'] === 'warning' ? 'yellow' : 'red') }}-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="cpu" class="w-5 h-5 text-{{ $health['memory']['status'] === 'healthy' ? 'green' : ($health['memory']['status'] === 'warning' ? 'yellow' : 'red') }}-600"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="font-medium">{{ __('general.memory') }}</h4>
                            <p class="text-sm text-slate-600">{{ $health['memory']['message'] }}</p>
                            @if(isset($health['memory']['details']))
                                <div class="text-xs text-slate-500 mt-1">
                                    Used: {{ $health['memory']['details']['used'] }} |
                                    Limit: {{ $health['memory']['details']['limit'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <span class="flex items-center text-{{ $health['memory']['status'] === 'healthy' ? 'green' : ($health['memory']['status'] === 'warning' ? 'yellow' : 'red') }}-600">
                        <div class="w-3 h-3 bg-{{ $health['memory']['status'] === 'healthy' ? 'green' : ($health['memory']['status'] === 'warning' ? 'yellow' : 'red') }}-500 rounded-full mr-2"></div>
                        {{ ucfirst($health['memory']['status']) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-span-12 xl:col-span-4">
        <div class="box p-6">
            <h3 class="text-lg font-medium mb-6">{{ __('general.system_actions') }}</h3>
            <div class="space-y-3">
                <button onclick="clearCache()" class="w-full btn btn-outline-primary">
                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                    {{ __('general.clear_cache') }}
                </button>
                <button onclick="clearLogs()" class="w-full btn btn-outline-warning">
                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i>
                    {{ __('general.clear_logs') }}
                </button>
                <a href="{{ route('admin.system.logs') }}" class="w-full btn btn-outline-secondary">
                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                    {{ __('general.view_logs') }}
                </a>
                <button onclick="runMaintenance()" class="w-full btn btn-outline-info">
                    <i data-lucide="settings" class="w-4 h-4 mr-2"></i>
                    {{ __('general.maintenance_mode') }}
                </button>
            </div>
        </div>

        <!-- System Information -->
        <div class="box p-6 mt-6">
            <h3 class="text-lg font-medium mb-6">{{ __('general.system_information') }}</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-600">{{ __('general.php_version') }}</span>
                    <span class="font-medium">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">{{ __('general.laravel_version') }}</span>
                    <span class="font-medium">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">{{ __('general.environment') }}</span>
                    <span class="font-medium">{{ app()->environment() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">{{ __('general.timezone') }}</span>
                    <span class="font-medium">{{ config('app.timezone') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">{{ __('general.locale') }}</span>
                    <span class="font-medium">{{ app()->getLocale() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function refreshHealth() {
    window.location.reload();
}

function clearCache() {
    if (confirm('{{ __("general.confirm_clear_cache") }}')) {
        fetch('{{ route("admin.system.cache.clear") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("general.cache_cleared_successfully") }}');
                window.location.reload();
            } else {
                alert('{{ __("general.cache_clear_failed") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("general.cache_clear_failed") }}');
        });
    }
}

function clearLogs() {
    if (confirm('{{ __("general.confirm_clear_logs") }}')) {
        // Implement log clearing
        alert('{{ __("general.feature_coming_soon") }}');
    }
}

function runMaintenance() {
    if (confirm('{{ __("general.confirm_maintenance_mode") }}')) {
        // Implement maintenance mode toggle
        alert('{{ __("general.feature_coming_soon") }}');
    }
}
</script>
@endpush
