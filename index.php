<?php
require_once 'vendor/phpmailer/phpmailer/src/Exception.php';
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Définir des constantes pour les identifiants SMTP
define('SMTP_USERNAME', 'mininoulilou@gmail.com'); // Remplacez par votre email
define('SMTP_PASSWORD', 'hpqz zoke gzcq bkfb'); // Remplacez par votre mot de passe d'application
define('SMTP_RECIPIENT', 'amerykouomou@gmail.com');

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et nettoyer les données du formulaire
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $sujet = filter_input(INPUT_POST, 'sujet', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Vérifier les données
    if (!$email) {
        $response = ['status' => 'error', 'message' => 'Adresse e-mail invalide.'];
        echo json_encode($response);
        exit;
    }

    if (empty($nom) || empty($sujet) || empty($message)) {
        $response = ['status' => 'error', 'message' => 'Tous les champs sont obligatoires.'];
        echo json_encode($response);
        exit;
    }

    // Envoyer l'email
    $resultat = envoyerEmail($nom, $email, $sujet, $message);

    if ($resultat === true) {
        $response = ['status' => 'success', 'message' => 'Votre message a été envoyé avec succès!'];
    } else {
        $response = ['status' => 'error', 'message' => 'Une erreur s\'est produite: ' . $resultat];
    }

    // Retourner une réponse JSON si c'est une requête AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Sinon, redirection avec message dans la session
    session_start();
    $_SESSION['message'] = htmlspecialchars($response['message'], ENT_QUOTES, 'UTF-8');
    $_SESSION['message_type'] = $response['status'];
    header("Location: " . $_SERVER['PHP_SELF'] . "#contact");
    exit;
}

/**
 * Fonction pour envoyer un email via PHPMailer
 */
function envoyerEmail($nom, $email, $sujet, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        // Expéditeur et destinataire
        $mail->setFrom(SMTP_USERNAME, 'Portfolio Contact');
        $mail->addAddress(SMTP_RECIPIENT, 'AMERY KOUOMOU');
        $mail->addReplyTo($email, $nom);

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = "Nouveau message de contact: $sujet";
        $mail->Body = "
            <h2>Nouveau message de contact</h2>
            <p><strong>Nom:</strong> " . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Sujet:</strong> " . htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "</p>
        ";
        $mail->AltBody = "Nom: $nom\nEmail: $email\nSujet: $sujet\nMessage: $message";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Erreur Mailer: {$mail->ErrorInfo}";
    }
}

// Récupérer les messages flash
session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
unset($_SESSION['message'], $_SESSION['message_type']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio d'Amery Kouomou, étudiante en 3e année bachelor informatique spécialisée en Data Engineering et Data Analysis.">
    <meta name="keywords" content="Data Engineering, Data Analysis, SQL, Python, Power BI, MySQL, MongoDB, Firebase, Node.js, Express.js, API REST, JavaScript, GitHub, Docker, Jira">
    <meta name="author" content="Amery Kouomou">
    <title>Amery Kouomou | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        /* CSS inchangé (trop long pour le copier ici) */
        /* ... Votre CSS existant ... */
          /* CSS inchangé (trop long pour le copier ici) */
  :root {
    --primary-color: #2a2a72;
    --secondary-color: #4a4ae4;
    --accent-color: #5d4fef;
    --text-color: #333;
    --light-text: #fff;
    --background-color: #f9f9f9;
    --card-bg: #ffffff;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.8;
    color: var(--text-color);
    background-color: var(--background-color);
    overflow-x: hidden;
    scroll-behavior: smooth;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Navigation */
nav {
    background: var(--primary-color);
    padding: 1rem 0;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

nav.scrolled {
    padding: 0.7rem 0;
    background: rgba(42, 42, 114, 0.95);
    backdrop-filter: blur(10px);
}

nav .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--light-text);
    text-decoration: none;
}

nav ul {
    list-style-type: none;
    display: flex;
    gap: 2rem;
}

nav ul li a {
    color: var(--light-text);
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    transition: var(--transition);
    position: relative;
    padding: 0.5rem 0;
}

nav ul li a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--light-text);
    transition: var(--transition);
}

nav ul li a:hover::after,
nav ul li a.active::after {
    width: 100%;
}

.hamburger {
    display: none;
    cursor: pointer;
    color: var(--light-text);
    font-size: 1.5rem;
}

/* Header */
header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--light-text);
    padding: 10rem 0 5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.particle {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
}

.header-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
}

header h1 {
    margin: 0;
    font-size: 3.5rem;
    margin-bottom: 1rem;
    font-weight: 800;
}

header p {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.btn {
    display: inline-block;
    padding: 0.8rem 2rem;
    background-color: var(--accent-color);
    color: var(--light-text);
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    transition: var(--transition);
    border: 2px solid transparent;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    background-color: transparent;
    border-color: var(--light-text);
}

.scroll-down {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    color: var(--light-text);
    font-size: 2rem;
    animation: bounce 2s infinite;
    cursor: pointer;
}

@keyframes bounce {

    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0) translateX(-50%);
    }

    40% {
        transform: translateY(-20px) translateX(-50%);
    }

    60% {
        transform: translateY(-10px) translateX(-50%);
    }
}

/* Sections communes */
section {
    padding: 5rem 0;
    position: relative;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title h2 {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
}

.section-title h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 70px;
    height: 3px;
    background-color: var(--accent-color);
}

.section-title p {
    color: #777;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

/* Compétences */
.competences-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.competence-card {
    background: var(--card-bg);
    border-radius: 10px;
    box-shadow: var(--shadow);
    padding: 2rem;
    text-align: center;
    transition: var(--transition);
    position: relative;
    opacity: 0;
    transform: translateY(50px);
}

.competence-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.competence-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: var(--accent-color);
    z-index: -1;
    transition: var(--transition);
}

.competence-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.competence-card:hover::before {
    height: 100%;
    opacity: 0.1;
}

.competence-card i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

.competence-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.competence-card p {
    color: #666;
    font-size: 1rem;
}

.skill-level {
    height: 8px;
    width: 80%;
    background-color: #e9ecef;
    border-radius: 10px;
    margin: 1rem auto;
    position: relative;
    overflow: hidden;
}

.skill-progress {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background-color: var(--accent-color);
    width: 0;
    transition: width 1.5s ease-in-out;
    border-radius: 10px;
}

/* Projets */
#projets {
    background-color: #f5f7fa;
}

.projets-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 2rem;
}

.projet-card {
    background: var(--card-bg);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    opacity: 0;
    transform: translateY(50px);
}

.projet-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.projet-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.projet-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    position: relative;
}

.projet-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.projet-card:hover .projet-image img {
    transform: scale(1.1);
}

.projet-content {
    padding: 1.5rem;
}

.projet-content h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.projet-content p {
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}

.projet-links {
    display: flex;
    gap: 1rem;
}

.projet-links a {
    color: var(--accent-color);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    transition: var(--transition);
}

.projet-links a:hover {
    color: var(--primary-color);
}

.projet-links a i {
    margin-right: 5px;
}

/* Formations */
.timeline {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline::after {
    content: '';
    position: absolute;
    width: 4px;
    background-color: var(--secondary-color);
    top: 0;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}

.timeline-item {
    padding: 10px 40px;
    position: relative;
    width: 50%;
    opacity: 0;
    transform: translateX(-50px);
    transition: var(--transition);
}

.timeline-item.visible {
    opacity: 1;
    transform: translateX(0);
}

.timeline-item:nth-child(odd) {
    left: 0;
}

.timeline-item:nth-child(even) {
    left: 50%;
    transform: translateX(50px);
}

.timeline-item:nth-child(even).visible {
    transform: translateX(0);
}

.timeline-content {
    padding: 1.5rem;
    background-color: var(--card-bg);
    border-radius: 10px;
    box-shadow: var(--shadow);
    position: relative;
}

.timeline-content::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: var(--card-bg);
    transform: rotate(45deg);
    top: 20px;
}

.timeline-item:nth-child(odd) .timeline-content::after {
    right: -10px;
}

.timeline-item:nth-child(even) .timeline-content::after {
    left: -10px;
}

.timeline-date {
    color: var(--accent-color);
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.timeline-content h3 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.timeline-content p {
    color: #666;
}

.timeline-dot {
    position: absolute;
    width: 24px;
    height: 24px;
    background-color: var(--accent-color);
    border-radius: 50%;
    top: 20px;
    z-index: 1;
    border: 4px solid var(--primary-color);
}

.timeline-item:nth-child(odd) .timeline-dot {
    right: -12px;
}

.timeline-item:nth-child(even) .timeline-dot {
    left: -12px;
}

/* Contact */
.contact-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.contact-info {
    background-color: var(--primary-color);
    color: var(--light-text);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
}

.contact-info h3 {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.contact-info p {
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.contact-details {
    margin-top: 2rem;
}

.contact-details div {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.contact-details i {
    font-size: 1.3rem;
    margin-right: 1rem;
    color: var(--accent-color);
}

.contact-form {
    background-color: var(--card-bg);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
}

.contact-form h3 {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-control {
    width: 100%;
    padding: 1rem;
    border: none;
    background-color: #f5f7fa;
    border-radius: 5px;
    font-size: 1rem;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(93, 79, 239, 0.2);
}

textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

button[type="submit"] {
    padding: 1rem 2rem;
    background-color: var(--accent-color);
    color: var(--light-text);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: var(--transition);
}

button[type="submit"]:hover {
    background-color: var(--primary-color);
    transform: translateY(-3px);
}

/* Footer */
footer {
    background-color: var(--primary-color);
    color: var(--light-text);
    padding: 3rem 0 1.5rem;
    text-align: center;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-bottom: 2rem;
    text-align: left;
}

.footer-section h3 {
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    position: relative;
    display: inline-block;
}

.footer-section h3::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 50px;
    height: 2px;
    background-color: var(--accent-color);
}

.footer-links {
    list-style: none;
}

.footer-links li {
    margin-bottom: 0.8rem;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: var(--transition);
}

.footer-links a:hover {
    color: var(--accent-color);
    padding-left: 5px;
}

.social-links {
    display: flex;
    gap: 15px;
    margin-top: 1rem;
}

.social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.1);
    color: var(--light-text);
    transition: var(--transition);
}

.social-links a:hover {
    background-color: var(--accent-color);
    transform: translateY(-5px);
}

.copyright {
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive */
@media (max-width: 992px) {
    .timeline::after {
        left: 31px;
    }

    .timeline-item {
        width: 100%;
        padding-left: 70px;
        padding-right: 25px;
    }

    .timeline-item:nth-child(even) {
        left: 0%;
    }

    .timeline-item:nth-child(odd) .timeline-content::after,
    .timeline-item:nth-child(even) .timeline-content::after {
        left: -10px;
    }

    .timeline-item:nth-child(odd) .timeline-dot,
    .timeline-item:nth-child(even) .timeline-dot {
        left: 18px;
    }
}

@media (max-width: 768px) {
    nav ul {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: var(--primary-color);
        flex-direction: column;
        padding: 1rem 0;
        text-align: center;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: var(--transition);
    }

    nav ul.show {
        display: flex;
    }

    nav ul li {
        margin: 0.5rem 0;
    }

    .hamburger {
        display: block;
    }

    header h1 {
        font-size: 2.5rem;
    }

    header p {
        font-size: 1.2rem;
    }
}

@media (max-width: 576px) {
    .section-title h2 {
        font-size: 2rem;
    }

    .contact-form,
    .contact-info {
        padding: 1.5rem;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate__fadeInUp {
    animation: fadeInUp 1s both;
}

.animate__fadeInLeft {
    animation: fadeInLeft 1s both;
}

.animate__fadeInRight {
    animation: fadeInRight 1s both;
}

.delay-1 {
    animation-delay: 0.2s;
}

.delay-2 {
    animation-delay: 0.4s;
}

.delay-3 {
    animation-delay: 0.6s;
}

.delay-4 {
    animation-delay: 0.8s;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.8);
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    position: relative;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

video {
    width: 100%;
    height: auto;
}
/* Ajout de styles pour les notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 5px;
    color: white;
    font-weight: 500;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
    max-width: 300px;
}

.notification.show {
    opacity: 1;
    transform: translateY(0);
}

.notification.success {
    background-color: #4CAF50;
}

.notification.error {
    background-color: #F44336;
}

/* Ajout d'un chargement pour le formulaire */
.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    margin-left: 10px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Amélioration de l'accessibilité */
.skip-to-content {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--accent-color);
    color: white;
    padding: 8px;
    z-index: 1001;
    transition: top 0.3s;
}

.skip-to-content:focus {
    top: 0;
}

/* Support du mode sombre */
@media (prefers-color-scheme: dark) {
    :root {
        --primary-color: #1a1a4a;
        --secondary-color: #3a3ab4;
        --accent-color: #4d40cf;
        --text-color: #f1f1f1;
        --light-text: #fff;
        --background-color: #121212;
        --card-bg: #1e1e1e;
    }

    .form-control {
        background-color: #2a2a2a;
        color: #f1f1f1;
    }
}

.moi {
    width: 10mm; /* Définit la largeur à 8mm */
    height: 10mm; /* Définit la hauteur à 8mm */
    border-radius: 50%; /* Rend l'image ronde */
    object-fit: cover; /* Assure que l'image remplit correctement la forme ronde */
}
    </style>
</head>

<body>
    <!-- Lien d'accessibilité pour sauter au contenu principal -->
    <a href="#accueil" class="skip-to-content">Aller au contenu</a>

    <nav>
        <div class="container">
            <a href="#accueil" class="logo"><img src="moi.jpg" alt="" class="moi">     Amery Djanang Kouomou</a>
            <ul id="menu">
                <li><a href="#accueil" class="active">Accueil</a></li>
                <li><a href="#competences">Compétences</a></li>
                <li><a href="#projets">Projets</a></li>
                <li><a href="#formations">Formations</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="hamburger" id="hamburger" aria-label="Menu de navigation">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <header id="accueil">
        <div id="particles"></div>
        <div class="header-content">
            <h1 class="animate__fadeInUp">Bienvenue sur mon Portfolio</h1>
            <p class="animate__fadeInUp delay-1">Je suis Amery Djanang Kouomou, étudiante en 3e année bachelor informatique, à l'aise avec SQL, Python et Power BI, et en route vers un poste de Data Analyst ou Data Engineer. Je recherche actuellement un stage pour juin 2026 et une alternance pour septembre 2026.</p>
            <a href="#contact" class="btn animate__fadeInUp delay-2">Me contacter</a>
        </div>
        <a href="#competences" class="scroll-down" aria-label="Défiler vers les compétences">
            <i class="fas fa-chevron-down"></i>
        </a>
    </header>

    <section id="competences">
        <div class="container">
            <div class="section-title">
                <h2>Mes Compétences</h2>
                <p>Voici les technologies et outils que je maîtrise pour analyser, transformer et visualiser des données, ainsi que pour développer des services backend.</p>
            </div>
            <div class="competences-container">
                <div class="competence-card">
                    <i class="fab fa-python" aria-hidden="true"></i>
                    <h3>Python</h3>
                    <p>Analyse de données, nettoyage et KPI avec Pandas et NumPy.</p>
                    <div class="skill-level" aria-label="Niveau de compétence: 90%">
                        <div class="skill-progress" data-level="90"></div>
                    </div>
                </div>
                <div class="competence-card">
                    <i class="fas fa-database" aria-hidden="true"></i>
                    <h3>SQL & Bases de données</h3>
                    <p>MySQL, MongoDB et Firebase pour la conception, l'extraction et la gestion des données.</p>
                    <div class="skill-level" aria-label="Niveau de compétence: 85%">
                        <div class="skill-progress" data-level="85"></div>
                    </div>
                </div>
                <div class="competence-card">
                    <i class="fas fa-chart-line" aria-hidden="true"></i>
                    <h3>Data Analysis & Visualisation</h3>
                    <p>Création de dashboards et visuels avec Power BI, Matplotlib et Excel.</p>
                    <div class="skill-level" aria-label="Niveau de compétence: 85%">
                        <div class="skill-progress" data-level="85"></div>
                    </div>
                </div>
                <div class="competence-card">
                    <i class="fas fa-server" aria-hidden="true"></i>
                    <h3>Back-end & API</h3>
                    <p>Node.js, Express.js, API REST, JSON et intégration de services pour des applications robustes.</p>
                    <div class="skill-level" aria-label="Niveau de compétence: 80%">
                        <div class="skill-progress" data-level="80"></div>
                    </div>
                </div>
                <div class="competence-card">
                    <i class="fab fa-js-square" aria-hidden="true"></i>
                    <h3>JavaScript</h3>
                    <p>JavaScript moderne pour le développement d'interfaces et l'interaction web côté client.</p>
                    <div class="skill-level" aria-label="Niveau de compétence: 80%">
                        <div class="skill-progress" data-level="80"></div>
                    </div>
                </div>
                <div class="competence-card">
                    <i class="fab fa-html5" aria-hidden="true"></i>
                    <h3>HTML5 & CSS3</h3>
                    <p>Structuration et design responsive d'interfaces web accessibles.</p>
                    <div class="skill-level" aria-label="Niveau de compétence: 80%">
                        <div class="skill-progress" data-level="80"></div>
                    </div>
                </div>
                <div class="competence-card">
                    <i class="fas fa-tools" aria-hidden="true"></i>
                    <h3>Outils & Collaboration</h3>
                    <p>GitLab / GitHub, Docker et Jira pour le versioning, l'automatisation et le suivi agile.</p>
                    <div class="skill-level" aria-label="Niveau de compétence: 75%">
                        <div class="skill-progress" data-level="75"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="projets">
        <div class="container">
            <div class="section-title">
                <h2>Projets Académiques</h2>
                <p>Projets réalisés dans le cadre de mes études, en analyse de données, back-end et visualisation.</p>
            </div>
            <div class="projets-container">
                <div class="projet-card">
                    <div class="projet-image">
                        <img src="programmephp.png" alt="Analyse du cancer - visualisation des données">
                    </div>
                    <div class="projet-content">
                        <h3>Analyse de l'Impact du Cancer</h3>
                        <p>Projet d'analyse de données sur l'impact du cancer selon les groupes d'âge et son évolution temporelle. Utilisation de Python, Pandas et Matplotlib pour analyser les tendances et visualiser les inégalités face à cette maladie.</p>
                        <div class="projet-links">
                            <a href="https://github.com/amery964/cancer-data-analysis" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i> Code source</a>
                        </div>
                    </div>
                </div>
                <div class="projet-card">
                    <div class="projet-image">
                        <img src="csharp.png" alt="Application de gestion des repas et stock">
                    </div>
                    <div class="projet-content">
                        <h3>Gestion des Repas et Stock Familial</h3>
                        <p>Application mobile développée en React Native pour la gestion des repas et du stock alimentaire dans une famille. Permet de planifier les menus, suivre les stocks et optimiser les achats.</p>
                        <div class="projet-links">
                            <a href="https://github.com/amery964/plannif-tchop" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i> Code source</a>
                        </div>
                    </div>
                </div>
                <div class="projet-card">
                    <div class="projet-image">
                        <img src="porte.png" alt="Backend de gestion d'auto-école">
                    </div>
                    <div class="projet-content">
                        <h3>Backend Auto-école (projet de stage)</h3>
                        <p>Projet de stage backend pour une auto-école avec gestion des utilisateurs, des créneaux et des séances. Développé en Node.js et Express, avec un backend structuré pour Firebase et gestion des données en temps réel.</p>
                        <div class="projet-links">
                            <a href="https://github.com/ulrichfots/backend-auto_ecole" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i> Code source</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="formations">
        <div class="container">
            <div class="section-title">
                <h2>Formations et Expériences</h2>
                <p>Mon parcours académique orienté Data Engineering et analyse de données.</p>
            </div>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">Dès septembre 2026</div>
                        <h3>Master of Data Engineering for AI</h3>
                        <p>EFREI, Paris - Formation axée sur la gestion des données, les pipelines et l'intelligence artificielle.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">En cours</div>
                        <h3>Bachelor Ingénierie informatique</h3>
                        <p>Supinfo, Paris - Développement web, bases de données et data analysis.</p>
                    </div>
                </div>
            
            </div>
        </div>
    </section>

    <section id="contact">
        <div class="container">
            <div class="section-title">
                <h2>Me Contacter</h2>
                <p> N'hésitez pas à me contacter.</p>
            </div>
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Informations de Contact</h3>
                    <p>Je suis disponible à répondre à toutes vos questions.</p>
                    <p><strong>📍 Recherche active :</strong> Stage pour juin 2026 et alternance pour septembre 2026</p>
                    <div class="contact-details">
                        <div>
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span>Paris,France</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <span>amerykouomou@gmail.com</span>
                        </div>
                        <div>
                            <i class="fas fa-phone" aria-hidden="true"></i>
                            <span>+33 65877921</span>
                        </div>
                        <div>
                            <i class="fas fa-file-pdf" aria-hidden="true"></i>
                            <a href="cv-stage.pdf" target="_blank" style="color: var(--accent-color); text-decoration: none;">CV Stage</a>
                        </div>
                        <div>
                            <i class="fas fa-file-pdf" aria-hidden="true"></i>
                            <a href="cv-alternance.pdf" target="_blank" style="color: var(--accent-color); text-decoration: none;">CV Alternance</a>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="https://linkedin.com/in/amery-kouomou" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="https://github.com/amery964" target="_blank" rel="noopener noreferrer" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="https://twitter.com/amery964" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="contact-form">
                    <h3>Envoyez-moi un message</h3>
                    <form id="contact-form" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="sujet" name="sujet" placeholder="Sujet" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="message" name="message" placeholder="Votre message" required></textarea>
                        </div>
                        <button type="submit" id="submit-btn">Envoyer le message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>À propos</h3>
                    <p>Étudiante en 3e année bachelor informatique, positionnée vers des postes de Data Analyst ou Data Engineer.</p>
                </div>
                <div class="footer-section">
                    <h3>Liens Rapides</h3>
                    <ul class="footer-links">
                        <li><a href="#accueil">Accueil</a></li>
                        <li><a href="#competences">Compétences</a></li>
                        <li><a href="#projets">Projets</a></li>
                        <li><a href="#formations">Formations</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contactez-moi</h3>
                    <p>amerykouomou@gmail.com</p>
                    <p>+3365877921</p>
                    <div class="social-links">
                        <a href="https://linkedin.com/in/amery-kouomou" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="https://github.com/amery964" target="_blank" rel="noopener noreferrer" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 | Amery Kouomou - Tous droits réservés</p>
            </div>
        </div>
    </footer>

    <!-- Notification pour les messages -->
    <div id="notification" class="notification">Message envoyé avec succès!</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>
    <script>
        // Variables globales
        const hamburger = document.getElementById('hamburger');
        const menu = document.getElementById('menu');
        const contactForm = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const notification = document.getElementById('notification');

        // Afficher une notification
        function showNotification(message, type) {
            notification.textContent = message;
            notification.className = `notification ${type}`;
            notification.classList.add('show');

            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Initialiser les animations au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
            initSkillBars();
            setupSmoothScroll();
            setupContactForm();

            // Vérifier s'il y a un message flash à afficher
            <?php if ($message): ?>
                showNotification('<?php echo addslashes($message); ?>', '<?php echo $messageType; ?>');
            <?php endif; ?>

            // Initialiser les animations au scroll
            handleScroll();
            window.addEventListener('scroll', handleScroll);

            // Menu mobile
            hamburger.addEventListener('click', toggleMobileMenu);
        });

        // Menu Toggle
        function toggleMobileMenu() {
            menu.classList.toggle('show');
            // Changer l'icône du hamburger
            const icon = hamburger.querySelector('i');
            if (menu.classList.contains('show')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
                hamburger.setAttribute('aria-expanded', 'true');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        }

        // Particle animation for header
        function createParticles() {
            const header = document.getElementById('particles');
            const particleCount = Math.min(50, window.innerWidth / 20); // Responsive particle count

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Random position
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                particle.style.left = `${posX}%`;
                particle.style.top = `${posY}%`;

                // Random size (smaller on mobile)
                const size = Math.random() * (window.innerWidth < 768 ? 3 : 5) + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;

                // Random opacity
                particle.style.opacity = Math.random() * 0.5 + 0.1;

                // Add to header
                header.appendChild(particle);

                // Animate with GSAP
                gsap.to(particle, {
                    y: Math.random() * 100 - 50,
                    x: Math.random() * 100 - 50,
                    opacity: Math.random() * 0.5,
                    duration: Math.random() * 10 + 5,
                    repeat: -1,
                    yoyo: true,
                    ease: "sine.inOut"
                });
            }
        }

        // Initialize skill progress bars with animation
        function initSkillBars() {
            const skillBars = document.querySelectorAll('.skill-progress');

            // Utiliser GSAP pour animer les barres de compétences
            skillBars.forEach(bar => {
                const level = bar.getAttribute('data-level');
                gsap.fromTo(bar,
                    { width: 0 },
                    {
                        width: `${level}%`,
                        duration: 1.5,
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: bar,
                            start: "top 80%"
                        }
                    }
                );
            });
        }

        // Smooth scroll pour les liens d'ancrage
        function setupSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Fermer le menu mobile si ouvert
                    if (menu.classList.contains('show')) {
                        toggleMobileMenu();
                    }

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 70, // Ajuster pour la barre de navigation
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }

        // Gérer les animations au scroll
        function handleScroll() {
            const scrollY = window.scrollY;

            // Mettre à jour la navigation active
            const sections = document.querySelectorAll('section, header');
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.offsetHeight;
                const sectionId = section.getAttribute('id');

                if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                    document.querySelectorAll('nav ul li a').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${sectionId}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }

        // Configuration du formulaire de contact
        function setupContactForm() {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validation côté client
                const nom = document.getElementById('nom').value.trim();
                const email = document.getElementById('email').value.trim();
                const sujet = document.getElementById('sujet').value.trim();
                const message = document.getElementById('message').value.trim();

                if (!nom || !email || !sujet || !message) {
                    showNotification('Tous les champs sont obligatoires.', 'error');
                    return;
                }

                // Vérification simple du format email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showNotification('Adresse e-mail invalide.', 'error');
                    return;
                }

                // Afficher l'indicateur de chargement
                submitBtn.innerHTML = 'Envoi en cours... <span class="loading"></span>';
                submitBtn.disabled = true;

                // Envoyer le formulaire via AJAX
                const formData = new FormData(this);

                fetch(this.action || window.location.href, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Réinitialiser le bouton
                    submitBtn.innerHTML = 'Envoyer le message';
                    submitBtn.disabled = false;

                    // Afficher la notification
                    showNotification(data.message, data.status);

                    // Réinitialiser le formulaire si succès
                    if (data.status === 'success') {
                        contactForm.reset();
                    }
                })
                .catch(error => {
                    // Gérer les erreurs
                    submitBtn.innerHTML = 'Envoyer le message';
                    submitBtn.disabled = false;
                    showNotification('Une erreur s\'est produite. Veuillez réessayer.', 'error');
                    console.error('Erreur:', error);
                });
            });
        }

        // Observer les éléments pour les rendre visibles
        function observeElements() {
            const elementsToObserve = document.querySelectorAll('.competence-card, .projet-card, .timeline-item');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target); // Arrêter d'observer une fois visible
                    }
                });
            }, {
                threshold: 0.1 // Déclencher lorsque 10% de l'élément est visible
            });

            elementsToObserve.forEach(element => {
                observer.observe(element);
            });
        }

        // Appeler la fonction au chargement de la page
        document.addEventListener('DOMContentLoaded', observeElements);
    </script>
</body>
</html>