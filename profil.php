<?php
require("inc/init.inc.php");
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
// print_r($_SESSION['utilisateur']['pseudo']);

// vérifier si le user est connect sinon on redirige vers connexion
if(!utilisateur_est_connecte())
{
    header("location:connexion.php");
}

$id_membre           = $_SESSION['utilisateur']['id_membre'];
$pseudo              = $_SESSION['utilisateur']['pseudo'];
$nom                 = $_SESSION['utilisateur']['nom'];
$prenom              = $_SESSION['utilisateur']['prenom'];
$email               = $_SESSION['utilisateur']['email'];
$civilite            = $_SESSION['utilisateur']['civilite'];
$statut              = $_SESSION['utilisateur']['statut'];
$date_enregistrement = $_SESSION['utilisateur']['date_enregistrement'];

if($statut == 1)
{
    $role = 'Administrateur';
}
else
{
    $role = 'Membre';
}

// la ligne suivante commence les affichages dans la page
require("inc/header.inc.php");
require("inc/nav.inc.php");
?>
    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user" style="color: plum;"></span> Profil (<?php echo $role; ?>)</h1>
        <?php // echo $message; // message destiné à l'utilisateur ?>
        <?= $message; // =echo en php ?>
        <?php ?>
      </div>

      <div class="row">
        <div class="col-sm-6">
            <ul>
                <li><?php echo $pseudo ?></li>
                <li><?php echo $nom ?></li>
                <li><?php echo $prenom ?></li>
                <li><?php echo $email ?></li>
                <li><?php echo $civilite ?></li>
                <li><?php echo $statut ?></li>
                <li><?php echo $date_enregistrement ?></li>
            </ul>
        </div>
        <div class="col-sm-6">
            <ul>
                <li><img src="" alt=""></li>
            </ul>
        </div>
      </div>
      
      
      <!-- tout sauf le mdp et le statut par contre afficher si membre ou admin -->
      



    </div><!-- /.container -->

<?php
require("inc/footer.inc.php");


