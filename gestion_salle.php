<?php
require("inc/init.inc.php");


// controle de l'acces à la page -> réservé aux admins (status 1)
if(!utilisateur_est_admin())
{
    // /!\ activer la redirection à la fin du dev de cette page
    //header("location:../connexion.php");
    //exit(); // pour ne pas exécuter la suite du code - arrêt de l'exécution du script - injection de script impossible via GET
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, t\'es pas admin!</div>';
}

// affichage tableau
    // requete
    $req_table_salle = $pdo->query("SELECT * FROM salle");


// récup des données du formulaire pour insertion dans la bdd
// variables de récupération
$titre = "";
$description = "";
$photo_bdd = "";
$capacite = "";
$categorie = "";
$pays = "";
$ville = "";
$adresse = "";
$cp = "";

/////////////////////////////////////////////////AJOUT SALLE///////////////////////////////////////////////
if(isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['capacite']) && isset($_POST['categorie']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) && utilisateur_est_admin())
{
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $capacite = $_POST['capacite'];
    $categorie = $_POST['categorie'];
    $pays = $_POST['pays'];
    $ville = $_POST['ville'];
    $adresse = $_POST['adresse'];
    $cp = $_POST['cp'];


    // contrôles sur les champs

        //déclaration variable de controle
        $erreur = false;

        // vérification du champ titre (vide ou pas)
        if(empty($titre))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "titre" est obligatoire.</div>';
            $erreur = true;
        }

        // vérification du champ description
        if(empty($description))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "description" est obligatoire.</div>';
            $erreur = true;
        }

        // ------------------------------ vérification du champ photo ---------------------------------
            // vérification du chargement d'une image
            if(!empty($_FILES['photo']['name']))
            {
                $photo_bdd = $titre . '_' . $_FILES['photo']['name'];
            }
            else{
                $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">La photo de la salle est obligatoire.</div>';
                $erreur = true;
            }
            // vérification de l'extension du fichier: (acceptées: jpg / jpeg / png / gif)
                // on isole l'extension
                $extension = strrchr($_FILES['photo']['name'], '.');

                // on transforme $extension afin que tous les caractères soient en minuscule
                $extension = strtolower($extension);

                // on enlève le "."
                $extension = substr($extension, 1);

                // les extensions acceptées
                $tab_extensions_valides = array("jpg", "jpeg", "png", "gif");

                // comparaison de $extension avec celles autorisées
                $verif_extension = in_array($extension, $tab_extensions_valides);

        if($verif_extension && !$erreur)
        {
            // extension valide + pas d'erreur
            $photo_dossier = RACINE_SERVEUR . 'photo/' . $photo_bdd;

            copy($_FILES['photo']['tmp_name'], $photo_dossier);
            // copy() permet de copier un fichier depuis un emplacement fourni en pemier argument vers un emplacement fourni en deuxième argument.
        }
        elseif(!$verif_extension) {
            // si l'extension du fichier n'est pas valide
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Format de l\'image invalide.<br /> extension acceptées:  "jpg", "jpeg", "png", "gif"</div>';
            $erreur = true;
        }
        //----------------------------------------------------------------------------------------

        // vérification du champ capacité
        if(empty($capacite))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "capacité" est obligatoire.</div>';
            $erreur = true;
        }

        // vérification du champ catégorie
        if(empty($categorie))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "categorie" est obligatoire.</div>';
            $erreur = true;
        }

        // vérification du champ pays
        if(empty($pays))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "pays" est obligatoire.</div>';
            $erreur = true;
        }

        // vérification du champ ville
        if(empty($ville))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "ville" est obligatoire.</div>';
            $erreur = true;
        }

        // vérification du champ adresse
        if(empty($adresse))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "adresse" est obligatoire.</div>';
            $erreur = true;
        }

        // vérification du champ code postal
        if(empty($cp))
        {
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Le champ "code postal" est obligatoire.</div>';
            $erreur = true;
        }


    // insertion des données de la bdd pour affichage si il n'y a pas d'erreur
    $req_insertion_salle = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
    $req_insertion_salle->bindParam(':titre', $titre, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':description', $description, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':pays', $pays, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':ville', $ville, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':adresse', $adresse, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':cp', $cp, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':capacite', $capacite, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':categorie', $categorie, PDO::PARAM_STR);
    $req_insertion_salle->bindParam(':photo', $photo_bdd, PDO::PARAM_STR);
    $req_insertion_salle->execute();


} // fin des isset($_POST['qquechose'])
///////////////////////////////////////////////FIN AJOUT SALLE////////////////////////////////////////////





/////////////////////////////////////////////MODIFICATION SALLE/////////////////////////////////////////// A FAIRE
// quand on clique sur modifier, on doit récupérer toutes les valeurs de la salle et pré-remplir les champs du formulaire

// vérification de l'activation de l'option modifier
if(isset($_GET['action']) && $_GET['action'] == 'modif')
{
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">T\'as appuyé sur le bouton modif.</div>';
}



///////////////////////////////////////////FIN MODIFICATION SALLE/////////////////////////////////////////



/////////////////////////////////////////////SUPPRESSION SALLE///////////////////////////////////////////

// vérification de l'activation de l'option modifier
if(isset($_GET['action']) && $_GET['action'] == 'suppr')
{
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">T\'as appuyé sur le bouton suppr.</div>';

    // il serait bien d'avoir une fenetre de confirmation !

    // récup des infos de la photo pour vider le dossier photo
    $salle_a_supprimer = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $salle_a_supprimer->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
    $salle_a_supprimer->execute();

    $salle_a_suppr = $salle_a_supprimer->fetch(PDO::FETCH_ASSOC);
    echo '<pre>'; print_r($salle_a_suppr); echo '</pre>';

    // on vérifie si la photo existe
    if(!empty($salle_a_suppr['photo']))
    {
        // on verifie le chemin si le fichier existe
        $chemin_photo = RACINE_SERVEUR . 'photo/' . $salle_a_suppr['photo'];
        
        if(file_exists($chemin_photo))
        {
            unlink($chemin_photo);
            // $message .= "le chemin de la photo à supprimer: " . $chemin_photo;
        }
    } // fin vérif photo

    // requete de suppression
    $req_suppression_salle = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
    $req_suppression_salle->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
    $req_suppression_salle->execute();
    
    $message .= '<div class="alert alert-success" role="alert" style="margin-top: 20px;">Salle supprimée.</div>';

    header("location:gestion_salle.php?action=");

}



///////////////////////////////////////////FIN SUPPRESSION SALLE/////////////////////////////////////////









// la ligne suivante commence les affichages dans la page
require("inc/header.inc.php");
require("inc/nav.inc.php");
//echo '<pre>'; print_r($_POST); echo '</pre>';
//echo '<pre>'; print_r($_GET); echo '</pre>';
?>



    <div class="container">

        <div class="starter-template">
            <h1><span class="glyphicon glyphicon-user"></span> Gestion des salles</h1>
            <?= $message; ?>
        </div>


        <!-- tableau -->
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <?php 
                            // récupération du nombre de colonnes dans la requête:
                            $nb_col = $req_table_salle->columnCount();
                            for($i = 0; $i < $nb_col; $i++)
                            {
                                //echo '<pre>'; print_r($resultat->getColumnMeta($i)); echo '</pre><hr />';
                                $colonne = $req_table_salle->getColumnMeta($i); // on récupère les informations de la colonne en cours afin ensuite de demander le name
                                // echo '<pre>'; print_r($colonne); echo '</pre>';
                                echo '<th>' . $colonne['name'] . '</th>';
                            }
                            echo '<th>Actions</th>';
                        ?>
                    </thead>
                    <tbody>
                        <?php
                            // affichage des lignes
                            while($var = $req_table_salle->fetch(PDO::FETCH_ASSOC))
                            {
                                echo '<tr>';
                                    foreach($var AS $indice => $valeur)
                                    {
                                        if($indice == 'photo')
                                        {
                                            echo '<td><img src="' . URL . 'photo/' . $valeur . '" width="100" alt="test" /></td>';
                                        }
                                        else
                                        {
                                            echo '<td>' . $valeur . '</td>';
                                        }

                                    }
                                    // boutons action
                                    echo '<td>';
                                        echo '<a href="?action=modif&id_salle=' . $var['id_salle'] . '" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>';
                                        echo '<a href="?action=suppr&id_salle=' . $var['id_salle'] . '" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>';
                                    echo '</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div><!-- fin .row -->
        <hr />


        <div class="row">
            <!-- Bouton ajouter une salle -->
            <button class="btn btn-primary" id="ajouter" >Ajouter une salle</button>

            <!-- Bouton masquer - apparait en même temps que le formulaire -->        
            <button class="btn btn-primary" id="masquer" style="display: none;">Masquer le formulaire</button>
        </div>

        <!-- formulaire d'ajout Affichage conditionnel -->
        <div class="row">

            <form action="" method="post" enctype="multipart/form-data" id="formAjout" style="display: none;">
            
                <div class="col-sm-6">

                    <div class="form-group">
                        <label for="titre">Titre<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" name="titre" id="titre" value="<?php echo $titre; ?>" placeholder="Titre de la salle" />
                    </div>

                    <div class="form-group">
                        <label for="description">Description<span style="color: red;">*</span></label>
                        <textarea name="description" class="form-control" id="description" ><?php echo $description; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="photo">Photo<span style="color: red;">*</span></label>
                        <input type="file" name="photo" id="photo" class="form-control"  />
                    </div>

                    <div class="form-group">
                        <label for="capacite">Capacité<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" name="capacite" id="capacite" value="<?php echo $capacite; ?>" placeholder="capacité de la salle" />  
                    </div>

                    <div class="form-group">
                        <label for="categorie">Catégorie<span style="color: red;">*</span></label>
                        <select name="categorie" id="categorie" class="form-control" >
                            <option value="reunion">Réunion</option>
                            <option value="bureau">Bureau</option>
                            <option value="formation">Formation</option>
                        </select>
                    </div>

                </div><!-- fin .col-sm-6 -->
            
                <div class="col-sm-6">

                    <div class="form-group">
                        <label for="pays">Pays<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" name="pays" id="pays" value="<?php echo $pays; ?>"  />
                    </div>
                    
                    <div class="form-group">
                        <label for="ville">Ville<span style="color: red;">*</span></label>
                        <select name="ville" id="ville" class="form-control" >
                            <option value="paris">Paris</option>
                            <option value="lyon">Lyon</option>
                            <option value="marseille">Marseille</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="adresse">Adresse<span style="color: red;">*</span></label>
                        <textarea name="adresse" class="form-control" id="adresse" value="<?php echo $adresse; ?>" ></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cp">Code postal<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" name="cp" id="cp" value="<?php echo $cp; ?>" />  
                    </div>
                    <input type="submit" class="form-control btn btn-primary" value="Valider">

                </div><!-- fin .col-sm-6 -->

            </form>

        </div><!-- fin .row -->

    </div><!-- /.container -->

    <?php
    require("inc/footer.inc.php");