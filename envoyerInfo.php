<?php
require_once 'vendor/phpmailer/phpmailer/src/Exception.php';
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $sujet = htmlspecialchars($_POST['sujet']);
    $message = htmlspecialchars($_POST['message']);

    if ($email === false) {
        echo "Adresse e-mail invalide.";
        exit;
    }

    $reponse = envoyerMatriculeParEmail($nom, $email, $sujet, $message);

    // Envoyer l'email
    if ($reponse === true) {
        echo "Votre message a été envoyé avec succès!";
    } else {
        echo "Une erreur s'est produite lors de l'envoi de l'email.";
    }
}

function envoyerMatriculeParEmail($nom, $email, $sujet, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'mininoulilou@gmail.com'; // Adresse e-mail de l'expéditeur
        $mail->Password = 'hpqz zoke gzcq bkfb';    // Mot de passe de l'expéditeur
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
        $mail->Port = 465; // Port SSL

        // Expéditeur et destinataire
        $mail->setFrom('mininoulilou@gmail.com', 'PortFolio');
        $mail->addAddress('amerykouomou@gmail.com', 'AMERY KOUOMOU');

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = "Nouveau message de contact de $nom";
        $mail->Body = 'Nom: ' . $nom . '\n'
            . 'Email : ' . $email . '\n'
            . 'Sujet : ' . $sujet . '\n'
            . 'Message : ' . $message . '\n';

        // Envoi de l'e-mail
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Pour faciliter le débogage, vous pouvez activer la ligne suivante pour afficher l'erreur
        echo "Erreur : {$mail->ErrorInfo}";
        return false;
    }
}
?>
