<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mpesa_integration');

// M-Pesa Daraja API configuration (Sandbox)
define('CONSUMER_KEY', 'cL7CnhahHN5VNwNhOZLovGoP0h7P6aLhdNn7wnnybGqkuzrY'); // Replace with your sandbox consumer key
define('CONSUMER_SECRET', 'ozADcPxTAS0XPUjOtfmtSfVNEphMZs5RyVgLmQtqMA6J9rDn3AoxB5u6hYmFAut3'); // Replace with your sandbox consumer secret
define('BUSINESS_SHORTCODE', '174379'); // Replace with your sandbox business shortcode
define('PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'); // Replace with your sandbox passkey (generated from Business Shortcode + Passkey + Timestamp)
define('CALLBACK_URL', 'https://8b54-2c0f-2d80-240-7f00-79d6-e38b-289f-24a.ngrok-free.app/callback_url.php'); // Replace with your callback URL (must be accessible by M-Pesa)