<?php
require("inc/init.inc.php");

// vérification si l'utilisateur est connecté, sinon redirection
if(utilisateur_est_connecte())
{
    // redirection à activer vers page d'accueil, quand elle existera
    // header("location:profil.php");
}

// déclaration des variables vide pour affichage dans les values du formulaire
$pseudo = "";
$mdp = "";
$nom = "";
$prenom = "";
$email = "";
$civilite = "";


// controle de la présence des champs dans le formulaire d'inscription
if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite']))
{
  // attribution des nouvelles valeurs
  // les champs se "souviennent" des valeurs si on recharge la page.
  // si le formulaire a été validé, on place dans ces variables les saisies correspondantes
  $pseudo = $_POST['pseudo'];
  $mdp = $_POST['mdp'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $civilite = $_POST['civilite'];

  // variable de contrôle des erreurs
  $erreur = "";

//////////////////// PSEUDO //////////////////////////////////////////////

  // contrôle sur la taille du pseudo (entre 4 et 20 caractères inclus)
  $taille_pseudo = iconv_strlen($pseudo);
  if($taille_pseudo < 4 || $taille_pseudo > 20)
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, la taille du pseudo est incorrecte.<br />En effet, le pseudo doit avoir entre 4 et 20 caractères inclus</div>';
    $erreur = true; // si l'on rentre dans cette condition alors il y a une erreur.
  }

  // contrôle si le pseudo est unique
  $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $verif_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
  $verif_pseudo->execute();
  // une seule ligne || 0 en retour
  if($verif_pseudo->rowCount() > 0)
  {
    // si on obtient au moins 1 ligne de résultat alors le pseudo est déjà pris.
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, le pseudo n\'est pas disponible.<br />Veuillez vérifier vos saisies.</div>';
    $erreur = true;
  }

//////////////////////////// fin pseudo ///////////////////////////////////

// contrôles sur le mdp

  // contrôle sur la taille du mot de passe (entre 8 et 20 caractères inclus)
  $taille_mdp = iconv_strlen($mdp);
  if($taille_mdp < 8 || $taille_mdp > 20)
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, la taille du mot de passe est incorrecte.<br />En effet, le pseudo doit avoir entre 8 et 20 caractères inclus</div>';
    $erreur = true; // si l'on rentre dans cette condition alors il y a une erreur.
  }

  // vérificaction de la validité du mot de passe
  if(!checkPassword($mdp)) 
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, le mot de passe n\'est pas valable.</div>';
    $erreur = true;
  }


// contrôles su le nom
  // contrôle sur la taille du nom (entre 1 et 30 caractères inclus)
  $taille_nom = iconv_strlen($nom);
  if($taille_nom < 1 || $taille_nom > 30)
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, la taille du nom est incorrecte.<br />En effet, le nom doit avoir entre 1 et 30 caractères inclus</div>';
    $erreur = true; // si l'on rentre dans cette condition alors il y a une erreur.
  }

// contrôles sur le prenom
  // contrôle sur la taille du nom (entre 1 et 30 caractères inclus)
  $taille_prenom = iconv_strlen($prenom);
  if($taille_prenom < 1 || $taille_prenom > 30)
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, la taille du prénom est incorrecte.<br />En effet, le nom doit avoir entre 1 et 30 caractères inclus</div>';
    $erreur = true; // si l'on rentre dans cette condition alors il y a une erreur.
  }


// contrôle sur la validité du format de l'email et non vide
  if(!filter_var($email ,FILTER_VALIDATE_EMAIL) || empty($email))
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, le format de votre email n\'est pas valide.<br />Veuillez saisir un email valide.</div>';
    $erreur = true;
  }

  // insertion dans la BDD
  if($erreur !== true) // pas d'erreur dans le remplissage du formulaire
  {
    // pour crypter (hachage) le mdp
    // $mdp = password_hash($mdp, PASSWORD_DEFAULT);
    // avec hachage du mdp, cf connexion_avec_mdp_hash.php sur le fichier de Mathieu
    $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES(:pseudo, :mdp, :nom, :prenom, :email, :civilite, 0, NOW())");
    $enregistrement->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $enregistrement->bindParam(":mdp", $mdp, PDO::PARAM_STR);
    $enregistrement->bindParam(":nom", $nom, PDO::PARAM_STR);
    $enregistrement->bindParam(":prenom", $prenom, PDO::PARAM_STR);
    $enregistrement->bindParam(":email", $email, PDO::PARAM_STR);
    $enregistrement->bindParam(":civilite", $civilite, PDO::PARAM_STR);
    $enregistrement->execute();

    // redirection sur la page connexion.php
    // header("location:connexion.php"); // /!\ les header(location:chemin) peuvent masquer des erreurs, les mettres en commentaire en phase de débug.

  }







} // fin du if isset





// la ligne suivante commence les affichages dans la page
require("inc/header.inc.php");
require("inc/nav.inc.php");

// vérification des indices
echo '<pre>'; print_r($_POST); echo '</pre>';




?>



    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user"></span> Inscription</h1>
        <?php //echo $message; // messages destinés à l'utilisateur ?>
        <?= $message; // cette balise php inclue un echo, elle est équivalente à la ligne du dessus ?>
      </div>

      <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
        
          <form action="" method="post">
              <div class="form-group">
                <label for="pseudo">Pseudo<span style="color: red;">*</span></label>
                <input type="text" class="form-control" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" />
              </div>
              
              <div class="form-group">
                <label for="mdp">Mot de passe<span style="color: red;">*</span></label><br>
                <span>Le mot de passe doit contenir entre 8 et 20 caractères, avoir au moins une majuscule, une minuscule et un chiffre.</span>
                <input type="text" class="form-control" name="mdp" id="mdp" value="<?php echo $mdp; ?>" />
              </div>
              <div class="form-group">
                <label for="nom">Nom<span style="color: red;">*</span></label>
                <input type="text" class="form-control" name="nom" id="nom" value="<?php echo $nom; ?>" />
              </div>
              <div class="form-group">
                <label for="prenom">Prenom<span style="color: red;">*</span></label>
                <input type="text" class="form-control" name="prenom" id="prenom" value="<?php echo $prenom; ?>" />
              </div>
              <div class="form-group">
                <label for="email">Email<span style="color: red;">*</span></label>
                <input type="text" class="form-control" name="email" id="email" value="<?php echo $email; ?>" />
              </div>
              <div class="form-group">
                <label for="civilite">Civilité</label>
                <select class="form-control" name="civilite" id="civilite" />
                  <option value="m">Homme</option>
                  <option value="f" <?php if($civilite == 'f') { echo 'selected'; } // IMPORTANT - "m" en premier, condition uniquement sur f ?> >Femme</option>
                </select>
              </div>
              <div class="form-group">
                <input type="submit" class="form-control btn btn-success" name="inscription" id="inscription" value="Inscription" />
              </div>
              
          </form>

        </div>
      </div>
    

    </div><!-- /.container -->

    <?php
    require("inc/footer.inc.php");