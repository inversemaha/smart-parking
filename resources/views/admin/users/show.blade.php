@extends('layouts.admin')

@section('content')
<div class="content">
    <!-- Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-4">
        <h1 class="text-lg font-medium text-slate-800 dark:text-slate-100">User Details</h1>
        <nav class="flex pt-2 sm:pt-0" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.dashboard.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Dashboard</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li><a href="{{ route('admin.users.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-slate-600">Users</a></li>
                <li class="text-slate-500 dark:text-slate-400">/</li>
                <li class="text-slate-600 dark:text-slate-300">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Info -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- User Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $user->email }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-bold
                        @if($user->suspended_at) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @elseif($user->deactivated_at) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif($user->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                        @endif">
                        @if($user->suspended_at)
                        Suspended
                        @elseif($user->deactivated_at)
                        Deactivated
                        @elseif($user->is_active)
                        Active
                        @else
                        Inactive
                        @endif
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Type</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ ucfirst($user->user_type) }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Mobile</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $user->mobile ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-slate-600 dark:text-slate-400 text-sm">Email Verified</label>
                        <p class="font-medium text-slate-900 dark:text-white mt-1">
                            {{ $user->email_verified_at ? '✓ Yes' : '✗ No' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Roles -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Assigned Roles</h3>
                @if($user->roles->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                    <span class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $role->name }}
                    </span>
                    @endforeach
                </div>
                @else
                <p class="text-slate-500 dark:text-slate-400">No roles assigned</p>
                @endif
            </div>

            <!-- Bookings -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    @if($user->bookings && $user->bookings->count() > 0)
                    <table class="w-full text-sm">
                        <thead class="border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-slate-600 dark:text-slate-400">Booking #</th>
                                <th class="px-4 py-2 text-left text-slate-600 dark:text-slate-400">Location</th>
                                <th class="px-4 py-2 text-left text-slate-600 dark:text-slate-400">Date</th>
                                <th class="px-4 py-2 text-left text-slate-600 dark:text-slate-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($user->bookings->take(5) as $booking)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                <td class="px-4 py-3 text-slate-900 dark:text-white">{{ $booking->booking_number }}</td>
                                <td class="px-4 py-3 text-slate-900 dark:text-white">{{ $booking->parkingLocation->name }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $booking->start_time->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($booking->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($booking->status === 'active') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-slate-500 dark:text-slate-400 text-center py-8">No bookings yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4 space-y-4">
            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg font-medium transition-colors">
                        Edit User
                    </a>
                    @if(!$user->suspended_at)
                    <button onclick="suspendUser()" class="w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors">
                        Suspend User
                    </button>
                    @else
                    <button onclick="activateUser()" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                        Reactivate User
                    </button>
                    @endif
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <h3 class="font-medium text-slate-900 dark:text-white mb-3 text-sm">System Information</h3>
                <div class="text-xs text-slate-600 dark:text-slate-400 space-y-2">
                    <p><strong>Registered:</strong> {{ $user->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $user->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>User ID:</strong> {{ $user->id }}</p>
                </div>
            </div>

            @if($user->suspended_at)
            <!-- Suspension Info -->
            <div class="bg-red-50 dark:bg-red-900 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <h3 class="font-medium text-red-900 dark:text-red-100 mb-2 text-sm">Suspended</h3>
                <div class="text-xs text-red-800 dark:text-red-200 space-y-1">
                    <p><strong>Suspended At:</strong> {{ $user->suspended_at->format('M d, Y H:i') }}</p>
                    @if($user->suspension_reason)
                    <p><strong>Reason:</strong> {{ $user->suspension_reason }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function suspendUser() {
    const reason = prompt('Enter suspension reason:');
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.users.suspend', $user->id) }}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="reason" value="${reason}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function activateUser() {
    if (confirm('Reactivate this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.users.activate', $user->id) }}`;
        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
