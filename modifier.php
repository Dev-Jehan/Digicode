<?php
// Démarrer la session pour récupérer les informations de l'utilisateur connecté
session_start();

// Inclusion des paramètres et de la bibliothèque de fonctions
include_once('include/_inc_parametres.php');
include_once('include/_inc_connexion.php');

// Vérification si l'utilisateur est connecté et s'il a le niveau 2 (admin)
if (!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    echo "Accès interdit. Vous devez être un administrateur pour modifier le digicode.";
    exit;
}

// Récupérer le digicode actuel depuis la table mrbs_room
$sqlDigicode = "SELECT digicode FROM mrbs_room LIMIT 1"; // Tu peux aussi récupérer un digicode spécifique si nécessaire
$stmtDigicode = $cnx->query($sqlDigicode);

if ($stmtDigicode && $rowDigicode = $stmtDigicode->fetch(PDO::FETCH_ASSOC)) {
    $Digicode = $rowDigicode['digicode'];
}

// Vérification de la soumission du formulaire pour modifier le digicode
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les valeurs des nouveaux digicodes et les nettoyer
    $newDigicode1 = trim($_POST['digicode1']);
    $newDigicode2 = trim($_POST['digicode2']);

    // Vérifier si les deux nouveaux digicodes sont identiques
    if ($newDigicode1 !== $newDigicode2) {
        $error = "Les deux digicodes ne correspondent pas. Veuillez réessayer.";
    } elseif ($newDigicode1 === $Digicode) {
        $error = "Le nouveau digicode est identique à l'ancien. Veuillez entrer un nouveau digicode.";
    } elseif (!preg_match("/^\d{6}$/", $newDigicode1)) {
        // Validation pour vérifier que le digicode est composé de 6 chiffres
        $error = "Le digicode doit être composé de 6 chiffres.";
    } else {
        // Mettre à jour le digicode dans toutes les salles de la base de données
        $sqlUpdate = "UPDATE mrbs_room SET digicode = :digicode"; // Pas de WHERE, on met à jour toutes les salles
        $stmtUpdate = $cnx->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':digicode', $newDigicode1, PDO::PARAM_STR);
        $stmtUpdate->execute();

        $success = "Le digicode a été modifié pour toutes les salles avec succès.";
    }
}
?>

<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title>Maison des Ligues - Modifier le Digicode</title>
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
        <tr>
            <td>
                Voici le digicode actuel : <strong><?php echo htmlspecialchars($Digicode); ?></strong>
            </td>
        </tr>
        <form action="modifier.php" method="post">
            <tr>
                <td>
                    <label for="digicode1">Nouveau digicode :</label>
                    <input type="text" name="digicode1" id="digicode1" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="digicode2">Confirmer le nouveau digicode :</label>
                    <input type="text" name="digicode2" id="digicode2" required>
                </td>
            </tr>
            <!-- Message d'erreur ou de succès -->
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
                    <input type="submit" value="Modifier le digicode">
                </td>
            </tr>
        </form>
    </table>
    <div style="clear:both"></div>
</div>

</body>
</html>
