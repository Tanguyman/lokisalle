<?php
require("../inc/init.inc.php");

// controle de l'acces à la page -> réservé aux admins (status 1)
if(!utilisateur_est_admin())
{
    header("location:../connexion.php");
    exit(); // pour ne pas exécuter la suite du code - arrêt de l'exécution du script - injection de script impossible via GET
}

// AFFICHAGE TABLEAU
// Recherche d'un mot dans les avis
if(isset($_POST['recherche']))
{
    $terme = $_POST['recherche'];
    $terme = '%' . $terme . '%';
    // echo '<pre>'; print_r($_POST['recherche']); echo '</pre>';

    // requete de recherche
    $req_avis = $pdo->prepare("SELECT * FROM avis, membre, salle WHERE avis.id_membre = membre.id_membre AND avis.id_salle = salle.id_salle AND ((avis.commentaire LIKE :terme) OR (membre.email LIKE :terme) OR (salle.titre = :terme))");
    $req_avis->bindParam(":terme", $terme, PDO::PARAM_STR);
    $req_avis->execute();

}
else {
    // Requete sur toutes les données des tables avis, membre et salle si pas de recherche en cours
    $req_avis = $pdo->query("SELECT * FROM avis, membre, salle WHERE avis.id_membre = membre.id_membre AND avis.id_salle = salle.id_salle");
}

// SUPPRESSION D'UN COMMENTAIRE - dans le cas d'une modération
if (isset($_GET['action']) && $_GET['action'] == 'suppr' && isset($_GET['id_avis'])) {
    //$message .= '<div class="alert alert-warning" role="alert" style="margin-top: 20px;">Suppression d\'un commentaire.</div>';

    // demande de confirmation - /!\ A FAIRE
    $confirme_suppression = false;
    // affichage du message de confirmation - /!\ A FAIRE

    // requete de suppression
    $req_suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
    $req_suppression->bindParam(":id_avis", $_GET['id_avis'], PDO::PARAM_STR);
    $req_suppression->execute();

    // rechargement de la page
    header("location:gestion_avis.php");
}

// MODIFICATION d'UN COMMENTAIRE - dans le cas d'une modération
if (isset($_GET['action']) && $_GET['action'] == 'modif' && isset($_GET['id_avis'])) {
    // on ne peut modifier que le commentaire
    $varAvis = $_GET['id_avis'];
    $req_comm = $pdo->query("SELECT * FROM avis WHERE id_avis = $varAvis");
    $comm = $req_comm->fetch(PDO::FETCH_ASSOC);
    //echo '<pre>'; print_r($comm); echo '</pre>';
   
    if(isset($_POST['commentaire'])) {    
        // requete de modification
        $modif = $_POST['commentaire'] . '<p>modifié par la modération</p>';
        $req_modif = $pdo->prepare("UPDATE avis SET commentaire = :modif WHERE id_avis = :avis");
        $req_modif->bindParam(':modif', $modif, PDO::PARAM_STR);
        $req_modif->bindParam(':avis', $varAvis, PDO::PARAM_STR);
        $req_modif->execute();

        header('location:gestion_avis.php');
    }
}




// la ligne suivante commence les affichages dans la page
require("../inc/header.inc.php");
require("../inc/nav.inc.php");
?>

    <div class="container">

        <div class="starter-template">
            <h1><span class="glyphicon glyphicon-user"></span> Gestion des avis</h1>
            <?php echo $message; ?>
        </div>


        <div class="row">

            <!-- formulaire de recherche -->
            <div class="col-sm-3">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="recherche" class="form-control" >Rechercher un mot</label>
                        <input type="text" name="recherche" id="recherche" class="form-control" />
                        <input type="submit" value="rechercher" class="btn btn-primary" />
                    </div>
                </form>
            </div>

            <!-- formulaire de confirmation de suppression
            <div class="col-sm-3">
                <form action="" method="post" style="display: none" >
                        <input type="submit" class="btn btn-danger" id="confirm" value="Confirmer la suppression">
                </form>
            </div> A voir plus tard -->




        </div><!-- fin ./row -->
        <hr />



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
                                        // echo $avis['note'];
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

        
        <!-- formulaire de modification / édition d'un commentaire -->
        <div class="row">
            <div class="col-sm-3">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="commentaire" class="form-control" >Commentaire</label>
                        <textarea name="commentaire"  class="form-control" id="commentaire"><?php if(isset($comm['commentaire'])) {echo $comm['commentaire'];} ?></textarea>
                        <input type="submit" value="valider" class="btn btn-primary form-control" />
                    </div>
                </form>
            </div>
        </div><!-- fin .row -->




    </div><!-- /.container -->

<?php
    require("../inc/footer.inc.php");