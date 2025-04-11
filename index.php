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
		
		/* Vérification de la variable action qui ne contient rien lors de la 1ère exécution */
		if (isset($_GET['action'])&& $_GET['action']=="connexion")
		{
			/* Vérification des champs vides */
			if ($_POST['txtnom']=="" OR $_POST['txtmdp']=="")
			{
				/* message d'erreur si les champs sont vides */
				?>
				<div id="connexion">
					<h3>Erreur de connexion</h3>
					<div id="erreurCo">
						Merci de bien vouloir renseigner les champs </br>
						<a href="index.php"><u>Retour</u></a>
					</div>
				</div>
				<?php
			}
							
			/* Si les champs sont remplis */
			else
			{
				/* recherche de l'utilisateur à partir de son nom et de son mot de passe */
				// préparation de la requête
				$req_pre = $cnx->prepare("SELECT * FROM mrbs_users WHERE name=:nom AND password=:mdp");
				// liaison des variables à la requête préparée
				$req_pre->bindValue(':nom', $_POST['txtnom'], PDO::PARAM_STR);
				// dans la base de données de l'application VALRES, les mots de passe sont codés en md5 
				$req_pre->bindValue(':mdp', md5($_POST['txtmdp']), PDO::PARAM_STR);
				$req_pre->execute();
				//le résultat est récupéré sous forme d'objet
				$resultat=$req_pre->fetch(PDO::FETCH_OBJ);
				
				//si aucune ligne trouvée
				if (!$resultat) {
					?> <div id='connexion'>
						<h3>Erreur de connexion</h3>
						<div id='erreurCo'>
							<p>Vous n'êtes pas enregistré, veuillez contacter l'administrateur ! </p>
							<a href='index.php'><u>Retour</u></a>
						</div>
					</div>
					<?php
				}
				else
				{
					/* Si l'utilisateur a été trouvé  */
                    /* Si l'utilisateur a été trouvé  */
                    if ($resultat == true)  {
                        /* sauvegarde de son nom et de son mot de passe dans des variables de session */
                        $_SESSION['nom'] = $_POST['txtnom'];
                        $_SESSION['password'] = $_POST['txtmdp'];

                        /* Sauvegarder l'ID de l'utilisateur dans la session */
                        $_SESSION['user_id'] = $resultat->id;  // Ajoute cette ligne pour stocker l'ID

                        /* si l'utilisateur connecté est un administrateur ou bien un autre utilisateur */
                        if ($resultat->level == 2) {
                            $_SESSION['level'] = 'admin';
                        } else {
                            if ($resultat->level == 1) {
                                $_SESSION['level'] = 'user';
                            } else {
                                $_SESSION['level'] = 'none';
                            }
                        }

                        /* appel de la page suivante gestion.php*/
                        header("Location:gestion.php");
                    } else {
                        /* affichage de l'erreur */
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