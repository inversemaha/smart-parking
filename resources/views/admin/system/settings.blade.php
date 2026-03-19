@extends('layouts.admin')

@section('title', __('admin.settings.title'))

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
                    <li class="text-primary font-medium">System Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- END: Top Bar -->

    <div class="grid grid-cols-12 gap-6">
        <!-- Settings Form -->
        <div class="col-span-12 lg:col-span-8">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- General Settings -->
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                        <h3 class="font-bold text-slate-900">General Settings</h3>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">System Name</label>
                            <input type="text" name="system_name" value="{{ $settings['system_name'] ?? 'Parking Management System' }}"
                                placeholder="Your system name"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">System Description</label>
                            <textarea name="system_description" placeholder="Brief description of your system"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" rows="3">{{ $settings['system_description'] ?? '' }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Support Email</label>
                                <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}"
                                    placeholder="support@example.com"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Support Phone</label>
                                <input type="tel" name="support_phone" value="{{ $settings['support_phone'] ?? '' }}"
                                    placeholder="+880 1000 000000"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Support Website</label>
                            <input type="url" name="support_website" value="{{ $settings['support_website'] ?? '' }}"
                                placeholder="https://example.com"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <!-- Parking Rates & Hours -->
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                        <h3 class="font-bold text-slate-900">Parking Rates & Hours</h3>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Hourly Rate (BDT)</label>
                                <input type="number" name="hourly_rate" step="0.01" value="{{ $settings['hourly_rate'] ?? '50' }}"
                                    placeholder="50"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Daily Rate (BDT)</label>
                                <input type="number" name="daily_rate" step="0.01" value="{{ $settings['daily_rate'] ?? '300' }}"
                                    placeholder="300"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Monthly Rate (BDT)</label>
                                <input type="number" name="monthly_rate" step="0.01" value="{{ $settings['monthly_rate'] ?? '5000' }}"
                                    placeholder="5000"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Operating Hours (From)</label>
                                <input type="time" name="operating_hours_from" value="{{ $settings['operating_hours_from'] ?? '06:00' }}"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Operating Hours (To)</label>
                                <input type="time" name="operating_hours_to" value="{{ $settings['operating_hours_to'] ?? '22:00' }}"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="enable_24_hours" value="1" 
                                    {{ isset($settings['enable_24_hours']) && $settings['enable_24_hours'] ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300">
                                <span class="text-sm font-semibold text-slate-700">Enable 24-hour parking</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                        <h3 class="font-bold text-slate-900">Payment Settings</h3>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" step="0.01" value="{{ $settings['tax_rate'] ?? '15' }}"
                                    placeholder="15"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Currency</label>
                                <select name="currency"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="BDT" {{ ($settings['currency'] ?? 'BDT') === 'BDT' ? 'selected' : '' }}>BDT (Taka)</option>
                                    <option value="USD" {{ ($settings['currency'] ?? 'BDT') === 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                    <option value="INR" {{ ($settings['currency'] ?? 'BDT') === 'INR' ? 'selected' : '' }}>INR (Rupee)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Payment Gateway</label>
                            <select name="payment_gateway"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="sslcommerz"{{ ($settings['payment_gateway'] ?? 'sslcommerz') === 'sslcommerz' ? 'selected' : '' }}>SSLCommerz</option>
                                <option value="stripe" {{ ($settings['payment_gateway'] ?? 'sslcommerz') === 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="manual" {{ ($settings['payment_gateway'] ?? 'sslcommerz') === 'manual' ? 'selected' : '' }}>Manual Payment</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Email Settings -->
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                        <h3 class="font-bold text-slate-900">Email Settings</h3>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">SMTP Host</label>
                            <input type="text" name="smtp_host" value="{{ $settings['smtp_host'] ?? '' }}"
                                placeholder="smtp.mailtrap.io"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">SMTP Port</label>
                                <input type="number" name="smtp_port" value="{{ $settings['smtp_port'] ?? '2525' }}"
                                    placeholder="2525"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">SMTP Username</label>
                                <input type="text" name="smtp_username" value="{{ $settings['smtp_username'] ?? '' }}"
                                    placeholder="username"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">From Email</label>
                            <input type="email" name="from_email" value="{{ $settings['from_email'] ?? '' }}"
                                placeholder="noreply@example.com"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <!-- System Maintenance -->
                <div class="intro-y box">
                    <div class="flex items-center p-5 border-b border-slate-200/60 bg-gradient-to-r from-primary/5 to-primary/10">
                        <h3 class="font-bold text-slate-900">Maintenance Mode</h3>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="maintenance_mode" value="1" 
                                    {{ isset($settings['maintenance_mode']) && $settings['maintenance_mode'] ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300">
                                <span class="text-sm font-semibold text-slate-700">Enable Maintenance Mode</span>
                            </label>
                            <p class="text-xs text-slate-500 mt-1 ml-6">When enabled, non-admin users will see a maintenance message</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Maintenance Message</label>
                            <textarea name="maintenance_message" placeholder="System is under maintenance. Please try again later."
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" rows="2">{{ $settings['maintenance_message'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-semibold flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>Save Settings
                    </button>
                    <a href="{{ route('admin.dashboard.index') }}" class="px-6 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Settings Info Sidebar -->
        <div class="col-span-12 lg:col-span-4">
            <!-- Quick Info -->
            <div class="intro-y box">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">System Information</h3>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">System Version</label>
                        <p class="text-sm font-semibold text-slate-900">1.0.0</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Last Updated</label>
                        <p class="text-sm font-semibold text-slate-900">{{ now()->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-500 uppercase">Database</label>
                        <p class="text-sm font-semibold text-slate-900">MySQL</p>
                    </div>
                </div>
            </div>

            <!-- Help & Documentation -->
            <div class="intro-y box mt-6">
                <div class="flex items-center p-5 border-b border-slate-200/60 bg-slate-50">
                    <h3 class="font-bold text-slate-900">Help & Documentation</h3>
                </div>
                <div class="p-5 space-y-2">
                    <a href="#" class="flex items-center gap-2 text-primary hover:text-primary/80 font-semibold text-sm">
                        <i data-lucide="help-circle" class="w-4 h-4"></i>Documentation
                    </a>
                    <a href="#" class="flex items-center gap-2 text-primary hover:text-primary/80 font-semibold text-sm">
                        <i data-lucide="mail" class="w-4 h-4"></i>Contact Support
                    </a>
                    <a href="{{ route('admin.system.logs') }}" class="flex items-center gap-2 text-primary hover:text-primary/80 font-semibold text-sm">
                        <i data-lucide="activity" class="w-4 h-4"></i>View System Logs
                    </a>
                    <a href="{{ route('admin.system.health') }}" class="flex items-center gap-2 text-primary hover:text-primary/80 font-semibold text-sm">
                        <i data-lucide="activity" class="w-4 h-4"></i>System Health
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
