<?php
	/* ouverture d'une session */
	// cette instruction doit toujours être la première ligne du code pour fonctionner!!!!!!!!!!!!!
	session_start();	
	// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
	include_once ('include/_inc_parametres.php');
    // connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('include/_inc_connexion.php');
?>
<!DOCTYPE HTML> 
<html lang="fr">
  <!-- début du html (bloc entête) -->
  <head>
    <title>Maison des Ligues</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<!-- accès à la feuille de style -->
    <link href="./styles/style.css" rel="stylesheet" type="text/css" />
  </head>
  
  <!-- Début du corps -->  
  <body>
  
	<div id="entete">
		<!-- affichage de l'image M2L -->
		<img src="images/logo.jpg">
	</div>
	
	<?php

    if (isset($_GET['action']) && $_GET['action'] == "connexion") {
        if ($_POST['txtnom'] == "" || $_POST['txtmdp'] == "") {
            ?>
            <div id="connexion">
                <h3>Erreur de connexion</h3>
                <div id="erreurCo">
                    Merci de bien vouloir renseigner les champs </br>
                    <a href="index.php"><u>Retour</u></a>
                </div>
            </div>
            <?php
        } else {
            $nom = $_POST['txtnom'];
            $mdp = md5($_POST['txtmdp']);  // codage MD5 comme dans la base

            // Vérifier si l'utilisateur existe
            $req_user = $cnx->prepare("SELECT * FROM mrbs_users WHERE name = :nom");
            $req_user->bindValue(':nom', $nom, PDO::PARAM_STR);
            $req_user->execute();
            $user = $req_user->fetch(PDO::FETCH_OBJ);

            // Initialiser les données de connexion échouée si besoin
            if (!$user) {
                $raison = 2; // utilisateur inconnu
            } else if ($user->password != $mdp) {
                $raison = 1; // mauvais mot de passe
            }

            // Si connexion échouée
            if (!isset($raison)) {
                // Succès de connexion
                $_SESSION['nom'] = $nom;
                $_SESSION['password'] = $_POST['txtmdp'];
                $_SESSION['user_id'] = $user->id;
                $_SESSION['level'] = ($user->level == 2) ? 'admin' : (($user->level == 1) ? 'user' : 'none');
                header("Location:gestion.php");
            } else {
                // Récupération des infos à logguer
                $ip = $_SERVER['REMOTE_ADDR'];
                $date = date('Y-m-d');
                $heure = date('H:i:s');

                // Insertion dans la table mrbs_connectLoose
                $insert = $cnx->prepare("INSERT INTO mrbs_connectLoose (name, password, date, heure, adresseIP, raison)
                                     VALUES (:name, :password, :date, :heure, :ip, :raison)");
                $insert->bindValue(':name', $nom, PDO::PARAM_STR);
                $insert->bindValue(':password', $_POST['txtmdp'], PDO::PARAM_STR);  // mot de passe en clair (comme saisi)
                $insert->bindValue(':date', $date, PDO::PARAM_STR);
                $insert->bindValue(':heure', $heure, PDO::PARAM_STR);
                $insert->bindValue(':ip', $ip, PDO::PARAM_STR);
                $insert->bindValue(':raison', $raison, PDO::PARAM_INT);
                $insert->execute();
                ?>
                <div id="connexion">
                    <h3>Erreur de connexion</h3>
                    <div id="erreurCo">
                        Erreur de connexion, informations erronées ! </br>
                        <a href="index.php"><u>Retour</u></a>
                    </div>
                </div>
                <?php
            }
        }
    }

    /* Si l'action est déconnexion */
		elseif (isset($_GET['action']) && $_GET['action']=="deconnexion")
		{
			/* suppression des variables de session */
			session_unset();
			session_destroy();
			/* retour à la page index.php  */
			header("Location:index.php");
		}
		else
		{
		/* affichage du formulaire de saisie lors de la 1ère exécution */
		?>
			
			<div id="connexion">
			
				<h3>Connexion</h3>
			
				<div id="formConnexion">
					<!-- rappel de cette page lors du clic sur le bouton Valider (2ème exécution) -->
					<form action="index.php?action=connexion" method="post">
					<table>
						<tr>
							<td>Utilisateur : </td> <td> <input type="text" name="txtnom"></td>
						</tr>
						<tr>
							<td>Mot de passe : </td> <td> <input type="password" name="txtmdp"></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" value="Valider"></td>
						</tr>
					</table>
					</form>
				</div>
				
				<div id="utile">
					<!-- appel de la page forgetMdp.php lors du clic sur le bouton -->
					<a href="forgetMdp.php" ><u>Mot de passe oublié</u></a> | 
				</div>
			</div>
		<?php
		}
	?>
  </body>
</html>