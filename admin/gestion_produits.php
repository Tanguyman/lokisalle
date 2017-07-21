<?php
// TEMPLATE A REPRENDRE SUR TOUTES LES PAGES DU SITE !!
require("../inc/init.inc.php");

// restriction d'acces, si l'utilisateur n'est pas admin alors il ne doit pas acceder à cette page
if(!utilisateur_est_admin())
{
  header("location:../connexion.php");// ? ../connexion.php
  exit(); // permet d'arreter l'éxecution du script au où une pers malveillante ferait des injections via GET
  /*-------------------------*\ 
        Pour Vérifier taper l'url suivant:
        http://localhost:8888/php/php_11_lokisalle/admin/gestion_produits.php
  \*-------------------------*/
}

/**********************************************
          
          // SUPRIMER UN PRODUIT "disponibilité"

/**********************************************/
// TODO


// DECLARATION DES VARIABLES
$id_produit   = "";
$id_salle     = "";
$date_arrivee = "";
$date_depart  = "";
$prix         = "";
$etat         = ""; // enum: libre, reservation

$salles       = ""; // Pour Afficher le <table>


// ATTENTION A BIEN DECLARER LA VARIABLE ERREUR
$erreur = '';
$message = "";

/*****************************************************************

      // RECUPERATION DES INFOS D'UNE SALLE 
      
       ?? A MODIFIER ??

/*****************************************************************/
//TODO
// TESTER LA REQUETE DANS phpmyadmin
//$tab_salles = $pdo->query("SELECT produit.*, salle.titre, salle.photo FROM salle, produit WHERE salle.id_salle = produit.id_salle");
$tab_salles = $pdo->query("SELECT produit.id_produit, produit.date_arrivee, produit.date_depart, produit.id_salle, produit.prix, produit.etat, salle.titre, salle.photo, salle.adresse FROM salle, produit WHERE salle.id_salle = produit.id_salle");

$salles .= '<table class="table table-bordered">';
$salles .= '<tr>';

// récupération du nb de colonnes dans la requete
// POURQUOI ???
// si on ajoute des colonnes dans la table => ce sera dynamique ;) => en l'occurence ça sert à rien :(
$nb_col = $tab_salles->columnCount();

for($i = 0; $i < $nb_col; $i++)
{
    $colonne = $tab_salles->getColumnMeta($i);
    if($colonne['name'] != 'titre' && $colonne['name'] != 'photo')
     {
         $salles .= '<th style="padding: 10px">' . $colonne['name']  . '</th>';
     }
}

$salles .= '<th>Actions</th>';
$salles .= '</tr>';

while($ligne = $tab_salles->fetch(PDO::FETCH_ASSOC))
{
    echo '<pre><p>WHILE fetch_assoc</p>'; var_dump($ligne); echo '</pre>';
    $salles .= '<tr>';
      
    foreach($ligne AS $indice => $info )
    {
        echo '<pre><p>FOREACH fetch_assoc</p>'; var_dump($info); echo '</pre>';
        if($indice == 'id_produit')
        {
          $salles .= '<td style="padding: 10px">' . $info . ' </td>';
        }
        elseif($indice == 'date_arrivee')
        {
          $salles .= '<td style="padding: 10px">' . $info . ' </td>';
        }
        elseif($indice == 'date_depart')
        {
          $salles .= '<td style="padding: 10px">' . $info . ' </td>';
        }
        elseif($indice == 'id_salle')
        {
            $salles .= '<td style="padding: 10px">' . $info . ' - ' . $ligne['titre'] . '<br>';
            $salles .= '<img src="' . URL . 'photo/' . $ligne['photo'] . '" class="img-thumbnail" width="100" /></td>';
        }
        elseif($indice == 'prix')
        {
          $salles .= '<td style="padding: 10px">' . $info . ' €</td>';
        }
        elseif($indice == 'etat')
        {
          $salles .= '<td style="padding: 10px">' . $info . ' </td>';
        }
        
     
    }
    
    
    $salles .= '<td><a href="?action=modification&id_produit=' . $ligne['id_produit'] .'" class="btn btn-warning"><span class="glyphicon glyphicon-refresh"></span></a> <a onclick="return(confirm(\'Etes vous sûr(e)\'));" href="?action=suppression&id_produit=' . $ligne['id_produit'] .'" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></a></td>';

     
    $salles .= '</tr>';
}           
$salles .= '</table>';



// <option>Chemise</option>
/* <option <?php echo 'selected'; } ?> >Accessoires</option> */
/*****************************************************************/ 

                // ENREGISTREMENT DES Disponibilité + Prix

/*****************************************************************/ 
$afficher_option = "";
$options = $pdo->query("SELECT id_salle, titre, adresse, cp, ville, capacite FROM salle");
while($option = $options->fetch(PDO::FETCH_ASSOC))
{
    $afficher_option .= '<option>' . $option['id_salle'] . ' - ' . $option['titre'] . ' - ' . $option['adresse'] . ' - ' . $option['cp'] . ' - ' . $option['ville'] . ' - ' . $option['capacite'] . '</option>';
// echo '<pre><p>$option</p>'; var_dump($option); echo '</pre>';
}

if(isset($_POST["date_depart"]) && isset($_POST["date_arrive"]) && isset($_POST["salle"]) && isset($_POST["prix"]))
{
    // $id_produit   = $_POST["id_produit"];
    $date_arrivee = $_POST["date_arrivee"];
    $date_depart  = $_POST["date_depart"];
    $id_salle     = $_POST["salle"]; // 
    $prix         = $_POST["prix"];
}

// if(isset($_POST["id_article"]) && isset($_POST["reference"]) && isset($_POST["categorie"]) && isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["couleur"]) && isset($_POST["taille"]) && isset($_POST["sexe"]) && isset($_POST["prix"]) && isset($_POST["stock"]))
// {
//     $id_produit   = $_POST["id_produit"];
//     $id_salle     = $_POST["id_salle"];
//     $date_arrivee = $_POST["date_arrivee"];
//     $date_depart  = $_POST["date_depart"];
//     $prix         = $_POST["prix"];
//     $etat         = $_POST["etat"];



//   // CONTROLER SI LA REFERENCE DU PRODUIT EXISTE DANS LA BDD
//   // 2 Si on est dans le cas d'un ajout car lors d 
//     $verif_ref_article = $pdo->prepare("SELECT * FROM article WHERE reference = :reference");//'544317001'
//     $verif_ref_article->bindParam(":reference", $reference, PDO::PARAM_STR);
//     $verif_ref_article->execute();

//     echo '<pre>'; var_dump($verif_ref_article->rowCount()); echo '</pre>';
// }



// la ligne suivante commence les affichages dans la page
require("../inc/header.inc.php");
require("../inc/nav.inc.php");
?>
<div class="container">

    <div class="starter-template">
        <h1>Gestion des disponibilités produit</h1>
        <?php //echo $message; // messages destinés à l'utilisateur ?>
        <?= $message; // cette balise php inclue un echo, elle est équivalente à la ligne du dessus ?>
        <?= $salles; // cette balise php inclue un echo, elle est équivalente à la ligne du dessus ?>
    </div>
    
     <div class='row'>
        <div class="col-sm-10 col-sm-offset-1"> 
            
            <form action="" method="post" enctype="multipart/form-data">

                <div class='row'>
                
                    <div class="col-sm-5 col-sm-offset-1">
                        <!-- <div class="form-group"> -->
                            <input type="hidden" name="id_article" id="id_article" value="<?php echo $id_article; ?>" class="form-control">
                        <!-- </div> -->
                        <div class="form-group">
                            <label for="date_depart">Date de départ<span class="text-danger">*</span></label>
                            <input type="text" name="date_depart" id="date_depart" class="form-control" value="<?php echo $date_depart; ?>">
                        </div>
                        <div class="form-group">
                            <label for="date_arrivee">Date d'arrivée</label>
                            <input type="text" name="date_arrivee" id="date_arrivee" class="form-control" value="<?php echo $date_arrivee; ?>">
                        </div>
                    </div><!--col-sm-5 col-sm-offset-1"-->

                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="salle">Salle</label>
                            <select type="text" name="salle" id="salle" class="form-control">
                                <?php echo $afficher_option; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prix">Tarif</label>
                            <input type="text" name="prix" id="prix" class="form-control" value="<?php echo $prix; ?>">
                        </div>
                    </div><!--col-sm-5-->
                </div><!--row-->
            
                <div class='row'>
                    <div class="col-sm-10 col-sm-offset-1"> 
                        <div class="form-group">
                            <button type="submit" name="inscription" id="inscription" class="form-control btn btn-info"><span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span> Enregistrer <span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span></button>
                        </div>
                    </div><!--col-sm-10 col-sm-offset-1-->
                </div><!--row-->

            </form>
        </div><!--col-sm-10 col-sm-offset-1-->
    </div><!--row-->

</div><!-- /.container -->

<?php
    require("../inc/footer.inc.php");