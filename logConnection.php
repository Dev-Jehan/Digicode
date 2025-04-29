<?php
// Démarrer la session pour récupérer les informations de l'utilisateur connecté
session_start();

// Inclusion des paramètres et de la bibliothèque de fonctions
include_once('include/_inc_parametres.php');

// Connexion du serveur web à la base MySQL
include_once('include/_inc_connexion.php');

// Vérification si l'utilisateur est connecté et est un administrateur
if (isset($_SESSION['user_id']) && $_SESSION['level'] == 'admin') {

    // Requête pour récupérer les logs des connexions échouées
    $sql = "SELECT * FROM mrbs_connectLoose ORDER BY date DESC, heure DESC";
    $stmt = $cnx->prepare($sql);
    $stmt->execute();

    // Vérification s'il y a des résultats
    if ($stmt->rowCount() > 0) {
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $logs = [];
    }

} else {
    // Si l'utilisateur n'est pas connecté ou n'est pas un administrateur
    header('Location: index.php'); // Redirection vers la page de connexion
    exit();
}

?>

<!DOCTYPE HTML>
<html lang="fr">

<head>
    <title>Logs des Connexions</title>
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
    <div id="logConnectionPage">

        <?php if (!empty($logs)): ?>
            <table id="logsTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Mot de passe</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Adresse IP</th>
                    <th>Raison</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($log['id']); ?></td>
                        <td><?php echo htmlspecialchars($log['name']); ?></td>
                        <td><?php echo htmlspecialchars($log['password']); ?></td>
                        <td><?php echo date('d/m/y', strtotime($log['date'])); ?></td>
                        <td><?php echo date('H\Hi', strtotime($log['heure'])); ?></td>
                        <td><?php echo htmlspecialchars($log['adresseIP']); ?></td>
                        <td>
                            <?php
                            if ($log['raison'] == 1) {
                                echo 'Mot de passe incorrect';
                            } else if ($log['raison'] == 2) {
                                echo 'Nom d\'utilisateur incorrect';
                            } else {
                                echo 'Raison inconnue';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun log de connexion échouée n'a été trouvé.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
