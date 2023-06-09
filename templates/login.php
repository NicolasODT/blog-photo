<?php
session_start();

// Vérifie si les informations de connexion ont été envoyées par le formulaire
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST['g-recaptcha-response'])) {

    // Vérifie le recaptcha
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaSecret = '6LeMHRElAAAAAI40BMkqAmWsQ5MaJiLizJqdp4p4';

    $recaptcha = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $recaptcha = json_decode($recaptcha);

    // Si le recaptcha est validé
    if ($recaptcha->success) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        require_once "../core/includes/connect.php";


        // Requête pour récupérer les informations de l'utilisateur
        $sql = "SELECT * FROM utilisateur WHERE email LIKE :email OR pseudo LIKE :email;";
        $query = $bdd->prepare($sql);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch();
        // Si l'utilisateur existe dans la base de données
        if ($results) {
            // Vérifie le mot de passe
            if (password_verify($password, $results['hash'])) {
                // Vérifie si le compte est actif
                if (!$results['actif']) {
                    header("Location: ../core/actions/deactived.php");
                    exit();
                }
                // Stocke les informations de l'utilisateur en session
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $results['role'];
                $_SESSION['id'] = $results['id'];
                $_SESSION['pseudo'] = $results['pseudo'];
                header('location: ../index.php');
            } else {
                echo 'Mot de passe incorrect';
            }
        } else {
            echo 'Email non trouvé';
        }
    } else {
        echo "Erreur reCAPTCHA";
    }
}

require_once '../core/includes/header.php';
?>

<main>
    <form action="" method="post" class="form-register">
        <input type="text" name="email" placeholder="Email ou Pseudo*" required>
        <input type="password" name="password" placeholder="Mot de passe *" required>
        <div class="g-recaptcha" data-sitekey="6LeMHRElAAAAACgEgUGBNqALUexnqykdbjJq7z-O"></div>
        <button>Connexion</button>
    </form>
</main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
require_once '../core/includes/footer.php';
?>
