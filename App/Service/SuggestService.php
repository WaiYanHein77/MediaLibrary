<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;

class SuggestService
{
    public function send(array $data): Result
    {
        try {

            $mail = new PHPMailer(true);

            // SMTP CONFIG
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];

            // EMAIL SETUP
            $mail->setFrom($_ENV['MAIL_FROM_EMAIL'], $_ENV['MAIL_FROM_NAME']);
            $mail->addReplyTo($data['email'], $data['name']);
            $mail->addAddress($_ENV['MAIL_FROM_EMAIL']);

            $mail->Subject = 'Library Suggestion from: ' . $data['name'];
            $mail->Body    = $this->buildBody($data);

            $mail->send();

            return new Result(true);

        } catch (\Exception $e) {
            return new Result(false, [
                'email' => 'Mailer Error: ' . $e->getMessage()
            ]);
        }
    }

    private function buildBody(array $data): string
    {
        return
            "Name: {$data['name']}\n" .
            "Email: {$data['email']}\n\n" .
            "Category: {$data['category']}\n" .
            "Title: {$data['title']}\n" .
            "Format: {$data['format']}\n" .
            "Genre: {$data['genre']}\n" .
            "Year: {$data['year']}\n" .
            "Details:\n{$data['details']}\n";
    }
}