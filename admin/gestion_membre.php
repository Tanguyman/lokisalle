<?php
require("../inc/init.inc.php");

// controle de l'acces à la page -> réservé aux admins (status 1)
if(!utilisateur_est_admin())
{
    header("location:../connexion.php");
    exit(); // pour ne pas exécuter la suite du code - arrêt de l'exécution du script - injection de script impossible via GET
}








// la ligne suivante commence les affichages dans la page
require("../inc/header.inc.php");
require("../inc/nav.inc.php");
?>



    <div class="container">

        <div class="starter-template">
            <h1><span class="glyphicon glyphicon-user"></span> Gestion des membres</h1>
            <?= $message; ?>
        </div>

    </div><!-- /.container -->

    <?php
    require("../inc/footer.inc.php");