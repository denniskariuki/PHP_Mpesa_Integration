<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mpesa_integration');

// M-Pesa Daraja API configuration (Sandbox)
define('CONSUMER_KEY', ''); // Replace with your sandbox consumer key
define('CONSUMER_SECRET', ''); // Replace with your sandbox consumer secret
define('BUSINESS_SHORTCODE', '174379'); // Replace with your sandbox business shortcode
define('PASSKEY', ''); // Replace with your sandbox passkey (generated from Business Shortcode + Passkey + Timestamp)
define('CALLBACK_URL', '/callback_url.php'); // Replace with your callback URL (must be accessible by M-Pesa)
