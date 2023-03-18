<?php
session_start();

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST['g-recaptcha-response'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaSecret = '6LeMHRElAAAAAI40BMkqAmWsQ5MaJiLizJqdp4p4';

    $recaptcha = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $recaptcha = json_decode($recaptcha);

    if ($recaptcha->success) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        require_once "../core/includes/connect.php";

        $sql = "SELECT * FROM utilisateur WHERE email LIKE :email OR pseudo LIKE :email;";

        $query = $bdd->prepare($sql);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch();
        if ($results) {
            if (password_verify($password, $results['hash'])) {
                if (!$results['actif']) {
                    header("Location: ../core/actions/deactived.php");
                    exit();
                }
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
        <input type="text" name="email" placeholder="Email ou Pseudo*" require>
        <input type="password" name="password" placeholder="Mot de passe *" require>
        <div class="g-recaptcha" data-sitekey="6LeMHRElAAAAACgEgUGBNqALUexnqykdbjJq7z-O"></div>
        <button>Connexion</button>
    </form>
</main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
require_once '../core/includes/footer.php';
?>
