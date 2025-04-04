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
				 </tr>
				 <tr>
				  <td>Page en cours de développement!!! <img src="images/chantier.jpg"></td>
				 </tr>
				</table>
			   	  </td>
			 </tr>
			</table>
		<div style="clear:both"></div>
					
	</div>
   </body>
</html>