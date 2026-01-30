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
        'transactions' => 'Transactions',
        'reconciliation' => 'Reconciliation',
        'refunds' => 'Refunds',
        'gateway_logs' => 'Gateway Logs',
        'process_refund' => 'Process Refund',
        'verify_payment' => 'Verify Payment',
        'status' => [
            'pending' => 'Pending',
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
            'bank' => 'Bank Transfer'
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
    ]
];
