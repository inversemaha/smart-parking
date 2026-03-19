<?php

return [
    'title' => 'গেট অপারেটর ড্যাশবোর্ড',
    'dashboard' => 'ড্যাশবোর্ড',
    'quick_scan' => 'দ্রুত স্ক্যান',
    
    // Dashboard stats
    'stats' => [
        'active_sessions' => 'সক্রিয় সেশন',
        'total_today' => 'আজকের প্রবেশ',
        'pending_payments' => 'অপেক্ষমাণ পেমেন্ট',
        'total_revenue' => 'আজকের রাজস্ব',
        'overstayed_vehicles' => 'অতিরিক্ত খরচী যানবাহন',
    ],

    // Quick operations
    'operations' => [
        'entry' => 'প্রবেশ',
        'exit' => 'প্রস্থান',
        'scan_qr' => 'কিউআর কোড স্ক্যান করুন',
        'manual_entry' => 'ম্যানুয়াল প্রবেশ',
        'manual_exit' => 'ম্যানুয়াল প্রস্থান',
        'search_vehicle' => 'যানবাহন অনুসন্ধান করুন',
    ],

    // Messages
    'messages' => [
        'entry_recorded_successfully' => 'প্রবেশ সফলভাবে রেকর্ড করা হয়েছে',
        'exit_recorded_successfully' => 'প্রস্থান সফলভাবে রেকর্ড করা হয়েছে',
        'session_already_active' => 'এই যানবাহনের ইতিমধ্যে একটি সক্রিয় পার্কিং সেশন রয়েছে',
        'no_active_session' => 'এই যানবাহনের জন্য কোন সক্রিয় সেশন পাওয়া যায়নি',
        'invalid_qr_code' => 'অবৈধ কিউআর কোড',
        'search_term_too_short' => 'সার্চ শব্দটি কমপক্ষে 2টি অক্ষর হওয়া আবশ্যক',
        'vehicle_info_retrieved' => 'যানবাহনের তথ্য পুনরুদ্ধার করা হয়েছে',
    ],

    // Alert types
    'alerts' => [
        'overstay' => 'অতিরিক্ত খরচ সতর্কতা',
        'long_park' => 'দীর্ঘ পার্কিং সতর্কতা',
        'unpaid' => 'অপেক্ষমাণ পেমেন্ট',
        'gate_maintenance' => 'গেট রক্ষণাবেক্ষণ',
        'equipment_issue' => 'সরঞ্জাম সমস্যা',
    ],

    // Fields
    'fields' => [
        'license_plate' => 'লাইসেন্স প্লেট',
        'zone' => 'পার্কিং জোন',
        'floor' => 'ফ্লোর',
        'entry_time' => 'প্রবেশের সময়',
        'exit_time' => 'প্রস্থানের সময়',
        'duration' => 'সময়কাল',
        'vehicle_category' => 'যানবাহনের ধরন',
        'entry_gate' => 'প্রবেশ গেট',
        'exit_gate' => 'প্রস্থান গেট',
        'total_charge' => 'মোট চার্জ',
        'charging_status' => 'পেমেন্ট স্থিতি',
        'session_status' => 'সেশন স্থিতি',
    ],

    // Session statuses
    'statuses' => [
        'active' => 'সক্রিয়',
        'completed' => 'সম্পন্ন',
        'cancelled' => 'বাতিল',
        'extended' => 'সম্প্রসারিত',
        'pending' => 'মুলতুবি',
        'collected' => 'সংগৃহীত',
    ],

    // Gate operations
    'gate' => [
        'status' => 'গেট স্থিতি',
        'operational' => 'কার্যকর',
        'maintenance' => 'রক্ষণাবেক্ষণ',
        'closed' => 'বন্ধ',
        'recent_activity' => 'সাম্প্রতিক কার্যকলাপ',
        'occupancy' => 'বর্তমান দখল',
    ],

    // Common actions
    'actions' => [
        'record_entry' => 'প্রবেশ রেকর্ড করুন',
        'record_exit' => 'প্রস্থান রেকর্ড করুন',
        'view_details' => 'বিবরণ দেখুন',
        'mark_extended' => 'সম্প্রসারিত হিসাবে চিহ্নিত করুন',
        'cancel_session' => 'সেশন বাতিল করুন',
        'collect_payment' => 'পেমেন্ট সংগ্রহ করুন',
    ],
];
