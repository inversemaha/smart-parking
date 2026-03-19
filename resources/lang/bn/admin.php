<?php

return [
    'title' => 'অ্যাডমিন প্যানেল',
    'dashboard' => 'ড্যাশবোর্ড',
    'overview' => 'সংক্ষিপ্ত বিবরণ',
    'statistics' => 'পরিসংখ্যান',

    // Navigation
    'navigation' => [
        'dashboard' => 'ড্যাশবোর্ড',
        'users' => 'ব্যবহারকারী ব্যবস্থাপনা',
        'vehicles' => 'যানবাহন ব্যবস্থাপনা',
        'parking' => 'পার্কিং ব্যবস্থাপনা',
        'bookings' => 'বুকিং ব্যবস্থাপনা',
        'payments' => 'পেমেন্ট ব্যবস্থাপনা',
        'gates' => 'গেট ব্যবস্থাপনা',
        'reports' => 'রিপোর্ট ও বিশ্লেষণ',
        'audit_logs' => 'অডিট লগ',
        'system' => 'সিস্টেম ব্যবস্থাপনা',
        'settings' => 'সেটিংস',
        'emergency' => 'জরুরি অপারেশন'
    ],

    // Dashboard
    'dashboard_stats' => [
        'total_users' => 'মোট ব্যবহারকারী',
        'active_bookings' => 'সক্রিয় বুকিং',
        'total_revenue' => 'মোট আয়',
        'parking_occupancy' => 'পার্কিং দখল',
        'pending_verifications' => 'যাচাইয়ের অপেক্ষায়',
        'total_vehicles' => 'মোট যানবাহন',
        'today_revenue' => 'আজকের আয়',
        'month_revenue' => 'এই মাসের আয়',
        'recent_activities' => 'সাম্প্রতিক কার্যক্রম',
        'system_status' => 'সিস্টেম অবস্থা'
    ],

    // User Management
    'users' => [
        'title' => 'ব্যবহারকারী ব্যবস্থাপনা',
        'create' => 'ব্যবহারকারী তৈরি',
        'edit' => 'ব্যবহারকারী সম্পাদনা',
        'delete' => 'ব্যবহারকারী মুছুন',
        'suspend' => 'ব্যবহারকারী স্থগিত',
        'activate' => 'ব্যবহারকারী সক্রিয়',
        'view_profile' => 'প্রোফাইল দেখুন',
        'assign_role' => 'ভূমিকা বরাদ্দ',
        'remove_role' => 'ভূমিকা অপসারণ',
        'status' => [
            'active' => 'সক্রিয়',
            'suspended' => 'স্থগিত',
            'pending_verification' => 'যাচাইয়ের অপেক্ষায়'
        ],
        'fields' => [
            'name' => 'পূর্ণ নাম',
            'mobile' => 'মোবাইল নম্বর',
            'status' => 'অবস্থা',
            'created_at' => 'নিবন্ধনের তারিখ',
            'last_login' => 'শেষ লগইন',
            'total_bookings' => 'মোট বুকিং',
            'total_spent' => 'মোট খরচ'
        ]
    ],

    // Vehicle Management
    'vehicles' => [
        'title' => 'যানবাহন ব্যবস্থাপনা',
        'add_new' => 'নতুন যানবাহন যোগ করুন',
        'label_plural' => 'যানবাহন',
        'pending_verification' => 'যাচাইয়ের অপেক্ষায়',
        'verify' => 'যানবাহন যাচাই',
        'reject' => 'যানবাহন প্রত্যাখ্যান',
        'view_documents' => 'নথি দেখুন',
        'verification_status' => [
            'pending' => 'অপেক্ষমান',
            'verified' => 'যাচাই করা',
            'rejected' => 'প্রত্যাখ্যাত'
        ],
        'fields' => [
            'registration_number' => 'নিবন্ধন নম্বর',
            'owner' => 'মালিক',
            'type' => 'যানবাহনের ধরণ',
            'brand' => 'ব্র্যান্ড',
            'model' => 'মডেল',
            'verification_status' => 'যাচাই অবস্থা',
            'created_at' => 'নিবন্ধনের তারিখ'
        ]
    ],

    // Parking Management
    'parking' => [
        'title' => 'পার্কিং ব্যবস্থাপনা',
        'locations' => 'পার্কিং স্থান',
        'slots' => 'পার্কিং স্লট',
        'slot_types' => 'স্লটের ধরণ',
        'occupancy' => 'দখল মনিটর',
        'create_location' => 'স্থান তৈরি',
        'edit_location' => 'স্থান সম্পাদনা',
        'create_slot' => 'স্লট তৈরি',
        'edit_slot' => 'স্লট সম্পাদনা',
        'slot_status' => [
            'available' => 'উপলব্ধ',
            'occupied' => 'দখলকৃত',
            'maintenance' => 'রক্ষণাবেক্ষণ',
            'reserved' => 'সংরক্ষিত'
        ],
        'slot_types' => [
            'regular' => 'নিয়মিত',
            'premium' => 'প্রিমিয়াম',
            'handicap' => 'প্রতিবন্ধী'
        ]
    ],

    // Booking Management
    'bookings' => [
        'title' => 'বুকিং ব্যবস্থাপনা',
        'active' => 'সক্রিয় বুকিং',
        'expired' => 'মেয়াদোত্তীর্ণ বুকিং',
        'history' => 'বুকিংয়ের ইতিহাস',
        'cancel' => 'বুকিং বাতিল',
        'extend' => 'বুকিং বর্ধিত',
        'force_end' => 'জোর করে সমাপ্তি',
        'status' => [
            'active' => 'সক্রিয়',
            'completed' => 'সম্পন্ন',
            'expired' => 'মেয়াদোত্তীর্ণ',
            'cancelled' => 'বাতিল'
        ]
    ],

    // Payment Management
    'payments' => [
        'title' => 'পেমেন্ট ব্যবস্থাপনা',
        'transactions' => 'পেমেন্ট লেনদেন',
        'search_placeholder' => 'পেমেন্ট আইডি বা ব্যবহারকারীর নাম দিয়ে অনুসন্ধান করুন...',
        'all_methods' => 'সমস্ত পদ্ধতি',
        'reconciliation' => 'পুনর্মিলন',
        'refunds' => 'ফেরত',
        'gateway_logs' => 'গেটওয়ে লগ',
        'process_refund' => 'ফেরত প্রক্রিয়া',
        'verify_payment' => 'পেমেন্ট যাচাই',
        'status' => [
            'initiated' => 'শুরু করা হয়েছে',
            'pending' => 'অপেক্ষমান',
            'processing' => 'প্রক্রিয়াকরণ',
            'paid' => 'প্রদত্ত',
            'completed' => 'সম্পন্ন',
            'failed' => 'ব্যর্থ',
            'refunded' => 'ফেরত',
            'cancelled' => 'বাতিল'
        ],
        'methods' => [
            'bkash' => 'বিকাশ',
            'nagad' => 'নগদ',
            'rocket' => 'রকেট',
            'card' => 'ক্রেডিট/ডেবিট কার্ড',
            'bank_transfer' => 'ব্যাংক ট্রান্সফার',
            'wallet' => 'ওয়ালেট'
        ]
    ],

    // Invoice Management
    'invoices' => [
        'title' => 'চালান ব্যবস্থাপনা',
        'management' => 'চালান ব্যবস্থাপনা',
        'search_placeholder' => 'চালান নম্বর বা ব্যবহারকারী দ্বারা অনুসন্ধান করুন...',
        'status' => [
            'paid' => 'প্রদত্ত',
            'unpaid' => 'অপ্রদত্ত',
            'overdue' => 'দেরী'
        ]
    ],

    // Booking Management
    'bookings' => [
        'title' => 'বুকিং ব্যবস্থাপনা',
        'search_placeholder' => 'বুকিং নম্বর, গাড়ি বা ব্যবহারকারী দ্বারা অনুসন্ধান করুন...',
        'status_pending' => 'অপেক্ষমান',
        'status_confirmed' => 'নিশ্চিত',
        'status_active' => 'সক্রিয়',
        'status_completed' => 'সম্পন্ন',
        'status_cancelled' => 'বাতিল',
        'active' => 'সক্রিয় বুকিং',
        'expired' => 'মেয়াদোত্তীর্ণ বুকিং',
        'history' => 'বুকিং ইতিহাস',
        'cancel' => 'বুকিং বাতিল করুন',
        'extend' => 'বুকিং বর্ধন করুন',
        'force_end' => 'জোরপূর্বক সমাপ্ত করুন',
        'status' => [
            'active' => 'সক্রিয়',
            'completed' => 'সম্পন্ন',
            'expired' => 'মেয়াদোত্তীর্ণ',
            'cancelled' => 'বাতিল'
        ]
    ],

    // Parking Management
    'parking' => [
        'title' => 'পার্কিং ব্যবস্থাপনা',
        'locations' => 'পার্কিং স্থান',
        'slots' => 'পার্কিং স্লট',
        'slot_types' => 'স্লটের ধরণ',
        'occupancy' => 'দখল মনিটর',
        'create_location' => 'অবস্থান তৈরি করুন',
        'edit_location' => 'অবস্থান সম্পাদনা করুন',
        'create_slot' => 'স্লট তৈরি করুন',
        'edit_slot' => 'স্লট সম্পাদনা করুন',
        'slot_status' => [
            'available' => 'উপলব্ধ',
            'occupied' => 'দখলকৃত',
            'maintenance' => 'রক্ষণাবেক্ষণ',
            'reserved' => 'সংরক্ষিত'
        ],
        'slot_types' => [
            'regular' => 'নিয়মিত',
            'premium' => 'প্রিমিয়াম',
            'handicap' => 'প্রতিবন্ধী'
        ]
    ],

    // Gate Management
    'gates' => [
        'title' => 'গেট ব্যবস্থাপনা',
        'entry_logs' => 'প্রবেশ লগ',
        'exit_logs' => 'প্রস্থান লগ',
        'access_control' => 'অ্যাক্সেস নিয়ন্ত্রণ',
        'manage_gates' => 'গেট পরিচালনা',
        'gate_status' => [
            'online' => 'অনলাইন',
            'offline' => 'অফলাইন',
            'maintenance' => 'রক্ষণাবেক্ষণ'
        ]
    ],

    // Reports & Analytics
    'reports' => [
        'title' => 'রিপোর্ট ও বিশ্লেষণ',
        'revenue' => 'আয়ের রিপোর্ট',
        'bookings' => 'বুকিং রিপোর্ট',
        'vehicles' => 'যানবাহন রিপোর্ট',
        'users' => 'ব্যবহারকারী রিপোর্ট',
        'occupancy' => 'দখল রিপোর্ট',
        'export' => 'রিপোর্ট রপ্তানি',
        'date_range' => 'তারিখের পরিসর',
        'group_by' => 'গ্রুপ বাই',
        'filter' => 'ফিল্টার',
        'generate' => 'রিপোর্ট তৈরি',
        'export_formats' => [
            'csv' => 'সিএসভি',
            'pdf' => 'পিডিএফ',
            'excel' => 'এক্সেল'
        ]
    ],

    // Audit Logs
    'audit' => [
        'title' => 'অডিট লগ',
        'view_details' => 'বিস্তারিত দেখুন',
        'export_logs' => 'লগ রপ্তানি',
        'user_logs' => 'ব্যবহারকারী লগ',
        'system_logs' => 'সিস্টেম লগ',
        'actions' => [
            'create' => 'তৈরি করা হয়েছে',
            'update' => 'আপডেট করা হয়েছে',
            'delete' => 'মুছে ফেলা হয়েছে',
            'login' => 'লগইন করেছেন',
            'logout' => 'লগআউট করেছেন',
            'access' => 'অ্যাক্সেস করেছেন',
            'verify' => 'যাচাই করেছেন',
            'reject' => 'প্রত্যাখ্যান করেছেন',
            'suspend' => 'স্থগিত করেছেন',
            'activate' => 'সক্রিয় করেছেন'
        ]
    ],

    // System Management
    'system' => [
        'title' => 'সিস্টেম ব্যবস্থাপনা',
        'health' => 'সিস্টেম স্বাস্থ্য',
        'monitoring' => 'সিস্টেম মনিটরিং',
        'cache' => 'ক্যাশ ব্যবস্থাপনা',
        'queues' => 'কিউ স্ট্যাটাস',
        'logs' => 'সিস্টেম লগ',
        'clear_cache' => 'ক্যাশ ক্লিয়ার',
        'restart_queues' => 'কিউ পুনরায় চালু',
        'system_status' => [
            'online' => 'অনলাইন',
            'degraded' => 'ক্ষতিগ্রস্ত',
            'offline' => 'অফলাইন'
        ]
    ],

    // Settings
    'settings' => [
        'title' => 'সিস্টেম সেটিংস',
        'general' => 'সাধারণ সেটিংস',
        'parking_rates' => 'পার্কিং রেট',
        'payment_gateway' => 'পেমেন্ট গেটওয়ে',
        'brta_config' => 'বিআরটিএ কনফিগারেশন',
        'security' => 'নিরাপত্তা সেটিংস',
        'notifications' => 'নোটিফিকেশন সেটিংস',
        'update_settings' => 'সেটিংস আপডেট',
        'reset_to_default' => 'ডিফল্টে রিসেট'
    ],

    // Emergency Operations
    'emergency' => [
        'title' => 'জরুরি অপারেশন',
        'force_exit_all' => 'সব যানবাহন জোর করে বের',
        'lock_system' => 'সিস্টেম লক',
        'unlock_system' => 'সিস্টেম আনলক',
        'broadcast_message' => 'বার্তা প্রচার',
        'emergency_contact' => 'জরুরি যোগাযোগ',
        'confirm_action' => 'জরুরি কার্য নিশ্চিত করুন'
    ],

    // System Settings
    'settings' => [
        'title' => 'সিস্টেম সেটিংস',
        'general' => 'সাধারণ সেটিংস',
        'parking_rates' => 'পার্কিং রেট',
        'working_hours' => 'কাজের সময়',
        'payment_gateway' => 'পেমেন্ট গেটওয়ে',
        'email_settings' => 'ইমেল সেটিংস',
        'maintenance_mode' => 'রক্ষণাবেক্ষণ মোড'
    ],

    // Common Actions
    'actions' => [
        'create' => 'তৈরি করুন',
        'edit' => 'সম্পাদনা',
        'update' => 'আপডেট',
        'delete' => 'মুছুন',
        'view' => 'দেখুন',
        'save' => 'সংরক্ষণ',
        'cancel' => 'বাতিল',
        'confirm' => 'নিশ্চিত',
        'search' => 'অনুসন্ধান',
        'filter' => 'ফিল্টার',
        'export' => 'রপ্তানি',
        'import' => 'আমদানি',
        'refresh' => 'রিফ্রেশ',
        'clear' => 'ক্লিয়ার',
        'reset' => 'রিসেট'
    ],

    // Messages
    'messages' => [
        'success' => [
            'created' => 'সফলভাবে তৈরি করা হয়েছে',
            'updated' => 'সফলভাবে আপডেট করা হয়েছে',
            'deleted' => 'সফলভাবে মুছে ফেলা হয়েছে',
            'verified' => 'সফলভাবে যাচাই করা হয়েছে',
            'activated' => 'সফলভাবে সক্রিয় করা হয়েছে',
            'suspended' => 'সফলভাবে স্থগিত করা হয়েছে'
        ],
        'errors' => [
            'not_found' => 'রেকর্ড পাওয়া যায়নি',
            'unauthorized' => 'অনধিকৃত অ্যাক্সেস',
            'validation_failed' => 'যাচাইকরণ ব্যর্থ',
            'operation_failed' => 'অপারেশন ব্যর্থ'
        ],
        'confirmations' => [
            'delete' => 'আপনি কি এই আইটেমটি মুছে ফেলার বিষয়ে নিশ্চিত?',
            'suspend' => 'আপনি কি এই ব্যবহারকারীকে স্থগিত করার বিষয়ে নিশ্চিত?',
            'emergency' => 'এটি একটি জরুরি অপারেশন। আপনি কি নিশ্চিত?'
        ]
    ],

    // Permissions
    'permissions' => [
        'title' => 'অনুমতি ব্যবস্থাপনা',
        'roles' => 'ভূমিকা ব্যবস্থাপনা',
        'assign_permission' => 'অনুমতি বরাদ্দ',
        'remove_permission' => 'অনুমতি অপসারণ',
        'create_role' => 'ভূমিকা তৈরি',
        'edit_role' => 'ভূমিকা সম্পাদনা',
        'role_permissions' => 'ভূমিকার অনুমতি'
    ],

    // Phase 1: Parking Management (Zones, Floors, Vehicles, Rates)
    'parking_zones' => [
        'title' => 'পার্কিং জোন',
        'singular' => 'জোন',
        'create' => 'জোন তৈরি করুন',
        'edit' => 'জোন সম্পাদনা করুন',
        'delete' => 'জোন মুছে ফেলুন',
        'restore' => 'জোন পুনরুদ্ধার করুন',
        'zone_created_successfully' => 'জোন সফলভাবে তৈরি হয়েছে',
        'zone_updated_successfully' => 'জোন সফলভাবে আপডেট হয়েছে',
        'zone_deleted_successfully' => 'জোন সফলভাবে মুছে ফেলা হয়েছে',
        'zone_restored_successfully' => 'জোন সফলভাবে পুনরুদ্ধার হয়েছে'
    ],

    'parking_floors' => [
        'title' => 'পার্কিং ফ্লোর',
        'singular' => 'ফ্লোর',
        'create' => 'ফ্লোর তৈরি করুন',
        'edit' => 'ফ্লোর সম্পাদনা করুন',
        'delete' => 'ফ্লোর মুছে ফেলুন',
        'restore' => 'ফ্লোর পুনরুদ্ধার করুন',
        'floor_created_successfully' => 'ফ্লোর সফলভাবে তৈরি হয়েছে',
        'floor_updated_successfully' => 'ফ্লোর সফলভাবে আপডেট হয়েছে',
        'floor_deleted_successfully' => 'ফ্লোর সফলভাবে মুছে ফেলা হয়েছে',
        'floor_restored_successfully' => 'ফ্লোর সফলভাবে পুনরুদ্ধার হয়েছে',
        'floor_already_exists_in_zone' => 'এই জোনে ইতিমধ্যে এই ফ্লোর নম্বর বিদ্যমান'
    ],

    'vehicle_types' => [
        'title' => 'যানবাহন প্রকার',
        'singular' => 'যানবাহন প্রকার',
        'create' => 'যানবাহন প্রকার তৈরি করুন',
        'edit' => 'যানবাহন প্রকার সম্পাদনা করুন',
        'delete' => 'যানবাহন প্রকার মুছে ফেলুন',
        'restore' => 'যানবাহন প্রকার পুনরুদ্ধার করুন',
        'vehicle_type_created_successfully' => 'যানবাহন প্রকার সফলভাবে তৈরি হয়েছে',
        'vehicle_type_updated_successfully' => 'যানবাহন প্রকার সফলভাবে আপডেট হয়েছে',
        'vehicle_type_deleted_successfully' => 'যানবাহন প্রকার সফলভাবে মুছে ফেলা হয়েছে',
        'vehicle_type_restored_successfully' => 'যানবাহন প্রকার সফলভাবে পুনরুদ্ধার হয়েছে',
        'cannot_delete_vehicle_type_has_rates' => 'সংযুক্ত রেট সহ যানবাহন প্রকার মুছে ফেলা যায় না'
    ],

    'parking_rates' => [
        'title' => 'পার্কিং রেট এবং মূল্য নির্ধারণ',
        'singular' => 'পার্কিং রেট',
        'create' => 'রেট তৈরি করুন',
        'edit' => 'রেট সম্পাদনা করুন',
        'delete' => 'রেট মুছে ফেলুন',
        'restore' => 'রেট পুনরুদ্ধার করুন',
        'rate_created_successfully' => 'পার্কিং রেট সফলভাবে তৈরি হয়েছে',
        'rate_updated_successfully' => 'পার্কিং রেট সফলভাবে আপডেট হয়েছে',
        'rate_deleted_successfully' => 'পার্কিং রেট সফলভাবে মুছে ফেলা হয়েছে',
        'rate_restored_successfully' => 'পার্কিং রেট সফলভাবে পুনরুদ্ধার হয়েছে',
        'rate_already_exists_for_zone_vehicle' => 'এই জোন এবং যানবাহন প্রকারের জন্য ইতিমধ্যে একটি রেট বিদ্যমান',
        'rates_imported_successfully' => ':count পার্কিং রেট সফলভাবে আমদানি করা হয়েছে'
    ],

    // Phase 2: Access Control & QR Codes
    'parking_gates' => [
        'title' => 'পার্কিং গেট',
        'singular' => 'গেট',
        'create' => 'গেট তৈরি করুন',
        'edit' => 'গেট সম্পাদনা করুন',
        'delete' => 'গেট মুছে ফেলুন',
        'restore' => 'গেট পুনরুদ্ধার করুন',
        'gate_created_successfully' => 'পার্কিং গেট সফলভাবে তৈরি হয়েছে',
        'gate_updated_successfully' => 'পার্কিং গেট সফলভাবে আপডেট হয়েছে',
        'gate_deleted_successfully' => 'পার্কিং গেট সফলভাবে মুছে ফেলা হয়েছে',
        'gate_restored_successfully' => 'পার্কিং গেট সফলভাবে পুনরুদ্ধার হয়েছে',
        'all_gates' => 'সমস্ত গেট',
        'add_gate' => 'গেট যোগ করুন'
    ],

    'qr_codes' => [
        'title' => 'কিউআর কোড ব্যবস্থাপনা',
        'singular' => 'কিউআর কোড',
        'create' => 'কিউআর কোড তৈরি করুন',
        'edit' => 'কিউআর কোড সম্পাদনা করুন',
        'delete' => 'কিউআর কোড মুছে ফেলুন',
        'restore' => 'কিউআর কোড পুনরুদ্ধার করুন',
        'qr_code_created_successfully' => 'কিউআর কোড সফলভাবে তৈরি হয়েছে',
        'qr_code_updated_successfully' => 'কিউআর কোড সফলভাবে আপডেট হয়েছে',
        'qr_code_deleted_successfully' => 'কিউআর কোড সফলভাবে মুছে ফেলা হয়েছে',
        'qr_code_restored_successfully' => 'কিউআর কোড সফলভাবে পুনরুদ্ধার হয়েছে',
        'all_qr_codes' => 'সমস্ত কিউআর কোড',
        'add_qr_code' => 'কিউআর কোড যোগ করুন',
        'qr_statistics' => 'কিউআর পরিসংখ্যান',
        'bulk_generate' => 'বাল্ক জেনারেট'
    ],

    'parking_access_logs' => [
        'title' => 'অ্যাক্সেস লগ',
        'singular' => 'অ্যাক্সেস লগ',
        'view' => 'অ্যাক্সেস লগ দেখুন',
        'entries' => 'প্রবেশ',
        'exits' => 'প্রস্থান',
        'allowed' => 'অনুমত অ্যাক্সেস',
        'denied' => 'অস্বীকৃত অ্যাক্সেস',
        'pending' => 'মুলতুবি অ্যাক্সেস',
        'alerts' => 'অ্যাক্সেস সতর্কতা'
    ],

    'parking_sessions' => [
        'title' => 'পার্কিং সেশন',
        'singular' => 'সেশন',
        'create' => 'সেশন তৈরি করুন',
        'edit' => 'সেশন সম্পাদনা করুন',
        'delete' => 'সেশন মুছে ফেলুন',
        'restore' => 'সেশন পুনরুদ্ধার করুন',
        'session_created_successfully' => 'পার্কিং সেশন সফলভাবে তৈরি হয়েছে',
        'session_updated_successfully' => 'পার্কিং সেশন সফলভাবে আপডেট হয়েছে',
        'session_deleted_successfully' => 'পার্কিং সেশন সফলভাবে মুছে ফেলা হয়েছে',
        'session_restored_successfully' => 'পার্কিং সেশন সফলভাবে পুনরুদ্ধার হয়েছে',
        'active_sessions' => 'সক্রিয় সেশন',
        'all_sessions' => 'সমস্ত সেশন',
        'new_session' => 'নতুন সেশন',
        'occupancy_monitor' => 'অধিগ্রহণ পর্যবেক্ষণ',
        'session_analytics' => 'সেশন বিশ্লেষণ',
        'session_marked_exited' => 'সেশন প্রস্থান হিসাবে চিহ্নিত করা হয়েছে',
        'session_extended' => 'সেশন সফলভাবে সম্প্রসারিত হয়েছে',
        'session_cancelled' => 'সেশন সফলভাবে বাতিল করা হয়েছে',
        'payment_collected' => 'পেমেন্ট সফলভাবে সংগ্রহ করা হয়েছে'
    ]
];

