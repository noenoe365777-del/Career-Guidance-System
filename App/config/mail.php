<?php

declare(strict_types=1);

return [
    // Sender
    'from_address' => 'thaetone712021@gmail.com',
    'from_name' => 'Career Guidance',

    // SMTP configuration for Gmail
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'auth' => true,
        'username' => 'thaetone712021@gmail.com',
        'password' => 'wqeryeaktrwhlqea', // Use your 16-character Gmail App Password    
        'secure' => 'tls',
        'port' => 587,
    ],
];
