<?php

return [
    'title' => 'Admin Panel',
    'dashboard' => 'Dashboard',
    'overview' => 'Overview',
    'statistics' => 'Statistics',

    // Navigation
    'navigation' => [
        'dashboard' => 'Dashboard',
        'users' => 'User Management',
        'vehicles' => 'Vehicle Management',
        'parking' => 'Parking Management',
        'bookings' => 'Booking Management',
        'payments' => 'Payment Management',
        'gates' => 'Gate Management',
        'reports' => 'Reports & Analytics',
        'audit_logs' => 'Audit Logs',
        'system' => 'System Management',
        'settings' => 'Settings',
        'emergency' => 'Emergency Operations'
    ],

    // Dashboard
    'dashboard_stats' => [
        'total_users' => 'Total Users',
        'active_bookings' => 'Active Bookings',
        'total_revenue' => 'Total Revenue',
        'parking_occupancy' => 'Parking Occupancy',
        'pending_verifications' => 'Pending Verifications',
        'total_vehicles' => 'Total Vehicles',
        'today_revenue' => 'Today\'s Revenue',
        'month_revenue' => 'This Month\'s Revenue',
        'recent_activities' => 'Recent Activities',
        'system_status' => 'System Status'
    ],

    // User Management
    'users' => [
        'title' => 'User Management',
        'create' => 'Create User',
        'edit' => 'Edit User',
        'delete' => 'Delete User',
        'suspend' => 'Suspend User',
        'activate' => 'Activate User',
        'view_profile' => 'View Profile',
        'assign_role' => 'Assign Role',
        'remove_role' => 'Remove Role',
        'status' => [
            'active' => 'Active',
            'suspended' => 'Suspended',
            'pending_verification' => 'Pending Verification'
        ],
        'fields' => [
            'name' => 'Full Name',
            'mobile' => 'Mobile Number',
            'status' => 'Status',
            'created_at' => 'Registration Date',
            'last_login' => 'Last Login',
            'total_bookings' => 'Total Bookings',
            'total_spent' => 'Total Spent'
        ]
    ],

    // Vehicle Management
    'vehicles' => [
        'title' => 'Vehicle Management',
        'add_new' => 'Add New Vehicle',
        'label_plural' => 'Vehicles',
        'pending_verification' => 'Pending Verification',
        'verify' => 'Verify Vehicle',
        'reject' => 'Reject Vehicle',
        'view_documents' => 'View Documents',
        'verification_status' => [
            'pending' => 'Pending',
            'verified' => 'Verified',
            'rejected' => 'Rejected'
        ],
        'fields' => [
            'registration_number' => 'Registration Number',
            'owner' => 'Owner',
            'type' => 'Vehicle Type',
            'brand' => 'Brand',
            'model' => 'Model',
            'verification_status' => 'Verification Status',
            'created_at' => 'Registration Date'
        ]
    ],

    // Parking Management
    'parking' => [
        'title' => 'Parking Management',
        'locations' => 'Parking Locations',
        'slots' => 'Parking Slots',
        'slot_types' => 'Slot Types',
        'occupancy' => 'Occupancy Monitor',
        'create_location' => 'Create Location',
        'edit_location' => 'Edit Location',
        'create_slot' => 'Create Slot',
        'edit_slot' => 'Edit Slot',
        'slot_status' => [
            'available' => 'Available',
            'occupied' => 'Occupied',
            'maintenance' => 'Maintenance',
            'reserved' => 'Reserved'
        ],
        'slot_types' => [
            'regular' => 'Regular',
            'premium' => 'Premium',
            'handicap' => 'Handicap'
        ]
    ],

    // Booking Management
    'bookings' => [
        'title' => 'Booking Management',
        'active' => 'Active Bookings',
        'expired' => 'Expired Bookings',
        'history' => 'Booking History',
        'cancel' => 'Cancel Booking',
        'extend' => 'Extend Booking',
        'force_end' => 'Force End',
        'status' => [
            'active' => 'Active',
            'completed' => 'Completed',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled'
        ]
    ],

    // Payment Management
    'payments' => [
        'title' => 'Payment Management',
        'transactions' => 'Payment Transactions',
        'search_placeholder' => 'Search by Payment ID or User...',
        'all_methods' => 'All Methods',
        'reconciliation' => 'Reconciliation',
        'refunds' => 'Refunds',
        'gateway_logs' => 'Gateway Logs',
        'process_refund' => 'Process Refund',
        'verify_payment' => 'Verify Payment',
        'status' => [
            'initiated' => 'Initiated',
            'pending' => 'Pending',
            'processing' => 'Processing',
            'paid' => 'Paid',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            'cancelled' => 'Cancelled'
        ],
        'methods' => [
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
            'card' => 'Credit/Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'wallet' => 'Wallet'
        ]
    ],

    // Invoice Management
    'invoices' => [
        'title' => 'Invoice Management',
        'management' => 'Invoices Management',
        'search_placeholder' => 'Search by Invoice # or User...',
        'create_title' => 'Create Invoice',
        'create_new_invoice' => 'Create New Invoice',
        'create_invoice' => 'Create Invoice',
        'select_parking_session' => 'Select a parking session to generate invoice',
        'select_session' => 'Select Parking Session',
        'search_session' => 'Search by license plate or zone...',
        'no_sessions_available' => 'No parking sessions available for invoicing',
        'invoice_summary' => 'Invoice Summary',
        'base_amount' => 'Base Amount',
        'tax' => 'Tax',
        'total' => 'Total',
        'invoice_info' => 'Invoice Information',
        'how_it_works' => 'How It Works',
        'step_1' => 'Select a completed parking session from the list',
        'step_2' => 'Review the invoice summary and details',
        'step_3' => 'Click "Create Invoice" to generate the invoice',
        'step_4' => 'The invoice will be created and added to the system',
        'session_statistics' => 'Session Statistics',
        'total_available' => 'Total Available Sessions',
        'total_amount' => 'Total Amount',
        'duration' => 'Duration',
        'status' => [
            'paid' => 'Paid',
            'unpaid' => 'Unpaid',
            'overdue' => 'Overdue'
        ]
    ],

    // Booking Management
    'bookings' => [
        'title' => 'Booking Management',
        'active' => 'Active Bookings',
        'expired' => 'Expired Bookings',
        'history' => 'Booking History',
        'cancel' => 'Cancel Booking',
        'extend' => 'Extend Booking',
        'force_end' => 'Force End',
        'status' => [
            'active' => 'Active',
            'completed' => 'Completed',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled'
        ]
    ],

    // Parking Management
    'parking' => [
        'title' => 'Parking Management',
        'locations' => 'Parking Locations',
        'slots' => 'Parking Slots',
        'slot_types' => 'Slot Types',
        'occupancy' => 'Occupancy Monitor',
        'create_location' => 'Create Location',
        'edit_location' => 'Edit Location',
        'create_slot' => 'Create Slot',
        'edit_slot' => 'Edit Slot',
        'slot_status' => [
            'available' => 'Available',
            'occupied' => 'Occupied',
            'maintenance' => 'Maintenance',
            'reserved' => 'Reserved'
        ],
        'slot_types' => [
            'regular' => 'Regular',
            'premium' => 'Premium',
            'handicap' => 'Handicap'
        ]
    ],

    // Gate Management
    'gates' => [
        'title' => 'Gate Management',
        'entry_logs' => 'Entry Logs',
        'exit_logs' => 'Exit Logs',
        'access_control' => 'Access Control',
        'manage_gates' => 'Manage Gates',
        'gate_status' => [
            'online' => 'Online',
            'offline' => 'Offline',
            'maintenance' => 'Maintenance'
        ]
    ],

    // Reports & Analytics
    'reports' => [
        'title' => 'Reports & Analytics',
        'revenue' => 'Revenue Reports',
        'bookings' => 'Booking Reports',
        'vehicles' => 'Vehicle Reports',
        'users' => 'User Reports',
        'occupancy' => 'Occupancy Reports',
        'export' => 'Export Report',
        'date_range' => 'Date Range',
        'group_by' => 'Group By',
        'filter' => 'Filter',
        'generate' => 'Generate Report',
        'export_formats' => [
            'csv' => 'CSV',
            'pdf' => 'PDF',
            'excel' => 'Excel'
        ]
    ],

    // Audit Logs
    'audit' => [
        'title' => 'Audit Logs',
        'view_details' => 'View Details',
        'export_logs' => 'Export Logs',
        'user_logs' => 'User Logs',
        'system_logs' => 'System Logs',
        'actions' => [
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'access' => 'Accessed',
            'verify' => 'Verified',
            'reject' => 'Rejected',
            'suspend' => 'Suspended',
            'activate' => 'Activated'
        ]
    ],

    // System Management
    'system' => [
        'title' => 'System Management',
        'health' => 'System Health',
        'monitoring' => 'System Monitoring',
        'cache' => 'Cache Management',
        'queues' => 'Queue Status',
        'logs' => 'System Logs',
        'clear_cache' => 'Clear Cache',
        'restart_queues' => 'Restart Queues',
        'system_status' => [
            'online' => 'Online',
            'degraded' => 'Degraded',
            'offline' => 'Offline'
        ]
    ],

    // Settings
    'settings' => [
        'title' => 'System Settings',
        'general' => 'General Settings',
        'parking_rates' => 'Parking Rates',
        'payment_gateway' => 'Payment Gateway',
        'brta_config' => 'BRTA Configuration',
        'security' => 'Security Settings',
        'notifications' => 'Notification Settings',
        'update_settings' => 'Update Settings',
        'reset_to_default' => 'Reset to Default'
    ],

    // Emergency Operations
    'emergency' => [
        'title' => 'Emergency Operations',
        'force_exit_all' => 'Force Exit All Vehicles',
        'lock_system' => 'Lock System',
        'unlock_system' => 'Unlock System',
        'broadcast_message' => 'Broadcast Message',
        'emergency_contact' => 'Emergency Contact',
        'confirm_action' => 'Confirm Emergency Action'
    ],

    // System Settings
    'settings' => [
        'title' => 'System Settings',
        'general' => 'General Settings',
        'parking_rates' => 'Parking Rates',
        'working_hours' => 'Working Hours',
        'payment_gateway' => 'Payment Gateway',
        'email_settings' => 'Email Settings',
        'maintenance_mode' => 'Maintenance Mode'
    ],

    // Common Actions
    'actions' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'update' => 'Update',
        'delete' => 'Delete',
        'view' => 'View',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'confirm' => 'Confirm',
        'search' => 'Search',
        'filter' => 'Filter',
        'export' => 'Export',
        'import' => 'Import',
        'refresh' => 'Refresh',
        'clear' => 'Clear',
        'reset' => 'Reset'
    ],

    // Messages
    'messages' => [
        'success' => [
            'created' => 'Successfully created',
            'updated' => 'Successfully updated',
            'deleted' => 'Successfully deleted',
            'verified' => 'Successfully verified',
            'activated' => 'Successfully activated',
            'suspended' => 'Successfully suspended'
        ],
        'errors' => [
            'not_found' => 'Record not found',
            'unauthorized' => 'Unauthorized access',
            'validation_failed' => 'Validation failed',
            'operation_failed' => 'Operation failed'
        ],
        'confirmations' => [
            'delete' => 'Are you sure you want to delete this item?',
            'suspend' => 'Are you sure you want to suspend this user?',
            'emergency' => 'This is an emergency operation. Are you sure?'
        ]
    ],

    // Permissions
    'permissions' => [
        'title' => 'Permissions Management',
        'roles' => 'Roles Management',
        'assign_permission' => 'Assign Permission',
        'remove_permission' => 'Remove Permission',
        'create_role' => 'Create Role',
        'edit_role' => 'Edit Role',
        'role_permissions' => 'Role Permissions'
    ],

    // Phase 1: Parking Management (Zones, Floors, Vehicles, Rates)
    'parking_zones' => [
        'title' => 'Parking Zones',
        'singular' => 'Zone',
        'create' => 'Create Zone',
        'edit' => 'Edit Zone',
        'delete' => 'Delete Zone',
        'restore' => 'Restore Zone',
        'zone_created_successfully' => 'Zone created successfully',
        'zone_updated_successfully' => 'Zone updated successfully',
        'zone_deleted_successfully' => 'Zone deleted successfully',
        'zone_restored_successfully' => 'Zone restored successfully'
    ],

    'parking_floors' => [
        'title' => 'Parking Floors',
        'singular' => 'Floor',
        'create' => 'Create Floor',
        'edit' => 'Edit Floor',
        'delete' => 'Delete Floor',
        'restore' => 'Restore Floor',
        'floor_created_successfully' => 'Floor created successfully',
        'floor_updated_successfully' => 'Floor updated successfully',
        'floor_deleted_successfully' => 'Floor deleted successfully',
        'floor_restored_successfully' => 'Floor restored successfully',
        'floor_already_exists_in_zone' => 'A floor with this number already exists in this zone'
    ],

    'vehicle_types' => [
        'title' => 'Vehicle Types',
        'singular' => 'Vehicle Type',
        'create' => 'Create Vehicle Type',
        'edit' => 'Edit Vehicle Type',
        'delete' => 'Delete Vehicle Type',
        'restore' => 'Restore Vehicle Type',
        'vehicle_type_created_successfully' => 'Vehicle type created successfully',
        'vehicle_type_updated_successfully' => 'Vehicle type updated successfully',
        'vehicle_type_deleted_successfully' => 'Vehicle type deleted successfully',
        'vehicle_type_restored_successfully' => 'Vehicle type restored successfully',
        'cannot_delete_vehicle_type_has_rates' => 'Cannot delete vehicle type that has associated rates'
    ],

    'parking_rates' => [
        'title' => 'Parking Rates & Pricing',
        'singular' => 'Parking Rate',
        'create' => 'Create Rate',
        'edit' => 'Edit Rate',
        'delete' => 'Delete Rate',
        'restore' => 'Restore Rate',
        'rate_created_successfully' => 'Parking rate created successfully',
        'rate_updated_successfully' => 'Parking rate updated successfully',
        'rate_deleted_successfully' => 'Parking rate deleted successfully',
        'rate_restored_successfully' => 'Parking rate restored successfully',
        'rate_already_exists_for_zone_vehicle' => 'A rate already exists for this zone and vehicle type combination',
        'rates_imported_successfully' => ':count parking rates imported successfully'
    ],

    'parking_gates' => [
        'title' => 'Parking Gates',
        'singular' => 'Gate',
        'create' => 'Create Gate',
        'edit' => 'Edit Gate',
        'delete' => 'Delete Gate',
        'restore' => 'Restore Gate',
        'gate_created_successfully' => 'Parking gate created successfully',
        'gate_updated_successfully' => 'Parking gate updated successfully',
        'gate_deleted_successfully' => 'Parking gate deleted successfully',
        'gate_restored_successfully' => 'Parking gate restored successfully',
        'all_gates' => 'All Gates',
        'add_gate' => 'Add Gate'
    ],

    'qr_codes' => [
        'title' => 'QR Code Management',
        'singular' => 'QR Code',
        'create' => 'Create QR Code',
        'edit' => 'Edit QR Code',
        'delete' => 'Delete QR Code',
        'restore' => 'Restore QR Code',
        'qr_code_created_successfully' => 'QR code created successfully',
        'qr_code_updated_successfully' => 'QR code updated successfully',
        'qr_code_deleted_successfully' => 'QR code deleted successfully',
        'qr_code_restored_successfully' => 'QR code restored successfully',
        'all_qr_codes' => 'All QR Codes',
        'add_qr_code' => 'Add QR Code',
        'qr_statistics' => 'QR Statistics',
        'bulk_generate' => 'Bulk Generate'
    ],

    'parking_access_logs' => [
        'title' => 'Access Logs',
        'singular' => 'Access Log',
        'view' => 'View Access Logs',
        'entries' => 'Entries',
        'exits' => 'Exits',
        'allowed' => 'Allowed Access',
        'denied' => 'Denied Access',
        'pending' => 'Pending Access',
        'alerts' => 'Access Alerts'
    ],

    'parking_sessions' => [
        'title' => 'Parking Sessions',
        'singular' => 'Session',
        'create' => 'Create Session',
        'edit' => 'Edit Session',
        'delete' => 'Delete Session',
        'restore' => 'Restore Session',
        'session_created_successfully' => 'Parking session created successfully',
        'session_updated_successfully' => 'Parking session updated successfully',
        'session_deleted_successfully' => 'Parking session deleted successfully',
        'session_restored_successfully' => 'Parking session restored successfully',
        'active_sessions' => 'Active Sessions',
        'all_sessions' => 'All Sessions',
        'new_session' => 'New Session',
        'occupancy_monitor' => 'Occupancy Monitor',
        'session_analytics' => 'Session Analytics',
        'session_marked_exited' => 'Session marked as exited',
        'session_extended' => 'Session extended successfully',
        'session_cancelled' => 'Session cancelled successfully',
        'payment_collected' => 'Payment collected successfully'
    ],

    // Phase 4: Operator Mode
    'operator' => [
        'title' => 'Gate Operator Panel',
        'dashboard' => 'Operator Dashboard',
        'quick_scan' => 'Quick Scan',
        'quick_operations' => 'Quick Operations',
        'active_sessions_count' => 'Active Sessions',
        'revenue_today' => 'Revenue Today',
        'pending_payments_count' => 'Pending Payments',
        'overstayed_count' => 'Overstayed Vehicles',
        'recent_activities' => 'Recent Activities',
        'entry' => 'Vehicle Entry',
        'exit' => 'Vehicle Exit',
        'manual_entry' => 'Manual Entry',
        'manual_exit' => 'Manual Exit',
        'search' => 'Search Vehicle',
        'scan_qr' => 'Scan QR Code',
        'alerts' => 'Real-time Alerts',
        'gate_status' => 'Gate Status',
        'entry_recorded' => 'Entry recorded successfully',
        'exit_recorded' => 'Exit recorded successfully',
        'search_results' => 'Search Results',
        'no_results' => 'No sessions found',
    ],

    // Phase 5: Billing & Invoicing
    'billing' => [
        'title' => 'Billing & Invoicing',
        'invoices' => 'Invoices',
        'create_invoice' => 'Create Invoice',
        'invoice_list' => 'Invoice List',
        'revenue_reports' => 'Revenue Reports',
        'overdue_invoices' => 'Overdue Invoices',
        'generate_from_session' => 'Generate from Session',
        'mark_as_paid' => 'Mark as Paid',
        'record_payment' => 'Record Payment',
        'cancel_invoice' => 'Cancel Invoice',
        'process_refund' => 'Process Refund',
        'download_pdf' => 'Download PDF',
        'total_revenue' => 'Total Revenue',
        'total_overdue' => 'Total Overdue',
        'pending_payments' => 'Pending Payments',
        'invoice_generated' => 'Invoice generated successfully',
        'payment_recorded' => 'Payment recorded successfully',
        'refund_processed' => 'Refund processed successfully',
    ]
];
