<?php
// rename fine to config.php

/**
 * Soubor se základní konfigurací
 */

// Database settings
define('DB_CONNECTION_STRING', 'mysql:host=127.0.0.1;dbname=xname;charset=utf8');
define('DB_USERNAME', 'xname');
define('DB_PASSWORD', '');

// Email settings
define('EMAIL_FROM', 'no-reply@barbershop.cz');
define('EMAIL_SUBJECT', 'Booking Confirmation');

// Base URL
define('BASE_URL', '/~xname/barbershop-reservation');
