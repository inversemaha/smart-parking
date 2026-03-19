# Sidebar Menu - Current vs Target Structure

## CURRENT SIDEBAR (What Exists Now)

```
✓ Dashboard

✓ Vehicles
  ├─ All Vehicles
  ├─ Pending Verification
  └─ Add Vehicle

✓ Parking Lots
  ├─ All Locations
  └─ Add Location

✓ Bookings
  ├─ All Bookings
  ├─ Pending
  └─ Active

✓ Users
  ├─ All Users
  └─ Add User

✓ Payments
  ├─ All Payments
  ├─ Completed
  └─ Pending

✓ Invoices  
  ├─ All Invoices
  ├─ Unpaid
  └─ Paid

✓ Reports

✓ Permissions
  ├─ Roles
  ├─ User Permissions
  └─ Manage Permissions

✓ Settings
  ├─ System Settings
  └─ Audit Logs

✓ My Profile
```

---

## TARGET SIDEBAR (What We Need to Build)

```
📊 Dashboard
   └─ Overview / Key Metrics

🏢 BUSINESS MANAGEMENT
   ├─ Parking Sessions [NEW]
   │  ├─ All Sessions
   │  ├─ Today's Entries
   │  ├─ Today's Exits
   │  └─ Session Reports
   │
   ├─ Parked Vehicles [NEW]
   │  ├─ Currently Parked
   │  ├─ By Location
   │  ├─ By Zone
   │  └─ Occupancy Report
   │
   ├─ Bookings / Reservations ✓
   │  ├─ All Bookings
   │  ├─ Pending Confirmation
   │  ├─ Active Bookings
   │  └─ Completed
   │
   ├─ Parking Slots ✓ (from "Parking Lots")
   │  ├─ All Slots
   │  ├─ Available
   │  ├─ Occupied
   │  └─ Maintenance
   │
   ├─ Parking Rates [NEW]
   │  ├─ Rate Configuration
   │  ├─ Zone-wise Rates
   │  ├─ Vehicle Type Rates
   │  └─ Special Rates
   │
   ├─ Access Control / QR [NEW]
   │  ├─ Generate QR
   │  ├─ QR Validation
   │  ├─ Scanned History
   │  └─ Invalid Scans
   │
   ├─ Vehicle Management ✓
   │  ├─ All Vehicles
   │  ├─ Verification Pending
   │  └─ Add Vehicle
   │
   ├─ Billing & Invoices [NEW]
   │  ├─ All Invoices
   │  ├─ Unpaid
   │  ├─ Paid
   │  ├─ Overdue
   │  └─ Generate Invoice
   │
   ├─ Payments ✓
   │  ├─ All Payments
   │  ├─ Completed
   │  ├─ Pending
   │  └─ Failed
   │
   ├─ Reports ✓
   │  ├─ Dashboard Report
   │  ├─ Revenue Report
   │  ├─ Occupancy Report
   │  └─ Booking Report
   │
   ├─ Contact Diary [NEW]
   │  ├─ All Contacts
   │  ├─ By Customer
   │  ├─ By Date
   │  └─ Add Contact
   │
   └─ Notice Board [NEW]
      ├─ All Notices
      ├─ Published
      ├─ Drafts
      └─ Add Notice

⚙️ SYSTEM CONFIGURATION
   ├─ Parking Zones [NEW]
   │  ├─ All Zones
   │  ├─ Zone Details
   │  ├─ Zone Occupancy
   │  └─ Add Zone
   │
   ├─ Parking Floors [NEW]
   │  ├─ All Floors
   │  ├─ By Zone
   │  ├─ Floor Configuration
   │  └─ Add Floor
   │
   ├─ Vehicle Types [NEW]
   │  ├─ All Types
   │  ├─ Type Configuration
   │  ├─ Rate Multipliers
   │  └─ Add Type
   │
   ├─ Slot Configuration [NEW]
   │  ├─ Slot Templates
   │  ├─ Size Categories
   │  ├─ Type Categories
   │  └─ Special Categories
   │
   ├─ Email Notifications [NEW]
   │  ├─ Email Templates
   │  ├─ Notification Rules
   │  ├─ Scheduled Emails
   │  └─ Email History
   │
   └─ SMS / Alert Notifications [NEW]
      ├─ SMS Templates
      ├─ Alert Rules
      ├─ Scheduled SMS
      └─ SMS History

👥 USER & ACCESS CONTROL
   ├─ User Management ✓
   │  ├─ All Users
   │  ├─ Active Users
   │  ├─ Suspended Users
   │  └─ Add User
   │
   ├─ Roles & Permissions ✓
   │  ├─ Roles
   │  ├─ Permissions
   │  ├─ User Role Assignment
   │  └─ Role Permissions
   │
   └─ Operator / Gate Mode [NEW]
      ├─ Gate Configuration
      ├─ Operator Dashboard
      ├─ Active Sessions
      └─ Operator Activity

📈 MONITORING & CONTROL
   ├─ Live Dashboard [NEW]
   │  ├─ Real-Time Occupancy
   │  ├─ Revenue Dashboard
   │  ├─ Active Sessions
   │  ├─ Zone Status
   │  └─ Alerts & Issues
   │
   ├─ Audit Logs ✓
   │  ├─ System Logs
   │  ├─ User Logs
   │  └─ Action Logs
   │
   ├─ Activity Logs [NEW]
   │  ├─ User Activities
   │  ├─ System Activities
   │  ├─ Filter by Date
   │  └─ Export Activities
   │
   ├─ Maintenance / Slot Blocking [NEW]
   │  ├─ Active Maintenance
   │  ├─ Schedule Maintenance
   │  ├─ Blocked Slots
   │  └─ Maintenance History
   │
   └─ Overstay Management [NEW]
      ├─ Active Overstays
      ├─ Overstay Charges
      ├─ Overstay Alerts
      └─ Overstay Reports

⚙️ SYSTEM SETTINGS
   ├─ Pricing [NEW]
   │  ├─ Pricing Rules
   │  ├─ Peak/Off-Peak
   │  ├─ Discounts & Promos
   │  └─ Bulk Pricing
   │
   ├─ General Settings ✓
   │  ├─ Company Info
   │  ├─ System Configuration
   │  └─ Localization
   │
   ├─ Branding [NEW]
   │  ├─ Logo & Images
   │  ├─ Color Scheme
   │  ├─ Company Details
   │  └─ Email Branding
   │
   ├─ Backup & Restore [NEW]
   │  ├─ Create Backup
   │  ├─ Backup History
   │  ├─ Restore Database
   │  └─ Backup Schedule
   │
   ├─ API & Integration [NEW]
   │  ├─ API Keys
   │  ├─ Webhooks
   │  ├─ Third-party Apps
   │  └─ Integration Logs
   │
   └─ My Profile ✓
      ├─ Profile Settings
      ├─ Change Password
      └─ Logout
```

---

## MIGRATION PATH

### Current → Target
```
EXISTING ITEMS (Keep & Enhance):
✓ Dashboard → Enhance with more metrics
✓ Vehicles → Keep as is, enhance
✓ Parking Lots → Rename to "Parking Slots"
✓ Bookings → Keep as is, enhance
✓ Users → Keep as is
✓ Payments → Keep as is
✓ Invoices → Move to "Billing & Invoices" (NEW section)
✓ Reports → Keep as is, enhance
✓ Permissions → Keep as is
✓ Settings → Keep as is, add subsections
✓ My Profile → Keep as is

NEW SECTIONS TO CREATE:
[ ] Business Management (container section)
    [ ] Parking Sessions
    [ ] Parked Vehicles
    [ ] Parking Rates
    [ ] Access Control / QR
    [ ] Contact Diary
    [ ] Notice Board
    
[ ] System Configuration (new/enhanced)
    [ ] Parking Zones
    [ ] Parking Floors
    [ ] Vehicle Types
    [ ] Slot Configuration
    [ ] Email Notifications
    [ ] SMS Notifications

[ ] Monitoring & Control (new section)
    [ ] Live Dashboard
    [ ] Activity Logs
    [ ] Maintenance / Slot Blocking
    [ ] Overstay Management

REORGANIZE:
[ ] User & Access Control
    - Move from other locations
    - Add: Operator / Gate Mode
```

---

## Statistics

| Category | Current | Target | New | % Complete |
|----------|---------|--------|-----|-----------|
| Business Management | 6 | 13 | 7 | 46% |
| System Configuration | 1 | 6 | 5 | 17% |
| User & Access Control | 2 | 3 | 1 | 67% |
| Monitoring & Control | 1 | 5 | 4 | 20% |
| System Settings | 1 | 5 | 4 | 20% |
| **TOTAL** | **12** | **33** | **21** | **36%** |

---

## NEXT STEPS

1. ✅ Gap Analysis Complete
2. ✅ Module Breakdown Created
3. → **Start Implementation**:
   - Phase 1: Parking Zones & Floors + Vehicle Types & Rates
   - Test all CRUD operations
   - Commit to git
4. → Phase 2: Access Control / QR + Parking Sessions
5. → Continue with remaining phases

