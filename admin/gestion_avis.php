<?php
require("../inc/init.inc.php");

// controle de l'acces à la page -> réservé aux admins (status 1)
if(!utilisateur_est_admin())
{
    header("location:../connexion.php");
    exit(); // pour ne pas exécuter la suite du code - arrêt de l'exécution du script - injection de script impossible via GET
}


// Requete sur toutes les données des tables avis, membre et salle
$req_avis = $pdo->query("SELECT * FROM avis, membre, salle WHERE avis.id_membre = membre.id_membre AND avis.id_salle = salle.id_salle");


// la ligne suivante commence les affichages dans la page
require("../inc/header.inc.php");
require("../inc/nav.inc.php");
?>

    <div class="container">

        <div class="starter-template">
            <h1><span class="glyphicon glyphicon-user"></span> Gestion des avis</h1>
            <?php echo $message; ?>
        </div>

        <!-- tableau -->
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <th>id avis</th>
                        <th>id membre</th>
                        <th>id salle</th>
                        <th>commentaire</th>
                        <th>note</th>
                        <th>date</th>
                        <th>action</th>
                    </thead>
                    <tbody>
                        <?php
                            while($avis = $req_avis->fetch(PDO::FETCH_ASSOC))
                            {
                                echo '<tr>';
                                    //echo '<pre>'; print_r($avis); echo '</pre>';
                                    echo '<td>' . $avis['id_avis'] . '</td>';
                                    echo '<td>' . $avis['id_membre'] . ' - ' . $avis['email'] . '</td>';
                                    echo '<td>' . $avis['id_salle'] . ' - ' . $avis['titre'] . '</td>';
                                    echo '<td>' . $avis['commentaire'] . '</td>';
                                    echo '<td>';
                                        // transformation de la note en étoiles
                                        $note = $avis['note'];
                                        for($i = 0; $i < $note; $i++)
                                        {
                                            echo '<span class="glyphicon glyphicon-star"></span>';
                                        }
                                        //echo $avis['note'];
                                    echo '</td>';
                                    echo '<td>' . $avis['date_enregistrement'] . '</td>';
                                    echo '<td>';
                                        echo '<a href="?action=modif&id_avis=' . $avis['id_avis'] . '" class="btn btn-warning modif" id="id_avis=' . $avis['id_avis'] . '"><span class="glyphicon glyphicon-pencil" ></span></a>';
                                        echo '<a href="?action=suppr&id_avis=' . $avis['id_avis'] . '" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>';
                                    echo '</td>';                                    
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div><!-- fin .row -->
        <hr />






    </div><!-- /.container -->

<?php
    require("../inc/footer.inc.php");