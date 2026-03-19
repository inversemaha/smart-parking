<?php

return [
    'title' => 'Gate Operator Dashboard',
    'dashboard' => 'Dashboard',
    'quick_scan' => 'Quick Scan',
    
    // Dashboard stats
    'stats' => [
        'active_sessions' => 'Active Sessions',
        'total_today' => 'Today\'s Entries',
        'pending_payments' => 'Pending Payments',
        'total_revenue' => 'Revenue Today',
        'overstayed_vehicles' => 'Overstayed Vehicles',
    ],

    // Quick operations
    'operations' => [
        'entry' => 'Entry',
        'exit' => 'Exit',
        'scan_qr' => 'Scan QR Code',
        'manual_entry' => 'Manual Entry',
        'manual_exit' => 'Manual Exit',
        'search_vehicle' => 'Search Vehicle',
    ],

    // Messages
    'messages' => [
        'entry_recorded_successfully' => 'Entry recorded successfully',
        'exit_recorded_successfully' => 'Exit recorded successfully',
        'session_already_active' => 'Vehicle already has an active parking session',
        'no_active_session' => 'No active session found for this vehicle',
        'invalid_qr_code' => 'Invalid QR code',
        'search_term_too_short' => 'Search term must be at least 2 characters',
        'vehicle_info_retrieved' => 'Vehicle information retrieved',
    ],

    // Alert types
    'alerts' => [
        'overstay' => 'Overstay Alert',
        'long_park' => 'Long Parking Alert',
        'unpaid' => 'Unpaid Parking',
        'gate_maintenance' => 'Gate Maintenance',
        'equipment_issue' => 'Equipment Issue',
    ],

    // Fields
    'fields' => [
        'license_plate' => 'License Plate',
        'zone' => 'Parking Zone',
        'floor' => 'Floor',
        'entry_time' => 'Entry Time',
        'exit_time' => 'Exit Time',
        'duration' => 'Duration',
        'vehicle_category' => 'Vehicle Type',
        'entry_gate' => 'Entry Gate',
        'exit_gate' => 'Exit Gate',
        'total_charge' => 'Total Charge',
        'charging_status' => 'Payment Status',
        'session_status' => 'Session Status',
    ],

    // Session statuses
    'statuses' => [
        'active' => 'Active',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'extended' => 'Extended',
        'pending' => 'Pending',
        'collected' => 'Collected',
    ],

    // Gate operations
    'gate' => [
        'status' => 'Gate Status',
        'operational' => 'Operational',
        'maintenance' => 'Maintenance',
        'closed' => 'Closed',
        'recent_activity' => 'Recent Activity',
        'occupancy' => 'Current Occupancy',
    ],

    // Common actions
    'actions' => [
        'record_entry' => 'Record Entry',
        'record_exit' => 'Record Exit',
        'view_details' => 'View Details',
        'mark_extended' => 'Mark Extended',
        'cancel_session' => 'Cancel Session',
        'collect_payment' => 'Collect Payment',
    ],
];
