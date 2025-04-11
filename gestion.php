<?php
// démarrer la session pour récupérer les informations de l'utilisateur connecté
session_start();

// inclusion des paramètres et de la bibliothèque de fonctions
include_once ('include/_inc_parametres.php');

// connexion du serveur web à la base MySQL
include_once ('include/_inc_connexion.php');

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];  // Récupère l'ID de l'utilisateur connecté

    // Préparer la requête SQL pour récupérer les informations de l'utilisateur
    $sqlUser = "SELECT * FROM mrbs_users WHERE id = :id LIMIT 1";
    $stmtUser = $cnx->prepare($sqlUser);
    $stmtUser->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmtUser->execute();

//    // Si l'utilisateur est trouvé
//    if ($rowUser = $stmtUser->fetch(PDO::FETCH_ASSOC)) {
//        echo "ID: " . $rowUser['id'] . "<br>";
//        echo "Level: " . $rowUser['level'] . "<br>";
//        echo "Name: " . $rowUser['name'] . "<br>";
//        echo "Email: " . $rowUser['email'] . "<br>";
//    } else {
//        echo "Utilisateur non trouvé.";
//    }

    // Récupération du digicode depuis la table mrbs_room
    $sqlDigicode = "SELECT digicode FROM mrbs_room LIMIT 1";  // Sélectionne le digicode (si une seule valeur unique dans la table)
    $stmtDigicode = $cnx->query($sqlDigicode);

    if ($stmtDigicode && $rowDigicode = $stmtDigicode->fetch(PDO::FETCH_ASSOC)) {
        // Récupère le digicode et l'affiche
        $digicode = $rowDigicode['digicode'];
//        echo "Digicode: " . $digicode . "<br>";
    } else {
        echo "Digicode non trouvé.";
    }
} else {
    echo "Utilisateur non connecté.";
}
?>
<!DOCTYPE HTML>
<html lang="fr">

  <head>
    <title>Maison des Ligues</title>
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
                        Nom : <?php echo $_SESSION['nom']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Niveau : <?php echo $_SESSION['level']; ?>
                    </td>
                </tr>

                <?php
                // Affichage du digicode uniquement pour les niveaux 'admin' et 'user'
                if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'user') {
                    ?>
                    <tr>
                        <td>
                            Digicode : <?php echo htmlspecialchars($digicode); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <tr>
                    <td>
                        <!-- Lien vers la modification de son mot de passe -->
                        <a href="modifmdp.php">
                            <button type="button">Modifier mot de passe</button>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        Page en cours de développement!!! <img src="images/chantier.jpg">
                    </td>
                </tr>
            </table>
		<div style="clear:both"></div>

	</div>
   </body>
</html>