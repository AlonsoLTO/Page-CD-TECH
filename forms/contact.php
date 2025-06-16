<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = $_POST['name']    ?? '';
    $email   = $_POST['email']   ?? '';
    $phone   = $_POST['phone']   ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo "Faltan datos en el formulario.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Correo no válido.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'mail.cdtech.pe';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ventas@cdtech.pe';
        $mail->Password   = 'Paolin_080308';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Opcional para depuración (quitar en producción)
        // $mail->SMTPDebug = 2;
        // $mail->Debugoutput = 'html';

        // Datos del correo
        $mail->setFrom('ventas@cdtech.pe', 'Formulario Web');
        $mail->addAddress('ventas@cdtech.pe');

        $mail->isHTML(true);
        $mail->Subject = "Asunto: $subject";
        $mail->Body    = "
            <strong>Nombre:</strong> $name<br>
            <strong>Email:</strong> $email<br>
            <strong>Telefono:</strong> $phone<br><br>
            <strong>Mensaje:</strong><br>$message
        ";

        $mail->send();
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Tu mensaje ha sido enviado. Gracias!']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos en el formulario.']);
    }

} else {
    http_response_code(405);
    echo "Método no permitido.";
}
