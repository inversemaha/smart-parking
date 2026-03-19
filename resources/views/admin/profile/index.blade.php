@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="content">
    <!-- BEGIN: Top Bar -->
    <div class="top-bar-boxed">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3 w-full">
            <nav aria-label="breadcrumb" class="flex flex-1">
                <ol class="flex items-center whitespace-nowrap">
                    <li>
                        <a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" class="w-4 h-4 mx-2 stroke-slate-400">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </li>
                    <li class="text-primary font-medium">My Profile</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- END: Top Bar -->

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Profile Section -->
        <div class="col-span-12 lg:col-span-8">
            <!-- Profile Card -->
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                    <h3 class="font-bold text-slate-900">Profile Information</h3>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-6 pb-4 border-b border-slate-200/60">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-primary/60 flex items-center justify-center text-white font-bold text-4xl flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Profile Avatar</label>
                            <input type="file" name="avatar" accept="image/*"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <p class="text-xs text-slate-500 mt-1">Supported formats: JPG, PNG (Max 2MB)</p>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            @error('name')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email (Read-only)</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                            <input type="tel" name="mobile" value="{{ auth()->user()->mobile ?? '' }}"
                                placeholder="+880 1700 000000"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            @error('mobile')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">User Type</label>
                            <input type="text" value="{{ ucfirst(auth()->user()->user_type ?? 'User') }}" disabled
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg bg-slate-50 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Bio (Optional)</label>
                        <textarea name="bio" placeholder="Tell us about yourself..."
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" rows="3">{{ auth()->user()->bio ?? '' }}</textarea>
                    </div>

                    <!-- Save Button -->
                    <div class="flex gap-3 pt-4 border-t border-slate-200/60">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-semibold flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>Save Changes
                        </button>
                        <a href="{{ route('admin.dashboard.index') }}" class="px-6 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors font-semibold">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                    <h3 class="font-bold text-slate-900">Change Password</h3>
                </div>

                <form action="{{ route('admin.profile.change-password') }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('current_password')
                            <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                            <input type="password" name="password" required
                                placeholder="At least 8 characters"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            @error('password')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                placeholder="Confirm new password"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex gap-3 pt-4 border-t border-slate-200/60">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-semibold flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Account Settings -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                    <h3 class="font-bold text-slate-900">Account Settings</h3>
                </div>

                <div class="p-5 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-slate-900">Email Notifications</p>
                            <p class="text-sm text-slate-600">Receive email updates about your bookings</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ auth()->user()->email_notifications ?? true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-slate-900">SMS Notifications</p>
                            <p class="text-sm text-slate-600">Receive SMS updates about your bookings</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ auth()->user()->sms_notifications ?? false ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-slate-900">Two-Factor Authentication</p>
                            <p class="text-sm text-slate-600">Add extra security to your account</p>
                        </div>
                        <button type="button" class="px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 font-semibold text-sm">
                            Configure
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-span-12 lg:col-span-4">
            <!-- Account Summary -->
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Account Summary</h3>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Account Status</label>
                        <p class="text-sm font-semibold {{ auth()->user()->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ auth()->user()->is_active ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">User Type</label>
                        <p class="text-sm font-semibold text-slate-900">{{ ucfirst(auth()->user()->user_type ?? 'User') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Joined Date</label>
                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->created_at?->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Last Login</label>
                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->last_login_at?->format('M d, Y H:i') ?? 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Roles & Permissions</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-2">
                        @forelse(auth()->user()->roles as $role)
                            <div class="px-3 py-2 bg-primary/10 rounded-lg">
                                <p class="text-sm font-semibold text-primary">{{ ucfirst($role->name) }}</p>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $role->permissions->count() }} permissions</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-600">No roles assigned</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Login Activity -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Active Sessions</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-2">
                        <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm font-semibold text-green-900">Current Session</p>
                            <p class="text-xs text-green-700 mt-1">
                                <i class="w-3 h-3 inline-block mr-1">🌐</i>
                                {{ request()->getClientIp() ?? 'Unknown' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="logoutAllModal.showModal()" 
                        class="mt-3 w-full px-4 py-2 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 font-semibold text-sm">
                        <i data-lucide="log-out" class="w-4 h-4 inline-block mr-2"></i>Logout All Sessions
                    </button>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="intro-y box mt-6 border-red-200">
                <div class="flex items-center p-5 border-b border-red-200 bg-red-50">
                    <h3 class="font-bold text-red-900">Danger Zone</h3>
                </div>
                <div class="p-5">
                    <p class="text-sm text-slate-600 mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                    <button type="button" onclick="deleteAccountModal.showModal()" 
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm">
                        <i data-lucide="trash-2" class="w-4 h-4 inline-block mr-2"></i>Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout All Sessions Modal -->
    <dialog id="logoutAllModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Logout All Sessions</h3>
            <p class="text-slate-600 mb-6">You will be logged out from all devices. Are you sure?</p>
            <div class="modal-action">
                <button type="button" onclick="logoutAllModal.close()" class="btn btn-ghost">Cancel</button>
                <form action="{{ route('admin.profile.logout-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">Logout All</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button type="button">close</button>
        </form>
    </dialog>

    <!-- Delete Account Modal -->
    <dialog id="deleteAccountModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-red-600 mb-4">Delete Account</h3>
            <p class="text-slate-600 mb-3">This action cannot be undone. All your data will be permanently deleted.</p>
            <p class="text-sm text-slate-500 mb-4">Enter your password to confirm deletion:</p>
            <form action="{{ route('admin.profile.delete-account') }}" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')
                <input type="password" name="password" required placeholder="Your password"
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <div class="modal-action">
                    <button type="button" onclick="deleteAccountModal.close()" class="btn btn-ghost">Cancel</button>
                    <button type="submit" class="btn btn-error">Delete Account</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button type="button">close</button>
        </form>
    </dialog>
</div>
@endsection
