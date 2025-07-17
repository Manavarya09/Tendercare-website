<?php
session_start();
$config = require __DIR__ . '/config.php';

// Disable error reporting in production
if (getenv('APP_ENV') !== 'development') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// CSRF token check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
    // Honeypot check
    if (!empty($_POST['website'])) {
        http_response_code(400);
        die('Spam detected.');
    }
}

// Generate CSRF token for form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$receiving_email_address = $config['receiving_email_address'];

if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
} else {
    die('Unable to load the "PHP Email Form" Library!');
}

$contact = new PHP_Email_Form;
$contact->ajax = true;
$contact->to = $receiving_email_address;

// Input validation and sanitization
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?: (filter_input(INPUT_POST, 'customerName', FILTER_SANITIZE_STRING) ?: '');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: (filter_input(INPUT_POST, 'contactDetails', FILTER_VALIDATE_EMAIL) ?: '');
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING) ?: 'Demo Form Submission';

$contact->from_name = $name;
$contact->from_email = $email;
$contact->subject = $subject;

foreach ($_POST as $key => $value) {
    if ($key === 'csrf_token' || $key === 'website') continue; // skip security fields
    if (is_array($value)) {
        $value = implode(', ', array_map('htmlspecialchars', $value));
    } else {
        $value = htmlspecialchars($value);
    }
    $contact->add_message($value, ucfirst(str_replace(['_', '-'], ' ', $key)));
}

$contact->smtp = array(
    'host' => $config['smtp_host'],
    'username' => $config['smtp_username'],
    'password' => $config['smtp_password'],
    'port' => $config['smtp_port'],
    'encryption' => $config['smtp_encryption']
);

echo $contact->send();
// To use: In your HTML form, add:
// <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
// <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
?>
