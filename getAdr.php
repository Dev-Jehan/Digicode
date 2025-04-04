<?php 
	// cette instruction doit toujours être la première ligne du code pour fonctionner!!!!!!!!!!!!!
	session_start();
	// inclusion des paramètres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
	include_once ('include/_inc_parametres.php');
    // connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
	include_once ('include/_inc_connexion.php');
	require_once("include/redirect.php");
?>
<!DOCTYPE HTML> 
<html lang="fr">

  <head>
    <title>Maison des Ligues</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="./styles/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript"  src="js/forgetVerif.js"></script>
  </head>
  
  <body>
	<?php 
	/*  récupération de l'adresse mail saisie et sauvegarde dans une variable de session*/
	$_SESSION['adr']=$_POST['txtadr'];
	
	/*  recherche de l'utilisateur avec l'adresse mail saisie */
	$req_pre = $cnx->prepare("SELECT * FROM mrbs_users WHERE email = :adr");
	// liaison de la variable à la requête préparée
	$req_pre->bindValue(':adr', $_POST['txtadr'], PDO::PARAM_STR);
	$req_pre->execute();
	//le résultat est récupéré sous forme d'objet
	$resultat=$req_pre->fetch(PDO::FETCH_OBJ);
	
	if($resultat->email == "")	{
		/* utilisateur non reconnu et redirection vers la page forgetMdp.php */
		$_SESSION['verifAdr']=false;		
		redirect("forgetMdp.php",0);
	}
	else 
	{
		/* utilisateur reconnu envoi du mail et redirection vers la page mailMdp */
		$_SESSION['nom']=$resultat->name;
		$_SESSION['level']=$resultat->level;
		$_SESSION['verifAdr']=true;
		redirect("mailMdp.php",0);
	}
	
	?>
	</body>
</html>