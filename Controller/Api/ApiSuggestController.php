<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ApiSuggestController
{
    private FormatService $formatService;

    public function __construct(FormatService $formatService)
    {
        $this->formatService = $formatService;
    }

    public function submit(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);
            return;
        }

        $data = $this->validateInput();

        if (!empty($data['error_message'])) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => $data['error_message']
            ]);
            return;
        }

        $mail = new PHPMailer(true);

        try {
            // ================= SMTP CONFIG =================
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            // 🔥 FIX: USE STARTTLS (more stable than 465)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];

            // ================= SENDER =================
            $mail->setFrom(
                $_ENV['MAIL_FROM_EMAIL'],
                $_ENV['MAIL_FROM_NAME']
            );

            // ================= RECIPIENT =================
            $mail->addAddress($_ENV['MAIL_FROM_EMAIL']);

            $mail->addReplyTo(
                $data['email'],
                $data['name']
            );

            // ================= CONTENT =================
            $mail->isHTML(true);
            $mail->Subject = "Library Suggestion: " . $data['title'];

            $mail->Body = "
                <h3>New Suggestion</h3>
                <p><b>Name:</b> {$data['name']}</p>
                <p><b>Email:</b> {$data['email']}</p>
                <p><b>Category:</b> {$data['category']}</p>
                <p><b>Title:</b> {$data['title']}</p>
                <p><b>Format:</b> {$data['format']}</p>
                <p><b>Genre:</b> {$data['genre']}</p>
                <p><b>Year:</b> {$data['year']}</p>
                <p><b>Details:</b><br>{$data['details']}</p>
            ";

            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'Suggestion sent successfully'
            ]);

        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => $mail->ErrorInfo
            ]);
        }
    }

    private function validateInput(): array
    {
        $data = [
            'name' => trim(filter_input(INPUT_POST, 'name')),
            'email' => trim(filter_input(INPUT_POST, 'email')),
            'category' => trim(filter_input(INPUT_POST, 'category')),
            'title' => trim(filter_input(INPUT_POST, 'title')),
            'format' => trim(filter_input(INPUT_POST, 'format')),
            'genre' => trim(filter_input(INPUT_POST, 'genre')),
            'year' => trim(filter_input(INPUT_POST, 'year')),
            'details' => trim(filter_input(INPUT_POST, 'details')),
            'error_message' => null
        ];

        if (empty($data['name']) || empty($data['email']) || empty($data['category']) || empty($data['title'])) {
            $data['error_message'] = "Required fields missing";
            return $data;
        }

        if (!PHPMailer::validateAddress($data['email'])) {
            $data['error_message'] = "Invalid email";
            return $data;
        }

        return $data;
    }
}