<?php
// cette instruction session_start() doit toujours être la première ligne du code pour fonctionner!!!!!!!!!!!!!
session_start();
require_once("include/fonctionVerifCode.php");
// inclusion des paramétres et de la bibliothéque de fonctions ("include_once" peut être remplacé par "require_once")
include_once ('include/_inc_parametres.php');
// connexion du serveur web à la base MySQL ("include_once" peut être remplacé par "require_once")
include_once ('include/_inc_connexion.php');
require_once("include/redirect.php");
// classe outils permettant d'envoyer des mails
include ("include/Outils.class.php");
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
        <img src="images/logo.JPG">
    </div>
<?php
// récupération des informations de l'utilisateur
// via les variables de session qui sont stockées dans des variables locales
$adr=$_SESSION['adr'];
$nom=$_SESSION['nom'];
$level=$_SESSION['level'];
// appel de la fonction getCode qui permet d'obtenir un nouveau mdp
$mdp=getCode();

//On vide les variables de session liées au demandeur pour n'utiliser que des variables locales
$verif=true;

//session_unset('adr');
try
{
    // préparation de la requête : mise à jour du mot de passe crypté
    $req_pre = $cnx->prepare("UPDATE mrbs_users SET password = :mdp WHERE email = :adr");
    // liaison des variables à la requête préparée
    $req_pre->bindValue(':mdp', md5($mdp), PDO::PARAM_STR);
    $req_pre->bindValue(':adr', $adr, PDO::PARAM_STR);
    $req_pre->execute();
}
catch (exception $e)
{
    $verif =false;
}

if ($verif == false)
{
    echo "	<div id='connexion'>
				<h3>R&eacute;initialiser le mot de passe</h3>
				<div id='erreurCo'>
					<p>La v&eacute;rification a &eacute;chou&eacute;, veuillez r&eacute;essayer ult&eacuterieurement</p>
					<p>Vous allez &ecirc;tre redirig&eacute; dans 3 secondes</p>
				</div>
			</div>";
    redirect("forgetMdp.php",3);
}
else
{
    echo "	<div id='connexion'>
				<h3>R&eacute;initialiser le mot de passe</h3>
				<div id='erreurCo'>
					<p>Le mot de passe a &eacute;t&eacute r&eacute;initialis&eacute; avec succ&egrave;s</p>
					<p>Vous allez &ecirc;tre redirig&eacute; dans 3 secondes</p>
				</div>
			</div>";
}
//construction du mail

//=====Déclaration des messages au format texte et au format HTML.


$message = "Bonjour Madame,Monsieur " . $nom .". ".mb_convert_encoding("Conformément à votre demande, votre mot de passe vient de vous être renvoyé", 'ISO-8859-1', 'UTF-8')."Vos Informations :  Votre identifiant : ".$nom."  Votre Mot de Passe : ".$mdp.";";



//=====Définition du sujet.
$sujet = "Mot de passe de l'application Maison des Ligues";


// envoi du mail
try
{
    // envoi du mail : destinataire, objet, message, émetteur
    Outils::envoyerMail ($adr, $sujet, $message, "delasalle.sio.crib@gmail.com" ) ;
    // mail($adr,$sujet,$message,$header);
}
catch (exception $e)
{
    echo "	<div id='connexion'>
				<h3>R&eacute;initialiser le mot de passe</h3>
				<div id='erreurCo'>
					<p>La v&eacute;rification a &eacute;chou&eacute;, veuillez r&eacute;essayer ult&eacuterieurement</p>
					<p>Vous allez &ecirc;tre redirig&eacute; dans 3 secondes</p>
				</div>
			</div>";
}
?>

<?php

// redirection vers index.php
redirect("index.php","3");
session_destroy();
?>