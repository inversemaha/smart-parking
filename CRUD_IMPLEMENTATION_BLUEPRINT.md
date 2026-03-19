# Complete CRUD Implementation Blueprint
# For Smart Parking Admin Modules
# Date: March 20, 2026

## Pattern Overview
All CRUD modules follow identical pattern:
1. **List View** (`index.blade.php`) - Table with search, filter, delete
2. **Create View** (`create.blade.php`) - Form for new entry
3. **Edit View** (`edit.blade.php`) - Form for editing
4. **Show View** (`show.blade.php`) - Detail view (optional)
5. **Controller** - List, Create, Store, Show, Edit, Update, Destroy
6. **Routes** - RESTful routes
7. **API Endpoints** - JSON responses for AJAX/Mobile
8. **Form Request** - Validation
9. **Repository** - Database queries

---

## MODULE 1: VEHICLE MANAGEMENT ✅
**Status**: List View Done
**Files Needed**:
- [x] resources/views/admin/vehicles/index.blade.php
- [ ] resources/views/admin/vehicles/create.blade.php
- [ ] resources/views/admin/vehicles/edit.blade.php
- [ ] resources/views/admin/vehicles/show.blade.php
- [ ] app/Domains/Vehicle/Requests/StoreVehicleRequest.php
- [ ] app/Domains/Admin/Controllers/VehicleController.php (API + Web)

---

## MODULE 2: PARKING LOCATION MANAGEMENT
**Status**: Not Started
**Routes to Add**:
```php
Route::prefix('parking-locations')->name('parking-locations.')->middleware('auth')->group(function () {
    Route::get('/', [ParkingLocationController::class, 'index'])->name('index');
    Route::get('/create', [ParkingLocationController::class, 'create'])->name('create');
    Route::post('/', [ParkingLocationController::class, 'store'])->name('store');
    Route::get('/{location}', [ParkingLocationController::class, 'show'])->name('show');
    Route::get('/{location}/edit', [ParkingLocationController::class, 'edit'])->name('edit');
    Route::put('/{location}', [ParkingLocationController::class, 'update'])->name('update');
    Route::delete('/{location}', [ParkingLocationController::class, 'destroy'])->name('destroy');
});
```

**Table Fields**:
- Name
- Address / GPS Coordinates  
- Total Slots
- Available Slots
- Hourly Rate
- Rating
- Status (Active/Inactive)
- Actions

---

## MODULE 3: BOOKING MANAGEMENT
**Status**: Not Started
**Routes**:
```php
Route::prefix('bookings')->name('bookings.')->middleware('auth')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
    Route::put('/{booking}', [BookingController::class, 'update'])->name('update');
    Route::delete('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
});
```

**Table Fields**:
- Booking ID
- User Name
- Vehicle Registration
- Location
- Check-in / Check-out Time
- Duration
- Amount
- Status
- Actions

---

## MODULE 4: USER MANAGEMENT (Full CRUD)
**Status**: Already has basic list
**Routes**:
```php
Route::prefix('users')->name('users.')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});
```

---

## MODULE 5: PAYMENT MANAGEMENT
**Status**: Not Started
**Fields**:
- Transaction ID
- User Name
- Amount
- Payment Method
- Status (Completed/Pending/Failed)
- Date
- Actions

---

## MODULE 6: INVOICE MANAGEMENT
**Status**: Not Started
**Fields**:
- Invoice Number  
- User
- Booking ID
- Total Amount
- Status (Paid/Unpaid)
- Download PDF/View

---

## MODULE 7: ROLE & PERMISSION (Enhanced)
**Status**: Already exists
**Enhancements**:
- Add interface for permission assignment
- Add bulk operations
- Add role templates

---

## MODULE 8: SYSTEM SETTINGS
**Status**: Not Started
**Settings Categories**:
- General Settings
- Parking Rates
- Email Configuration
- SMS Configuration
- Payment Gateway Settings
- System Maintenance Mode

---

## MODULE 9: USER PROFILE (Self)
**Status**: Not Started
**Features**:
- View/Edit Profile
- Change Password
- Avatar Upload
- Account Settings
- Login History
- Device Management

---

## MODULE 10: REPORTS WITH DOWNLOAD
**Status**: Partially exists
**Enhancements**:
- Add CSV Export
- Add PDF Generation
- Add Date Range Filters
- Add Report Scheduling

---

## SIDEBAR MENU STRUCTURE (Update)
```php
<!-- Vehicles -->
<li>
    <a href="{{ route('admin.vehicles.index') }}" class="side-menu">
        <div class="side-menu__icon"><i data-lucide="car"></i></div>
        <div class="side-menu__title">Vehicles</div>
    </a>
</li>

<!-- Parking Locations -->
<li>
    <a href="javascript:;" class="side-menu">
        <div class="side-menu__icon"><i data-lucide="map-pin"></i></div>
        <div class="side-menu__title">
            Parking Locations
            <div class="side-menu__sub-icon"><i data-lucide="chevron-down"></i></div>
        </div>
    </a>
    <ul class="">
        <li><a href="{{ route('admin.parking-locations.index') }}" class="side-menu"><div class="side-menu__icon"><i data-lucide="activity"></i></div><div class="side-menu__title">All Locations</div></a></li>
        <li><a href="{{ route('admin.parking-locations.create') }}" class="side-menu"><div class="side-menu__icon"><i data-lucide="plus"></i></div><div class="side-menu__title">Add New</div></a></li>
    </ul>
</li>

<!-- Bookings -->
<li>
    <a href="{{ route('admin.bookings.index') }}" class="side-menu">
        <div class="side-menu__icon"><i data-lucide="calendar"></i></div>
        <div class="side-menu__title">Bookings</div>
    </a>
</li>

<!-- Payments -->
<li>
    <a href="{{ route('admin.payments.index') }}" class="side-menu">
        <div class="side-menu__icon"><i data-lucide="credit-card"></i></div>
        <div class="side-menu__title">Payments</div>
    </a>
</li>

<!-- Users -->
<li>
    <a href="{{ route('admin.users.index') }}" class="side-menu">
        <div class="side-menu__icon"><i data-lucide="users"></i></div>
        <div class="side-menu__title">Users</div>
    </a>
</li>

<!-- Invoices -->
<li>
    <a href="{{ route('admin.invoices.index') }}" class="side-menu">
        <div class="side-menu__icon"><i data-lucide="file-text"></i></div>
        <div class="side-menu__title">Invoices</div>
    </a>
</li>

<!-- System Settings -->
<li>
    <a href="{{ route('admin.settings.index') }}" class="side-menu">
        <div class="side-menu__icon"><i data-lucide="settings"></i></div>
        <div class="side-menu__title">Settings</div>
    </a>
</li>
```

---

## FORM TEMPLATE (Create/Edit)
```blade
@extends('layouts.admin')

@section('title', 'Create/Edit {{ $title }}')
@section('page-title', 'Create/Edit {{ $title }}')

@section('content')
<div class="grid grid-cols-12 gap-6 mt-10">
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="intro-y box p-5">
            <form action="{{ isset($item) ? route('admin.{{ $route }}.update', $item) : route('admin.{{ $route }}.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($item)) @method('PUT') @endif

                <!-- Form Fields -->
                <div class="mt-3">
                    <label class="form-label">Field Name</label>
                    <input type="text" class="form-control @error('field_name') border-danger @enderror" 
                        name="field_name" value="{{ old('field_name', $item->field_name ?? '') }}" required>
                    @error('field_name')<div class="text-danger text-xs mt-2">{{ $message }}</div>@enderror
                </div>

                <!-- Buttons -->
                <div class="mt-5 flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ isset($item) ? 'Update' : 'Create' }}</button>
                    <a href="{{ route('admin.{{ $route }}.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

---

## API ENDPOINTS TEMPLATE
```php
<?php
namespace App\Domains\Admin\Controllers\Api;

use App\Http\Controllers\Controller;

class {{ Model }}Controller extends Controller
{
    public function index(Request $request) {
        // List with filters
        $items = {{ Model }}::when($request->search, function($q) use ($request) {
            $q->where('field', 'like', "%{$request->search}%");
        })->paginate(15);
        
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function store(Request $request) {
        // Validate and create
        $validated = $request->validate([
            'field' => 'required|string|unique:table,field'
        ]);
        
        $item = {{ Model }}::create($validated);
        return response()->json(['success' => true, 'data' => $item], 201);
    }

    public function show({{ Model }} $item) {
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update(Request $request, {{ Model }} $item) {
        $validated = $request->validate([...]);
        $item->update($validated);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy({{ Model }} $item) {
        $item->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
```

---

## QUICK IMPLEMENTATION GUIDE

### Step 1: Create Form Request
```bash
php artisan make:request Admin/Store{{ Model }}Request
```

### Step 2: Create Controller
```bash
php artisan make:controller Admin/{{ Model }}Controller --resource
```

### Step 3: Create Views (5 files)
- index.blade.php (list)
- create.blade.php
- edit.blade.php
- show.blade.php

### Step 4: Add Routes
Add to `routes/web.php` or `routes/admin.php`

### Step 5: Migrate if needed
```bash
php artisan migrate
```

### Step 6: Test & Commit
```bash
git add -A
git commit -m "feat: Add {{ Module }} CRUD with full functionality"
```

---

##Priority Order
1. ✅ Vehicle Management (In Progress)
2. 🔄 Parking Location CRUD
3. 🔄 Booking Management
4. 🔄 User Management (Complete)
5. 🔄 Payment CRUD
6. 🔄 Invoice CRUD
7. 🔄 System Settings
8. 🔄 User Profile
9. 🔄 Reports Enhancement
10. 🔄 Role & Permission Enhancement

---

## Testing Checklist
- [ ] List view displays all data
- [ ] Search functionality works
- [ ] Create form validates input
- [ ] Edit form pre-fills data
- [ ] Delete confirmation works
- [ ] API returns JSON responses
- [ ] Filters work correctly
- [ ] Export to CSV works
- [ ] Pagination works
- [ ] Mobile responsive

---

## Git Commit Messages
```
feat: Add [Module] management list view
feat: Add [Module] create form and API endpoint
feat: Add [Module] edit form and update logic
feat: Add [Module] delete functionality with confirmation
feat: Add [Module] complete CRUD with API
```

---

Max Tokens: Use this blueprint to quickly implement all modules!
