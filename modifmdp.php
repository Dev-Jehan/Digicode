<?php
// Démarrer la session pour récupérer les informations de l'utilisateur connecté
session_start();

// Inclusion des paramètres et de la bibliothèque de fonctions
include_once('include/_inc_parametres.php');
include_once('include/_inc_connexion.php');

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Accès interdit. Vous devez être connecté pour modifier votre mot de passe.";
    exit;
}

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];
// Vérifier que l'ID est bien récupéré depuis la session
//echo "User ID: " . $_SESSION['user_id'] . "<br>";

// Récupérer les informations de l'utilisateur depuis la base de données
$sqlUser = "SELECT * FROM mrbs_users WHERE id = :id LIMIT 1";
$stmtUser = $cnx->prepare($sqlUser);
$stmtUser->bindParam(':id', $userId, PDO::PARAM_INT);
$stmtUser->execute();
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

//// Vérifier si l'utilisateur a été trouvé et afficher ses informations
//if ($user) {
//    echo "Utilisateur trouvé :<br>";
//    echo "ID: " . $user['id'] . "<br>";
//    echo "Level: " . $user['level'] . "<br>";
//    echo "Name: " . $user['name'] . "<br>";
//    echo "Email: " . $user['email'] . "<br>";
//} else {
//    echo "Utilisateur non trouvé.";
//}

// Vérification de la soumission du formulaire pour modifier le mot de passe
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les valeurs du formulaire
    $currentPassword = $_POST['current_password'];
    $newPassword1 = $_POST['new_password1'];
    $newPassword2 = $_POST['new_password2'];

    // Vérifier si le mot de passe actuel est correct
    if (md5($currentPassword) !== $user['password']) {
        $error = "Le mot de passe actuel est incorrect. Veuillez réessayer.";
    }
    // Vérifier si les deux nouveaux mots de passe sont identiques
    elseif ($newPassword1 !== $newPassword2) {
        $error = "Les deux nouveaux mots de passe ne correspondent pas. Veuillez réessayer.";
    }
    // Vérifier si le nouveau mot de passe est identique à l'ancien
    elseif (md5($newPassword1) === $user['password']) {
        $error = "Le nouveau mot de passe ne doit pas être identique à l'ancien mot de passe.";
    } else {
        // Mettre à jour le mot de passe dans la base de données
        $sqlUpdatePassword = "UPDATE mrbs_users SET password = :password WHERE id = :id";
        $stmtUpdatePassword = $cnx->prepare($sqlUpdatePassword);
        $newPasswordHashed = md5($newPassword1); // Hachage du nouveau mot de passe
        $stmtUpdatePassword->bindParam(':password', $newPasswordHashed, PDO::PARAM_STR);
        $stmtUpdatePassword->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtUpdatePassword->execute();

        $success = "Votre mot de passe a été modifié avec succès.";
    }
}
?>

<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title>Maison des Ligues - Modifier le Mot de Passe</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
    <div style="clear:both"></div>
    <table>
        <form action="modifmdp.php" method="post">
            <tr>
                <td>
                    <label for="current_password">Mot de passe actuel :</label>
                    <input type="password" name="current_password" id="current_password" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="new_password1">Nouveau mot de passe :</label>
                    <input type="password" name="new_password1" id="new_password1" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="new_password2">Confirmer le nouveau mot de passe :</label>
                    <input type="password" name="new_password2" id="new_password2" required>
                </td>
            </tr>

            <!-- Affichage des messages d'erreur ou de succès -->
            <?php if (isset($error)) { ?>
                <tr>
                    <td style="color: red;">
                        <?php echo $error; ?>
                    </td>
                </tr>
            <?php } elseif (isset($success)) { ?>
                <tr>
                    <td style="color: green;">
                        <?php echo $success; ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>
                    <input type="submit" value="Modifier le mot de passe">
                </td>
            </tr>
        </form>
    </table>
    <div style="clear:both"></div>
</div>

</body>
</html>
