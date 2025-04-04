<?php 
	// cette instruction doit toujours être la première ligne du code pour fonctionner!!!!!!!!!!!!!
	session_start();
	// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
	include_once ('include/_inc_parametres.php');
    // connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('include/_inc_connexion.php');
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
	
	<div id="connexion">
		<h3>Mot de passe oubli&eacute;</h3>
		
		<?php 
		if(isset($_SESSION['verifAdr'])) {
			if ($_SESSION['verifAdr']==false) {
				echo "<div class='msg'><p> Adresse mail inconnue </p></div>";
			}	
		}
		?>
	
		<p>Entrez dans le champ ci-dessous votre adresse e-mail afin de recevoir un nouveau mot de passe</p>
	
		<div id="formForgetMdp">
			<form action="getAdr.php" name="forgetMdp" method="post" >
				<table>
					<tr>
						<td>Adresse mail : </td>
						<td><input type="email" name="txtadr"/></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Envoyer"/>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><u>Retour</u></a></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
  </body>
</html>