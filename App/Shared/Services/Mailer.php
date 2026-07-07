<?php

declare(strict_types=1);

namespace App\Shared\Services;

class Mailer
{
    public static function send(string $to, string $subject, string $body): bool
    {
        $config = require BASE_PATH . '/App/config/mail.php';

        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->Debugoutput = 'error_log';

                $smtp = $config['smtp'] ?? [];
                if (!empty($smtp['host'])) {
                    $mail->isSMTP();
                    $mail->Host = $smtp['host'];
                    $mail->SMTPAuth = (bool)($smtp['auth'] ?? false);
                    $mail->Username = (string)($smtp['username'] ?? '');
                    $mail->Password = (string)($smtp['password'] ?? '');
                    $mail->SMTPSecure = (string)($smtp['secure'] ?? 'tls');
                    $mail->Port = (int)($smtp['port'] ?? 587);
                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true,
                        ],
                    ];
                } else {
                    error_log('Mailer warning: SMTP host is empty, using default mailer transport.');
                }

                $mail->setFrom($config['from_address'] ?? 'no-reply@localhost', $config['from_name'] ?? 'No Reply');
                $mail->addAddress($to);
                $mail->isHTML(false);
                $mail->Subject = $subject;
                $mail->Body = $body;

                return (bool)$mail->send();
            } catch (\Throwable $e) {
                error_log('Mailer PHPMailer error: ' . $e->getMessage());
                return false;
            }
        }

        error_log('Mailer warning: PHPMailer class not found, falling back to mail().');

        $headers = 'From: ' . ($config['from_address'] ?? 'no-reply@localhost') . "\r\n"
            . 'Reply-To: ' . ($config['from_address'] ?? '') . "\r\n"
            . 'X-Mailer: PHP/' . phpversion();
        $result = (bool)mail($to, $subject, $body, $headers);
        if (!$result) {
            error_log('Mailer fallback mail() failed for: ' . $to . ' subject: ' . $subject);
        }

        return $result;
    }
}
