<?php
// Simple dotenv loader for PHP
function load_env(
    $path = __DIR__ . '/../.env'
) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        if (!getenv($name)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}
load_env();

// SMTP configuration
return [
    'smtp_host' => getenv('SMTP_HOST') ?: 'mail.tendercare.ae',
    'smtp_username' => getenv('SMTP_USERNAME') ?: 'sales@tendercare.ae',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: 'Tender302025',
    'smtp_port' => getenv('SMTP_PORT') ?: 465,
    'smtp_encryption' => getenv('SMTP_ENCRYPTION') ?: 'ssl',
    'receiving_email_address' => getenv('RECEIVING_EMAIL') ?: 'sales@tendercare.ae',
    // CSRF secret (should be random and unique per deployment)
    'csrf_secret' => getenv('CSRF_SECRET') ?: 'change_this_to_a_random_secret_key',
]; 