<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function is_ajax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
           || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
}
function respond($success, $message) {
    if (is_ajax()) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['success' => $success, 'message' => $message]);
    } else {
        if ($success) {
            echo "<p style='color:green;'>$message</p>";
        } else {
            echo "<p style='color:red;'>$message</p>";
        }
    }
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et sécuriser les données
    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Validation basique
    if (!empty($nom) && !empty($email) && !empty($message)) {
        // Validation stricte de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            respond(false, 'Adresse e-mail invalide.');
        }
        // Limiter la taille du message pour éviter les abus
        if (strlen($message) > 5000) {
            respond(false, 'Le message est trop long.');
        }
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            // Utiliser des variables d'environnement pour ne pas laisser les identifiants en clair dans le dépôt
            $smtpUser = getenv('SMTP_USER');
            $smtpPass = getenv('SMTP_PASS');
            if (!$smtpUser || !$smtpPass) {
                respond(false, 'Configuration SMTP manquante. Définissez SMTP_USER et SMTP_PASS sur le serveur.');
            }
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Encodage
            $mail->CharSet = 'UTF-8';

            // Destinataire
            $mail->setFrom('musjosue809@gmail.com', 'Formulaire Contact'); // L'expéditeur doit être authentifié
            $mail->addAddress('musjosue809@gmail.com'); // Où envoyer le message (vous-même)
            $mail->addReplyTo($email, $nom); // Pour répondre directement à l'utilisateur

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Subject = 'Nouveau message de ' . $nom . ' depuis le site web';
            
            $bodyContent = "<h3>Nouveau message de contact</h3>";
            $bodyContent .= "<p><strong>Nom :</strong> " . $nom . "</p>";
            $bodyContent .= "<p><strong>Email :</strong> " . $email . "</p>";
            $bodyContent .= "<p><strong>Message :</strong></p>";
            $bodyContent .= "<p>" . nl2br($message) . "</p>";
            
            $mail->Body = $bodyContent;
            $mail->AltBody = "Nom: $nom\nEmail: $email\nMessage:\n$message";

            $mail->send();
            respond(true, 'Message envoyé avec succès. Merci de nous avoir contactés !');
        } catch (Exception $e) {
            respond(false, "Le message n'a pas pu être envoyé. Erreur Mailer : {$mail->ErrorInfo}");
        }
    } else {
        respond(false, 'Veuillez remplir tous les champs.');
    }
} else {
    respond(false, 'Méthode non autorisée.');
}
?>