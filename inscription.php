<?php
require("inc/init.inc.php");

// vérification si l'utilisateur est connecté, sinon redirection
if(utilisateur_est_connecte())
{
    header("location:profil.php");
}

// déclaration des variables vide pour affichage dans les values du formulaire
$pseudo = "";
$mdp = "";
$nom = "";
$prenom = "";
$email = "";
$sexe = "";
$ville = "";
$cp = "";
$adresse = "";

if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['sexe']) && isset($_POST['ville']) && isset($_POST['cp']) && isset($_POST['adresse'])) // personne n'a manipulé notre formulaire
{
  // attribution des nouvelles valeurs: cf. value="" dans le formulaire
  // les champs se "souviennent" des valeurs si on recharge la page.
  // si le formulaire a été validé, on place dans ces variables les saisies correspondantes
  $pseudo = $_POST['pseudo'];
  $mdp = $_POST['mdp'];
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $sexe = $_POST['sexe'];
  $ville = $_POST['ville'];
  $cp = $_POST['cp'];
  $adresse = $_POST['adresse'];

  // variable de contrôle des erreurs
  $erreur = "";

  // contrôle sur la taille du pseudo (entre 4 et 14 caractères inclus)
  $taille_pseudo = iconv_strlen($pseudo);
  if($taille_pseudo < 4 || $taille_pseudo > 14)
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, la taille du pseudo est incorrecte.<br />En effet, le pseudo doit avoir entre 4 et 14 caractères inclus</div>';
    $erreur = true; // si l'on rentre dans cette condition alors il y a une erreur.
  }

  // contrôle des caractères dans le pseudo (autorisés: a-z A-Z 0-9 _ - .)
  $verif_caracteres = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
  /*
  // preg_match() va vérifier les caractères contenus dans la variable pseudo selon une expression régulière fournie en 1er argument.
  // renvoie 1 si tout est ok, sinon 0.
  
  // expression:
  // # => permet d'indiquer le début et la fin de l'expression
  // ^ => indique que la chaîne ($pseudo) ne peut que commencer par ces caractères.
  // $ => indique que la chaîne ($pseudo) ne peut que finir par ces caractères.
  // + => indique que les caractères autorisés peuvent apparaître plusieurs fois.
  // [] => contient les caractères autorisés.
  */
  if(!$verif_caracteres && !empty($pseudo)) // si false
  {
    // on rentre dans cette condition si $verif_caractère contient 0, cad si il y a des caractères non autorisés.
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, caractères non autorisés dans le pseudo.<br />Caractère autorisés: A-Z et 0-9</div>';
    $erreur = true;
  }

  // contrôle sur la validité du format de l'email et non vide
  if(!filter_var($email ,FILTER_VALIDATE_EMAIL) && !empty($email))
  {
    $message .= '<div class="alert alert-danger" role="alert" style="margin-top: 20px;">Attention, le format de votre email n\'est pas valide.<br />Veuillez saisir un email valide.</div>';
    $erreur = true;
  }

  // contrôle sur la disponibilité du pseudo en BDD
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

  // AUTRES CONTROLES - A FAIRE

  // insertion dans la BDD
  if($erreur !== true) // pas d'erreur dans le remplissage du formulaire
  {
    // pour crypter (hachage) le mdp
    // $mdp = password_hash($mdp, PASSWORD_DEFAULT);
    // avec hachage du mdp, cf connexion_avec_mdp_hash.php sur le fichier de Mathieu
    $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse, statut) VALUES(:pseudo, :mdp, :nom, :prenom, :email, :sexe, :ville, :cp, :adresse, 0)");
    $enregistrement->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $enregistrement->bindParam(":mdp", $mdp, PDO::PARAM_STR);
    $enregistrement->bindParam(":nom", $nom, PDO::PARAM_STR);
    $enregistrement->bindParam(":prenom", $prenom, PDO::PARAM_STR);
    $enregistrement->bindParam(":email", $email, PDO::PARAM_STR);
    $enregistrement->bindParam(":sexe", $sexe, PDO::PARAM_STR);
    $enregistrement->bindParam(":ville", $ville, PDO::PARAM_STR);
    $enregistrement->bindParam(":cp", $cp, PDO::PARAM_STR);
    $enregistrement->bindParam(":adresse", $adresse, PDO::PARAM_STR);
    $enregistrement->execute();

    // redirection sur la page connexion.php
    header("location:connexion.php"); // /!\ les header(location:chemin) peuvent masquer des erreurs, les mettres en commentaire en phase de débug.

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
                <label for="mdp">Mot de passe</label>
                <input type="text" class="form-control" name="mdp" id="mdp" value="<?php echo $mdp; ?>" />
              </div>
              <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" name="nom" id="nom" value="<?php echo $nom; ?>" />
              </div>
              <div class="form-group">
                <label for="prenom">Prenom</label>
                <input type="text" class="form-control" name="prenom" id="prenom" value="<?php echo $prenom; ?>" />
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" id="email" value="<?php echo $email; ?>" />
              </div>
              <div class="form-group">
                <label for="sexe">Sexe</label>
                <select class="form-control" name="sexe" id="sexe" />
                  <option value="m">Homme</option>
                  <option value="f" <?php if($sexe == 'f') { echo 'selected'; } // IMPORTANT - "m" en premier, condition uniquement sur f ?> >Femme</option>
                </select>
              <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" class="form-control" name="ville" id="ville" value="<?php echo $ville; ?>" />
              </div>
              <div class="form-group">
                <label for="cp">CP</label>
                <input type="text" class="form-control" name="cp" id="cp" value="<?php echo $cp; ?>" />
              </div>
              <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea class="form-control" name="adresse" id="adresse" value=""><?php echo $adresse; // dans textarea ?></textarea>
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