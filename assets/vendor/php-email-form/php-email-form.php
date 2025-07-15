<?php


if (!defined('PHP_EMAIL_FORM_VERSION')) {
  define('PHP_EMAIL_FORM_VERSION', '2.0');
}

// PHPMailer integration
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';
require_once __DIR__ . '/../phpmailer/src/Exception.php';

class PHP_Email_Form {
  public $to;
  public $from_name;
  public $from_email;
  public $subject;
  public $ajax = false;
  public $smtp = false;
  public $messages = array();
  public $attachments = array();
  public $honeypot = '';
  public $recaptcha_secret_key = '';

  public $invalid_to_email = 'Email to (receiving email address) is empty or invalid!';
  public $invalid_from_name = 'From Name is empty!';
  public $invalid_from_email = 'Email from: is empty or invalid!';
  public $invalid_subject = 'Subject is too short or empty!';
  public $short = 'is too short or empty!';
  public $ajax_error = 'Sorry, the request should be an Ajax POST';

  public function add_message($content, $label = '', $min_length = 0) {
    if ($min_length > 0 && strlen(trim($content)) < $min_length) {
      $this->messages[] = $label . ' ' . $this->short;
      return false;
    }
    $this->messages[] = ($label ? $label . ': ' : '') . $content;
    return true;
  }

  public function add_attachment($field, $max_size_mb = 10, $allowed_exts = array()) {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] != 0) return;
    $file = $_FILES[$field];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($allowed_exts && !in_array($ext, $allowed_exts)) return;
    if ($file['size'] > $max_size_mb * 1024 * 1024) return;
    $this->attachments[] = $file;
  }

  public function send() {
    if ($this->ajax && (!$this->is_ajax() || $_SERVER['REQUEST_METHOD'] != 'POST')) {
      return $this->ajax_error;
    }
    if (!$this->to || !filter_var($this->to, FILTER_VALIDATE_EMAIL)) {
      return $this->invalid_to_email;
    }
    if (!$this->from_name) {
      return $this->invalid_from_name;
    }
    if (!$this->from_email || !filter_var($this->from_email, FILTER_VALIDATE_EMAIL)) {
      return $this->invalid_from_email;
    }
    if (!$this->subject || strlen(trim($this->subject)) < 2) {
      return $this->invalid_subject;
    }
    if ($this->honeypot && !empty($this->honeypot)) {
      return 'Spam detected!';
    }
    if ($this->recaptcha_secret_key && isset($_POST['g-recaptcha-response'])) {
      $recaptcha = $this->verify_recaptcha($_POST['g-recaptcha-response']);
      if (!$recaptcha) return 'reCAPTCHA verification failed!';
    }
    $headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
    $headers .= "Reply-To: {$this->from_email}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $boundary = md5(time());
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $body .= implode("\n", $this->messages) . "\r\n";
    foreach ($this->attachments as $file) {
      $body .= "--$boundary\r\n";
      $body .= "Content-Type: " . $file['type'] . "; name=\"" . $file['name'] . "\"\r\n";
      $body .= "Content-Disposition: attachment; filename=\"" . $file['name'] . "\"\r\n";
      $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
      $body .= chunk_split(base64_encode(file_get_contents($file['tmp_name']))) . "\r\n";
    }
    $body .= "--$boundary--";
    if ($this->smtp && is_array($this->smtp)) {
      return $this->send_smtp($body, $headers);
    } else {
      if (mail($this->to, $this->subject, $body, $headers)) {
        return 'OK';
      } else {
        return 'Could not send email!';
      }
    }
  }

  private function is_ajax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
  }

  private function verify_recaptcha($response) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
      'secret' => $this->recaptcha_secret_key,
      'response' => $response
    );
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
      ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $result = json_decode($result, true);
    return isset($result['success']) && $result['success'] === true;
  }

  private function send_smtp($body, $headers) {
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'mail.tendercare.ae';
      $mail->SMTPAuth = true;
      $mail->Username = 'sales@tendercare.ae';
      $mail->Password = 'Tender302025';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;
      $mail->setFrom($this->from_email, $this->from_name);
      $mail->addAddress($this->to);
      $mail->addAddress('sales@tendercare.ae');
      $mail->Subject = $this->subject;
      $mail->isHTML(true);
      // Build HTML body
      $logoPath = __DIR__ . '/../img/tendercare-logo.png';
      $logoCid = 'tendercare-logo';
      if (file_exists($logoPath)) {
        $mail->addEmbeddedImage($logoPath, $logoCid);
        $logoHtml = '<img src="cid:' . $logoCid . '" alt="Tendercare Logo" width="160" style="display: block; margin: 0 auto 18px auto;">';
      } else {
        $logoHtml = '';
      }
      $body = $logoHtml;
      $body .= '<table cellpadding="10" cellspacing="0" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(255,102,0,0.08); border: 1.5px solid #ffb347; width: 100%; max-width: 600px; margin: 0 auto;">';
      foreach ($this->messages as $msg) {
        $parts = explode(': ', $msg, 2);
        $label = isset($parts[1]) ? $parts[0] : '';
        $value = isset($parts[1]) ? $parts[1] : $parts[0];
        $body .= '<tr>';
        $body .= '<td style="background: #ffb347; color: #fff; font-weight: bold; border-radius: 8px 0 0 8px;">' . htmlspecialchars($label) . '</td>';
        $body .= '<td style="background: #fff6f0; color: #333; border-radius: 0 8px 8px 0;">' . nl2br(htmlspecialchars($value)) . '</td>';
        $body .= '</tr>';
      }
      $body .= '</table>';
      $body .= '<p style="color: #888; font-size: 0.95em; margin-top: 18px; text-align:center;">This message was sent from the Contact form on your website.</p>';
      // Send to sales@tendercare.ae
      $mail->clearAllRecipients();
      $mail->setFrom($this->from_email, $this->from_name);
      $mail->addAddress('sales@tendercare.ae');
      if (filter_var($this->from_email, FILTER_VALIDATE_EMAIL)) {
        $mail->addReplyTo($this->from_email);
      }
      $mail->isHTML(true);
      $mail->Subject = $this->subject;
      $mail->Body = $body;
      // Attachments
      foreach ($this->attachments as $file) {
        $mail->addAttachment($file['tmp_name'], $file['name']);
      }
      $mail->send();
      // Send to user
      if (filter_var($this->from_email, FILTER_VALIDATE_EMAIL)) {
        $mailUser = new PHPMailer(true);
        $mailUser->isSMTP();
        $mailUser->Host = 'mail.tendercare.ae';
        $mailUser->SMTPAuth = true;
        $mailUser->Username = 'sales@tendercare.ae';
        $mailUser->Password = 'Tender302025';
        $mailUser->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailUser->Port = 465;
        $mailUser->setFrom('sales@tendercare.ae', $this->from_name);
        $mailUser->addAddress($this->from_email);
        $mailUser->addReplyTo('sales@tendercare.ae');
        if (file_exists($logoPath)) {
          $mailUser->addEmbeddedImage($logoPath, $logoCid);
        }
        $mailUser->isHTML(true);
        $mailUser->Subject = $this->subject;
        $mailUser->Body = $body;
        foreach ($this->attachments as $file) {
          $mailUser->addAttachment($file['tmp_name'], $file['name']);
        }
        $mailUser->send();
      }
      return 'OK';
    } catch (Exception $e) {
      return 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }
} 