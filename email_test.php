<?php

declare(strict_types=1);

// This script tests the project SMTP / PHPMailer setup.
// Usage from CLI:
//   php email_test.php recipient@example.com
// Or from browser:
//   http://localhost/career-guidance-system/email_test.php?to=recipient@example.com

$root = __DIR__;
$autoload = $root . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    echo "ERROR: vendor/autoload.php not found. Run composer install or composer require phpmailer/phpmailer first.";
    exit(1);
}

// For Gmail SMTP, ensure App/config/mail.php uses:
//   host: smtp.gmail.com
//   auth: true
//   username: your.email@gmail.com
//   password: your 16-character Gmail App Password
//   secure: tls
//   port: 587

require $autoload;

$configFile = $root . '/App/config/mail.php';
if (!file_exists($configFile)) {
    echo "ERROR: App/config/mail.php not found.\n";
    exit(1);
}

$config = require $configFile;

$to = '';
if (PHP_SAPI === 'cli') {
    $to = $argv[1] ?? '';
} else {
    $to = $_GET['to'] ?? '';
}

if (empty($to)) {
    echo "Usage:\n";
    echo "  php email_test.php recipient@example.com\n";
    echo "Or open in browser:\n";
    echo "  http://localhost/career-guidance-system/email_test.php?to=recipient@example.com\n";
    exit(1);
}

if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    echo "ERROR: PHPMailer is not installed. Run composer require phpmailer/phpmailer\n";
    exit(1);
}

$smtp = $config['smtp'] ?? [];
$host = trim((string)($smtp['host'] ?? ''));

if (empty($host)) {
    echo "WARNING: SMTP host is empty in App/config/mail.php.\n";
    echo "PHPMailer will try to send via the default transport, which may fail on XAMPP.\n\n";
}

$mail = new PHPMailer\\PHPMailer\\PHPMailer(true);

try {
    if (!empty($host)) {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = (bool)($smtp['auth'] ?? false);
        $mail->Username = (string)($smtp['username'] ?? '');
        $mail->Password = (string)($smtp['password'] ?? '');
        $mail->SMTPSecure = (string)($smtp['secure'] ?? 'tls');
        $mail->Port = (int)($smtp['port'] ?? 587);
    }

    $mail->setFrom($config['from_address'] ?? 'no-reply@localhost', $config['from_name'] ?? 'No Reply');
    $mail->addAddress($to);
    $mail->Subject = 'SMTP / PHPMailer Test Message';
    $mail->Body = "This is a test email from your Career Guidance System project.\n\n" .
                  "If you received this message, your SMTP and PHPMailer setup is working correctly.";
    $mail->AltBody = "This is a test email from your Career Guidance System project.";

    $mail->send();
    echo "SUCCESS: Test email sent to {$to}.\n";
    echo "If it does not appear in your inbox, check spam/junk and your SMTP provider logs.\n";
    echo "SMTP host used: " . ($host ?: 'default PHP mail() transport') . "\n";
} catch (Exception $e) {
    echo "ERROR: Failed to send email.\n";
    echo "PHPMailer Exception: " . $e->getMessage() . "\n";
    echo "SMTP host: " . ($host ?: 'default PHP mail() transport') . "\n";
    if (!empty($smtp['host'])) {
        echo "SMTP auth: " . (($smtp['auth'] ?? false) ? 'true' : 'false') . "\n";
        echo "SMTP username: " . (($smtp['username'] ?? '') ?: '(empty)') . "\n";
    }
    exit(1);
}
