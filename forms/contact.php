<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  $receiving_email_address = 'manavarya0178@gmail.com';

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = isset($_POST['name']) ? $_POST['name'] : (isset($_POST['customerName']) ? $_POST['customerName'] : '');
  $contact->from_email = isset($_POST['email']) ? $_POST['email'] : (isset($_POST['contactDetails']) ? $_POST['contactDetails'] : '');
  $contact->subject = isset($_POST['subject']) ? $_POST['subject'] : 'Demo Form Submission';

  // Add all POST fields to the email in a consistent format
  foreach ($_POST as $key => $value) {
    if (is_array($value)) {
      $value = implode(', ', $value);
    }
    $contact->add_message($value, ucfirst(str_replace(['_', '-'], ' ', $key)));
  }

  $contact->smtp = array(
    'host' => 'smtp.gmail.com',
    'username' => 'sales@tendercare.com',
    'password' => 'Tender302025',
    'port' => '587',
    'encryption' => 'tls'
  );

  echo $contact->send();
?>
