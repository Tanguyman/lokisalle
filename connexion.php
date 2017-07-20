<?php
require("inc/init.inc.php");

// destruction de la session quand on clique sur deconnexion (deconnexion de l'utilisateur)
// echo '<pre>'; print_r($_GET); echo '</pre><hr />';
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    session_destroy();
}

// vérification si l'utilisateur est connecté, sinon redirection
if(utilisateur_est_connecte())
{
    header("location:profil.php");
}

// vérification de l'existence des indices du formulaire
if(isset($_POST['pseudo']) && isset($_POST['mdp']))
{
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];
    // avec hachage du mdp, cf connexion_avec_mdp_hash.php sur le fichier de Mathieu
    
    $verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo AND mdp = :mdp");
    $verif_connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $verif_connexion->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $verif_connexion->execute();


    if($verif_connexion->rowCount() > 0)
    {
        // si je récupère une ligne alors le pseudo et mdp sont corrects
        $info_utilisateur = $verif_connexion->fetch(PDO::FETCH_ASSOC);


        // on place toutes les informations de l'utilisateur dans la session sauf le mdp
        $_SESSION['utilisateur'] = array();
        $_SESSION['utilisateur']['id_membre'] = $info_utilisateur['id_membre'];
        $_SESSION['utilisateur']['pseudo'] = $info_utilisateur['pseudo'];
        $_SESSION['utilisateur']['nom'] = $info_utilisateur['nom'];
        $_SESSION['utilisateur']['prenom'] = $info_utilisateur['prenom'];
        $_SESSION['utilisateur']['email'] = $info_utilisateur['email'];
        $_SESSION['utilisateur']['sexe'] = $info_utilisateur['sexe'];
        $_SESSION['utilisateur']['ville'] = $info_utilisateur['ville'];
        $_SESSION['utilisateur']['cp'] = $info_utilisateur['cp'];
        $_SESSION['utilisateur']['adresse'] = $info_utilisateur['adresse'];
        $_SESSION['utilisateur']['statut'] = $info_utilisateur['statut'];

        // on redirige sur profil
        header("location:profil.php");

        // même chose avec un foreach:
        /*
        $_SESSION['utilisateur'] = array();
        foreach($info_utilisateur AS $indice => $valeur)
        {
            if($indice != 'mdp')
            {
                $_SESSION['utilisateur'][indice] = $valeur;
            }
        }*/

    }
    else{
            $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, erreur sur le pseudo ou le mot de passe.<br />Veuillez vérifier vos saisies.</div>';
    }

} //fin if(isset)







// la ligne suivante commence les affichages dans la page
require("inc/header.inc.php");
require("inc/nav.inc.php");
echo '<pre>'; print_r($_SESSION); echo '</pre>';
// echo '<pre>'; print_r($_SERVER); echo '</pre>';

?>



    <div class="container">
        <div class="starter-template">
            <?php //echo $message; // messages destinés à l'utilisateur ?>
            <?= $message; // cette balise php inclue un echo, elle est équivalente à la ligne du dessus ?>
        </div>

        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" class="form-control" name="pseudo" id="pseudo" value="" />
                    </div>
                    <div class="form-group">
                        <label for="mdp">Mot de passse</label>
                        <input type="text" class="form-control" name="mdp" id="mdp" value="" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="form-control btn btn-success" name="connexion" id="connexion" value="Connexion" />
                    </div>
                </form>
            </div><!-- fin row-->
        </div>

    </div><!-- /.container -->

    <?php
    require("inc/footer.inc.php");