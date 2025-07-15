<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/assets/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/assets/vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/assets/vendor/phpmailer/src/Exception.php';

// Collect form data
function post($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$inquiry_types = [];
$possible_inquiries = [
    'newPurchase' => 'New Purchase',
    'demoRequest' => 'Demo Request',
    'softwareUpgrade' => 'Software Upgrade',
    'integrationInquiry' => 'Integration Inquiry',
    'pricingRequest' => 'Pricing Request',
    'customizationRequest' => 'Customization Request',
    'otherInquiry' => 'Other'
];
foreach ($possible_inquiries as $key => $label) {
    if (isset($_POST[$key])) {
        $inquiry_types[] = $label;
    }
}
$inquiry_type_str = implode(', ', $inquiry_types);

$fields = [
    'Customer Name' => post('customerName'),
    'Organization Name' => post('orgName'),
    'Email' => post('contactDetails'),
    'Phone Country Code' => isset($_POST['countryCode']) ? $_POST['countryCode'] : '+971',
    'Phone Number' => post('preferredContact'),
    'Number of Users' => post('numUsers'),
    'Number of Doctors' => post('numDoctors'),
    'Specialties' => post('specialties'),
    'Number of Branches' => post('numBranches'),
    'Inquiry Type(s)' => $inquiry_type_str,
    'Other Inquiry Text' => post('otherInquiryText'),
    'Existing Software' => post('existingSoftware'),
    'Server Preference' => post('serverPref'),
    'Preferred Demo Date' => post('demoDate'),
    'Budget' => post('budget'),
];

$html = '<html><body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 24px;">';
$html .= '<h2 style="color: #ff6600;">New Book a Demo Submission</h2>';
$html .= '<table cellpadding="10" cellspacing="0" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(255,102,0,0.08); border: 1.5px solid #ffb347;">';
foreach ($fields as $label => $value) {
    $html .= '<tr>';
    $html .= '<td style="background: #ffb347; color: #fff; font-weight: bold; border-radius: 8px 0 0 8px;">' . htmlspecialchars($label) . '</td>';
    $html .= '<td style="background: #fff6f0; color: #333; border-radius: 0 8px 8px 0;">' . nl2br(htmlspecialchars($value)) . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
$html .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px;">This message was sent from the Book a Demo form on your website.</p>';
$html .= '</body></html>';

$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.tendercare.ae';
    $mail->SMTPAuth = true;
    $mail->Username = 'sales@tendercare.ae';
    $mail->Password = 'Tender302025';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Recipients
    $mail->setFrom('sales@tendercare.ae', 'Tendercare Website');
    $mail->addAddress($fields['Email']); // Send to the user
    $mail->addAddress('sales@tendercare.ae'); // Send a copy to sales
    if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
      $mail->addReplyTo($fields['Email']);
    }
    // Embed the logo
    $logoPath = __DIR__ . '/assets/img/tendercare-logo.png';
    $logoCid = 'tendercare-logo';
    $logoHtml = '';
    if (file_exists($logoPath)) {
      $logoHtml = '<img src="cid:' . $logoCid . '" alt="Tendercare Logo" width="160" style="display: block; margin: 0 auto 18px auto;">';
    }
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Thank You for Your Response';
    // Build HTML body (same as contact form)
    $body = $logoHtml;
    $body .= '<table cellpadding="10" cellspacing="0" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(255,102,0,0.08); border: 1.5px solid #ffb347; width: 100%; max-width: 600px; margin: 0 auto;">';
    foreach ($fields as $label => $value) {
      $body .= '<tr>';
      $body .= '<td style="background: #ffb347; color: #fff; font-weight: bold; border-radius: 8px 0 0 8px;">' . htmlspecialchars($label) . '</td>';
      $body .= '<td style="background: #fff6f0; color: #333; border-radius: 0 8px 8px 0;">' . nl2br(htmlspecialchars($value)) . '</td>';
      $body .= '</tr>';
    }
    $body .= '</table>';
    $body .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px; text-align:center;">This message was sent from the Book a Demo form on your website.</p>';
    $mail->Body = $body;
    $mail->send();

    // Send to sales@tendercare.ae
    $mailSales = new PHPMailer(true);
    try {
      $mailSales->isSMTP();
      $mailSales->Host = 'mail.tendercare.ae';
      $mailSales->SMTPAuth = true;
      $mailSales->Username = 'sales@tendercare.ae';
      $mailSales->Password = 'Tender302025';
      $mailSales->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mailSales->Port = 465;
      $mailSales->setFrom('sales@tendercare.ae', 'Tendercare Website');
      $mailSales->addAddress('sales@tendercare.ae');
      if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
        $mailSales->addReplyTo($fields['Email']);
      }
      if (file_exists($logoPath)) {
        $mailSales->addEmbeddedImage($logoPath, $logoCid);
      }
      $mailSales->isHTML(true);
      $mailSales->Subject = 'Thank You for Your Response';
      $mailSales->Body = $body;
      $mailSales->send();
    } catch (Exception $e) {
      // Optionally log error
    }

    // Send to user
    if (filter_var($fields['Email'], FILTER_VALIDATE_EMAIL)) {
      $mailUser = new PHPMailer(true);
      try {
        $mailUser->isSMTP();
        $mailUser->Host = 'mail.tendercare.ae';
        $mailUser->SMTPAuth = true;
        $mailUser->Username = 'sales@tendercare.ae';
        $mailUser->Password = 'Tender302025';
        $mailUser->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailUser->Port = 465;
        $mailUser->setFrom('sales@tendercare.ae', 'Tendercare Website');
        $mailUser->addAddress($fields['Email']);
        $mailUser->addReplyTo('sales@tendercare.ae');
        if (file_exists($logoPath)) {
          $mailUser->addEmbeddedImage($logoPath, $logoCid);
        }
        $mailUser->isHTML(true);
        $mailUser->Subject = 'Thank You for Your Response';
        $mailUser->Body = $body;
        $mailUser->send();
      } catch (Exception $e) {
        // Optionally log error
      }
    }
    echo 'OK';
} catch (Exception $e) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} 