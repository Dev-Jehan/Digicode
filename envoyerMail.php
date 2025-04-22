<?php
// Démarrer la session pour récupérer les informations de l'utilisateur connecté
session_start();

// Inclusions des fichiers nécessaires
include_once('include/_inc_parametres.php');
include_once('include/_inc_connexion.php');
require_once("include/redirect.php");
include ("include/Outils.class.php");

if (!isset($_SESSION['user_id'])) {
    echo "Accès interdit. Vous devez être connecté.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les destinataires sélectionnés
    $destinataires = isset($_POST['destinataires']) ? $_POST['destinataires'] : [];
    $objet = trim($_POST['objet']);
    $message = trim($_POST['message']);

    if (empty($destinataires) || empty($objet) || empty($message)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {
        // Récupérer les adresses e-mail des utilisateurs sélectionnés
        $emails = [];
        foreach ($destinataires as $userId) {
            $sqlEmail = "SELECT email FROM mrbs_users WHERE id = :id";
            $stmtEmail = $cnx->prepare($sqlEmail);
            $stmtEmail->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmtEmail->execute();

            if ($rowEmail = $stmtEmail->fetch(PDO::FETCH_ASSOC)) {
                $emails[] = $rowEmail['email'];
            }
        }

        if (count($emails) > 0) {
            // Envoi de l'e-mail en utilisant la classe Outils (comme dans ton fichier mailMdp.php)
            $sujet = $objet;
            $message = $message;

            try {
                foreach ($emails as $email) {
                    Outils::envoyerMail($email, $sujet, $message, "delasalle.sio.crib@gmail.com");
                }

                $confirmation = "Le message a été envoyé avec succès à " . count($emails) . " destinataire(s).";
            } catch (Exception $e) {
                $erreur = "Le message n'a pas pu être envoyé. Erreur : " . $e->getMessage();
            }
        } else {
            $erreur = "Aucun destinataire valide sélectionné.";
        }
    }
}

// Récupérer la liste des utilisateurs pour la sélection
$sqlUsers = "SELECT id, name FROM mrbs_users";
$stmtUsers = $cnx->query($sqlUsers);
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Envoyer un Mail</title>
    <link href="./styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="entete">
    <img src="images/logo.jpg">
</div>

<div id="menu">
    <?php include("menu.php"); ?>
</div>

<div id="pagegestionD">
    <h2>Envoyer un e-mail</h2>

    <?php if (isset($erreur)) : ?>
        <p style="color:red;"><?php echo $erreur; ?></p>
    <?php elseif (isset($confirmation)) : ?>
        <p style="color:green;"><?php echo $confirmation; ?></p>
    <?php endif; ?>

    <form method="post" action="envoyerMail.php">
        <label for="destinataires">Destinataire(s) :</label><br>
        <select name="destinataires[]" id="destinataires" multiple size="10" style="width:400px;">
            <?php foreach ($users as $user) : ?>
                <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="objet">Objet :</label><br>
        <input type="text" name="objet" id="objet" style="width:400px;" required><br><br>

        <label for="message">Message :</label><br>
        <textarea name="message" id="message" rows="8" cols="60" required></textarea><br><br>

        <input type="submit" value="Envoyer">
    </form>
</div>

</body>
</html>
