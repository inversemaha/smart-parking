# Smart Parking System - Module-by-Module Task Breakdown

---

## MODULE 1: PARKING ZONES & FLOORS
**Category**: System Configuration  
**Priority**: CRITICAL (foundational)  
**Complexity**: Medium

### Features Required
1. **Zone Management**
   - Create/Edit/Delete parking zones
   - Hierarchy: Building → Zone
   - Set capacity per zone
   - Set location coordinates (GPS)

2. **Floor Management**
   - Create/Edit/Delete floors within zones
   - Floor numbering and hierarchy
   - Set capacity per floor
   - Floor-specific pricing

### CRUD Operations
```
Zones:
- GET /admin/system/zones (list all)
- POST /admin/system/zones (create)
- GET /admin/system/zones/{id} (view detail)
- PUT /admin/system/zones/{id} (update)
- DELETE /admin/system/zones/{id} (delete)

Floors:
- GET /admin/system/floors (list all)
- POST /admin/system/floors (create)
- GET /admin/system/floors/{id} (view detail)
- PUT /admin/system/floors/{id} (update)
- DELETE /admin/system/floors/{id} (delete)
- GET /admin/system/zones/{zone}/floors (zone-specific)
```

### Additional Actions
- View zone occupancy (how many slots occupied)
- View floor occupancy
- Mark zone as active/inactive
- Bulk assign slots to floors

### Database Tables
```sql
parking_zones (
  id, building_id, name, location, latitude, longitude,
  total_capacity, current_occupancy, is_active, created_at, updated_at
)

parking_floors (
  id, zone_id, floor_number, floor_name, total_capacity,
  current_occupancy, is_active, created_at, updated_at
)
```

### API Endpoints (Basic)
- `GET /api/zones` - List all zones (public)
- `GET /api/zones/{id}/occupancy` - Zone occupancy
- `GET /api/floors/{id}/occupancy` - Floor occupancy

### UI Components
- Zone list table (name, building, capacity, occupancy %)
- Zone form (create/edit modal)
- Floor list (hierarchical, grouped by zone)
- Floor form (create/edit modal)

---

## MODULE 2: VEHICLE TYPES & PARKING RATES
**Category**: System Configuration  
**Priority**: CRITICAL (needed by booking)  
**Complexity**: Medium

### Features Required
1. **Vehicle Type Management**
   - Create/Edit/Delete vehicle types (Car, Motorcycle, Truck, etc.)
   - Dimensions (width, height, length)
   - Rate multiplier
   - Icon/Photo

2. **Parking Rate Management**
   - Define rates per zone × vehicle type
   - Hourly rate, daily rate
   - Time-based pricing (peak/off-peak)
   - Override rates per location

### CRUD Operations
```
Vehicle Types:
- GET /admin/system/vehicle-types (list all)
- POST /admin/system/vehicle-types (create)
- GET /admin/system/vehicle-types/{id} (view detail)
- PUT /admin/system/vehicle-types/{id} (update)
- DELETE /admin/system/vehicle-types/{id} (delete)

Parking Rates:
- GET /admin/system/rates (list all)
- POST /admin/system/rates (create)
- GET /admin/system/rates/{id} (view detail)
- PUT /admin/system/rates/{id} (update)
- DELETE /admin/system/rates/{id} (delete)
- GET /admin/system/zones/{zone}/rates (zone-specific rates)
```

### Additional Actions
- Calculate price for booked duration
- Apply bulk rates to all zones
- View rate history (audit changes)
- Compare rates across zones

### Database Tables
```sql
vehicle_types (
  id, name, width, height, length, description,
  rate_multiplier, icon_url, is_active, created_at, updated_at
)

parking_rates (
  id, zone_id, vehicle_type_id, hourly_rate, daily_rate,
  peak_hour_rate, off_peak_rate, is_active, created_at, updated_at
)
```

### API Endpoints (Basic)
- `GET /api/vehicle-types` - List all types
- `GET /api/rates/calculate` - Calculate parking cost
  - Params: zone_id, vehicle_type_id, entry_time, exit_time
  - Returns: total_cost, hourly_cost, daily_cost, breakdown

### UI Components
- Vehicle type list table (name, dimensions, multiplier)
- Vehicle type form (create/edit)
- Rates list table (zone, vehicle type, hourly, daily prices)
- Rates form (create/edit modal)
- Rate calculator tool (test price calculation)

---

## MODULE 3: ACCESS CONTROL / QR GENERATION
**Category**: Business Management  
**Priority**: HIGH (needed for booking flow)  
**Complexity**: High

### Features Required
1. **QR Code Generation**
   - Generate unique QR per booking
   - Encode booking info: ID, vehicle plate, zone, time
   - QR should be scannable at entry/exit

2. **Token Management**
   - Create access token per booking
   - Token expires at booking exit time + buffer
   - Revoke token if booking cancelled

### CRUD Operations
```
Access Tokens:
- POST /admin/bookings/{id}/generate-qr (generate QR for booking)
- GET /admin/bookings/{id}/qr (view QR)
- DELETE /admin/bookings/{id}/qr (revoke QR)
- GET /api/qr/validate/{token} (validate QR - for gate operator)
```

### Additional Actions
- Regenerate QR if booking extended
- Print QR code
- Email QR to customer
- Validate QR at entry/exit

### Database Tables
```sql
access_tokens (
  id, booking_id, token_code, qr_code_url, scanned_at,
  is_valid, expires_at, created_at, updated_at
)
```

### API Endpoints (Basic)
- `POST /api/qr/scan` - Scan QR code
  - Input: qr_code or token
  - Returns: booking details, vehicle info, parking slot
- `GET /api/qr/{token}` - Get QR details (for validation)

### UI Components
- QR display in booking detail view
- QR print/email options
- QR regeneration button
- Gate operator QR scanner interface (mobile-friendly)

---

## MODULE 4: PARKING SESSIONS (ENTRY/EXIT TRACKING)
**Category**: Business Management  
**Priority**: HIGH  
**Complexity**: High

### Features Required
1. **Entry Recording**
   - Record vehicle entry via QR scan
   - Capture entry time, gate, photo
   - Update parking slot status to "occupied"

2. **Exit Recording**
   - Record vehicle exit via QR scan
   - Calculate overstay charges
   - Generate invoice
   - Free up parking slot

3. **Session History**
   - View all sessions per booking
   - Session duration, charges
   - Entry/exit photos

### CRUD Operations
```
Parking Sessions:
- POST /api/parking/entry (scan QR at entry gate)
- POST /api/parking/exit (scan QR at exit gate)
- GET /admin/sessions (list all sessions)
- GET /admin/sessions/{id} (session detail)
- GET /admin/bookings/{id}/session (session for booking)
```

### Additional Actions
- Record entry/exit photos
- Calculate duration and charges
- Alert if overstay
- Generate automatic invoice on exit

### Database Tables
```sql
parking_sessions (
  id, booking_id, vehicle_id, slot_id, entry_time, exit_time,
  entry_gate_id, exit_gate_id, entry_photo_url, exit_photo_url,
  duration_minutes, is_completed, created_at, updated_at
)
```

### API Endpoints (Basic)
- `POST /api/parking/entry` - Mark entry
  - Input: qr_token or booking_id
  - Returns: entry confirmation, duration estimate
- `POST /api/parking/exit` - Mark exit
  - Input: qr_token or booking_id
  - Returns: invoice details, total charge

### UI Components
- Gate operator QR scanner (entry page)
- Gate operator QR scanner (exit page)
- Session list (admin view - all entries/exits)
- Session detail (entry time, exit time, duration, cost)

---

## MODULE 5: OPERATOR / GATE MODE
**Category**: User & Access Control  
**Priority**: HIGH  
**Complexity**: Medium

### Features Required
1. **Dedicated Gate Operator Interface**
   - Simplified mobile-friendly UI
   - QR scanner integration
   - Quick entry/exit recording
   - Offline capability (sync when online)

2. **Operator Dashboard**
   - Today's entries/exits count
   - Active sessions
   - Quick stats

### Actions
- Scan QR to mark entry
- Scan QR to mark exit
- View entry/exit history for current shift
- Report issues/maintenance

### Database
- Use existing roles (gate-operator)
- Track operator actions in activity_logs

### API Endpoints
- `GET /api/operator/dashboard` - Operator stats
- `GET /api/operator/sessions/active` - Active sessions
- `POST /api/operator/session/{id}/complete` - Complete session

### UI Components
- Operator login (simplified)
- QR scanner page (camera feed, manual input)
- Session quick view (license plate, entry time, estimated exit)
- Today's activity summary

---

## MODULE 6: BILLING & INVOICE MANAGEMENT
**Category**: Business Management  
**Priority**: HIGH  
**Complexity**: Medium-High

### Features Required
1. **Invoice Generation**
   - Auto-generate invoice on booking exit
   - Manual invoice generation
   - Invoice number sequence

2. **Invoice Management**
   - View all invoices
   - Mark as paid
   - Download PDF
   - Email invoice

### CRUD Operations
```
Invoices:
- GET /admin/invoices (list all)
- GET /admin/invoices/{id} (view detail)
- POST /admin/invoices (manual create)
- PUT /admin/invoices/{id} (update)
- GET /admin/invoices/{id}/download (PDF)
- POST /admin/invoices/{id}/send-email (email)
- POST /admin/invoices/{id}/mark-paid (mark payment)
```

### Additional Actions
- Generate invoice for multiple bookings
- Apply discounts/promos
- Track payment status
- Re-send invoice email
- View invoice history

### Database Tables
```sql
invoices (
  id, booking_id, invoice_number, issue_date, due_date,
  subtotal, tax, discount, total, payment_status, payment_date,
  customer_name, customer_email, notes, created_at, updated_at
)

invoice_items (
  id, invoice_id, description, quantity, rate, amount
)
```

### UI Components
- Invoice list with filters (paid/unpaid/overdue)
- Invoice detail view
- Invoice form (manual creation)
- PDF viewer
- Email options

---

## SIDEBAR MENU STRUCTURE (Updated)

```
Dashboard

Business Management
├── Parking Sessions (NEW)
├── Parked Vehicles (NEW - real-time view)
├── Bookings (EXISTING)
├── Parking Slots (exists as Parking Lots)
├── Parking Rates (NEW)
├── Access Control / QR (NEW)
├── Vehicle Management (EXISTING)
├── Invoices (NEW - separate from payments)
├── Payments (EXISTING)
├── Reports (EXISTING)
├── Contact Diary (NEW)
└── Notice Board (NEW)

System Configuration
├── Parking Zones (NEW)
├── Parking Floors (NEW)
├── Vehicle Types (NEW)
├── Parking Rates (NEW)
├── Slot Configuration (NEW)
├── Email Notifications (NEW)
└── SMS / Alert Notifications (NEW)

User & Access Control
├── User Management (EXISTING)
├── Roles & Permissions (EXISTING)
└── Operator / Gate Mode (NEW)

Monitoring & Control
├── Live Dashboard (NEW)
├── Audit Logs (EXISTING)
├── Activity Logs (NEW)
├── Maintenance / Slot Blocking (NEW)
└── Overstay Management (NEW)

System Settings
├── Pricing (NEW)
├── General Settings (EXISTING)
├── Branding (NEW)
├── Backup & Restore (NEW)
└── API & Integration (NEW)
```

---

## IMPLEMENTATION ROADMAP

### PHASE 1: Foundation (Start Here)
- [ ] Module 1: Parking Zones & Floors
- [ ] Module 2: Vehicle Types & Rates

### PHASE 2: Booking Flow
- [ ] Module 3: Access Control / QR Generation
- [ ] Module 4: Parking Sessions
- [ ] Module 5: Operator / Gate Mode

### PHASE 3: Payment & Billing  
- [ ] Module 6: Invoices
- [ ] Notifications (Email/SMS)

### PHASE 4: Monitoring
- [ ] Live Dashboard
- [ ] Activity Logs
- [ ] Overstay Management

### PHASE 5: Advanced
- [ ] Pricing Rules
- [ ] Branding
- [ ] API & Integration
- [ ] Backup & Restore

