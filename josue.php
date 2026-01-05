<?php
// Initialisation des messages
$successMessage = "";
$errorMessage = "";

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $messageForm = htmlspecialchars($_POST['message']);

    // Vérifier le fichier
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
        $allowed = ['pdf', 'doc', 'docx'];
        $filename = $_FILES['fichier']['name'];
        $fileTmp = $_FILES['fichier']['tmp_name'];
        $fileExt = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($fileExt), $allowed)) {
            $destination = "uploads/" . uniqid() . "_" . $filename;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            if (move_uploaded_file($fileTmp, $destination)) {
                // Préparer l'e-mail
                $to = "musjosue809@gmail.com";
                $subject = "Nouveau formulaire de contact de $nom";
                
                // Contenu du mail
                $body = "Nom : $nom\n";
                $body .= "Email : $email\n";
                $body .= "Message :\n$messageForm\n";

                // Ajouter fichier en pièce jointe avec PHPMailer ou mail() simple
                // Pour simplifier ici, on envoie juste le lien vers le fichier
                $body .= "CV : " . $destination . "\n";

                $headers = "From: $email";

                if (mail($to, $subject, $body, $headers)) {
                    $successMessage = "Merci $nom ! Votre formulaire a été envoyé avec succès.";
                } else {
                    $errorMessage = "Erreur lors de l'envoi de votre formulaire.";
                }
            } else {
                $errorMessage = "Erreur lors du téléchargement de votre fichier.";
            }
        } else {
            $errorMessage = "Format de fichier non autorisé. Seuls PDF et DOC/DOCX sont acceptés.";
        }
    } else {
        $errorMessage = "Veuillez joindre un fichier.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contactez-nous</title>
  <link rel="stylesheet" href="contact.css">
  <link rel="icon" href="dirnex.JPG" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align:center; font-weight:bold;}
    .success { background-color: #2ecc71; color: #fff; }
    .error { background-color: #e74c3c; color: #fff; }
  </style>
</head>
<body>

<section class="contact-section">
  <div class="contact-container">
    <div class="contact-info">
      <h2>BIENVENUE CHEZ <span>D</span>IRNEX</h2>
      <h2><span>S</span>ervices d'inscription</h2>
      <p>Veuillez entrer vos informations sans oublier le CV</p>
      <div class="social-links">
        <a href="#"><i class="fa-brands fa-facebook"></i></a>
        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
      </div>
    </div>

    <div class="contact-form">
      <h2>Remplissez le formulaire</h2>

      <!-- Messages dynamiques -->
      <?php if($successMessage) echo "<div class='message success'>$successMessage</div>"; ?>
      <?php if($errorMessage) echo "<div class='message error'>$errorMessage</div>"; ?>

      <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="nom" placeholder="Votre nom complet" required>
        <input type="email" name="email" placeholder="Votre adresse e-mail" required>
        <input type="file" id="fichier" name="fichier" placeholder="Entrez votre CV" required>
        <textarea name="message" placeholder="Veuillez écrire votre lettre de motivation" required></textarea>
        <button type="submit">Envoyer</button>
      </form>
    </div>
  </div>
</section>

</body>
</html>
