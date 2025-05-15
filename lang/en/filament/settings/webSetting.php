<?php
return [
    'navigation_group' => 'Manage Web Settings',
    'web' => [
        'NavigationLabel' => 'Web Settings',
        'ModelLabel' => 'Web Settings',
        'PluralModelLabel' => 'Web Settings',
        'section' => [
            'setting' => 'General Settings',
            'phones' => 'Contact Numbers',
            'social' => 'Social Media',
        ],

        'columns' => [
            'name' => 'Name',
            'closed_mass' => 'Closure Message',
            'meta_des' => 'Meta Description',
            'whatsapp_des' => 'Default WhatsApp Message',

            'web_status' => 'Website Status',
            'web_status_date' => 'Launch Date',
            'switch_lang' => 'Allow Language Switching',
            'lang' => 'Default Language',
            'users_login' => 'User Login',
            'users_register' => 'New User Registration',
            'users_forget_password' => 'Password Recovery',

            'phone_num' => 'Phone Number',
            'phone_call' => 'Call Number',
            'whatsapp_num' => 'WhatsApp Number',
            'whatsapp_send' => 'WhatsApp Sender Number',
            'web_url' => 'Website URL',
            'email' => 'Email Address',

            'facebook' => 'Facebook',
            'youtube' => 'YouTube',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
            'google_api' => 'Google API Key',

            'telegram_send' => 'Telegram Sender',
            'telegram_phone' => 'Telegram Phone',
            'telegram_group' => 'Telegram Group',

            'schema_type' => 'Schema Type',
            'schema_lat' => 'Latitude',
            'schema_long' => 'Longitude',
            'schema_postal_code' => 'Postal Code',
            'schema_country' => 'Country',
        ],
        'req' => [
            'required_if' => 'Please enter the launch date',
            'after_or_equal' => 'The launch date must be today or later.',
        ],

    ],
];
