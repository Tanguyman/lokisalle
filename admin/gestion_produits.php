<?php
// TEMPLATE A REPRENDRE SUR TOUTES LES PAGES DU SITE !!
require("../inc/init.inc.php");

// restriction d'acces, si l'utilisateur n'est pas admin alors il ne doit pas acceder à cette page
if(!utilisateur_est_admin())
{
  header("location:../connexion.php");// ? ../connexion.php
  exit(); // permet d'arreter l'éxecution du script au cas où une pers malveillante ferait des injections via GET
  /*-------------------------*\ 
        Pour TRAVAILLER taper l'url suivant:
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

/**********************************************************************************************************************************\

                                                    B - AFFICHER TABLEAU
                                            
                                            => BDD = TABLE produit + TABLE salle
                                
                                            + AFFICHER la liste déroulante dans le formulaire (champ salle)

\**********************************************************************************************************************************/

    /*-----------------------------------------------------------------------*\
                                
                                B - AFFICHER TABLEAU

    \*-----------------------------------------------------------------------*/
        // TESTER LA REQUETE DANS phpmyadmin (ok)
            //$tab_salles = $pdo->query("SELECT produit.*, salle.titre, salle.photo FROM salle, produit WHERE salle.id_salle = produit.id_salle");
            // CORRECTION Alex + Mathieu (facilite l'affichage dans le tableau)
            $tab_salles = $pdo->query("SELECT produit.id_produit, produit.date_arrivee, produit.date_depart, produit.id_salle, produit.prix, produit.etat, salle.titre, salle.photo, salle.adresse FROM salle, produit WHERE salle.id_salle = produit.id_salle");

        // AFFICHER COLONNES
            $salles .= '<table class="table table-bordered">';
            $salles .= '<tr>';

            // récupération du nb de colonnes dans la requete
                // POURQUOI ???
                // si on ajoute des colonnes dans la table (via phpmyadmin) => ce sera dynamique ;)
                // => en l'occurence ça sert à rien :(
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

        // AFFICHER LIGNES
            while($ligne = $tab_salles->fetch(PDO::FETCH_ASSOC))
            {
                // echo '<pre><p>WHILE fetch_assoc</p>'; var_dump($ligne); echo '</pre>';
                $salles .= '<tr>';
                
                foreach($ligne AS $indice => $info )
                {
                    // echo '<pre><p>FOREACH fetch_assoc</p>'; var_dump($info); echo '</pre>';
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
            } // endwhile          
            $salles .= '</table>';

    /*-----------------------------------------------------------------------*\
                    
                    LISTE DEROULANTE (salle) DANS FORMULAIRE

    \*-----------------------------------------------------------------------*/
        $afficher_option = "";
        $options = $pdo->query("SELECT id_salle, titre, adresse, cp, ville, capacite FROM salle");
        while($option = $options->fetch(PDO::FETCH_ASSOC))
        {
            $afficher_option .= '<option>' . $option['id_salle'] . ' - ' . $option['titre'] . ' - ' . $option['adresse'] . ' - ' . $option['cp'] . ' - ' . $option['ville'] . ' - ' . $option['capacite'] . '</option>';
            // echo '<pre><p>$option</p>'; var_dump($option); echo '</pre>';
        }

/**********************************************************************************************************************************\

                                        A - INSERTION produit DANS LA TABLE (ok)
                                        ________________________________________

                                                        JE VEUX
                            si le client se trompe laisse la valeur des champs justes => vider les faux
                            si il réussi :
                                - afficher un message (ok)
                                - vider les champs

\**********************************************************************************************************************************/ 
/**********************************************************************************************************************************\
    
                                                    LES CONTROLES :
                                        ________________________________________

                                                    VERIFIER que :
            
                            1 - tous les champs sont remplis (ok)
                                2 - les dates sont des dates (ok)
                                    2.1 - date_arrivee > date_now (ok)
                                    2.2 - date_arrivee < date_depart (ok)
                                3 - les prix sont number ou integer ou float... (ok)

                            4 - il n'y a pas de chevauchement
                                CONTROLER SI id_produit EXISTE DEJA QUE:
                                    SI date_arrivee DES id ENTRANT < date_arrivee DEJA PRESENT
                                        ALORS date_depart ENTRANT < date_arrivee DEJA PRESENT (veille max)

                                    SI date_arrivee DES id ENTRANT > date_arrivee DEJA PRESENT
                                        ALORS date_arrivee ENTRANT > date_depart DEJA PRESENT (veille max)

        CONTROLER SI id_salle EXISTE DANS LA TABLE (salle ou produit ?)
            SUPER VERIF
            j'ai remplacer par 4 la valeur de <select id="salle">4</select>
            IMPOSSIBLE => cool ;)
            Warning: PDOStatement::execute(): SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`wf3_lokisalle`.`produit`, CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE) in /Applications/MAMP/htdocs/php/php_11_lokisalle/admin/gestion_produits.php on line 188
        
        CONTROLER SI date_arrive < date_depart au moins 1 jour de disponibilité
            donc date_depart - date_arrive > 1 jour
\**********************************************************************************************************************************/ 

/*-----------------------------------------------------------------------*\

1 - tous les champs sont remplis (ok)

\*-----------------------------------------------------------------------*/
if(isset($_POST["date_depart"]) && isset($_POST["date_arrivee"]) && isset($_POST["salle"]) && isset($_POST["prix"]) && !empty($_POST["date_depart"]) && !empty($_POST["date_arrivee"]) && !empty($_POST["salle"]) && !empty($_POST["prix"]))
{
    // $id_produit   = $_POST["id_produit"];
    $id_salle_full = $_POST["salle"]; // 
    $date_arrivee  = $_POST["date_arrivee"];
    $date_depart   = $_POST["date_depart"];
    $prix          = $_POST["prix"];

    // RECUPERER l'id_salle de l'id_salle_full
    $id_salle = substr($id_salle_full, 0, 1);

    // je controle si je recupere les données
    echo '<pre>'; var_dump($id_salle_full, $date_arrivee, $date_depart, $prix, $id_salle); echo '</pre>';

    /*-------------------------------------------------------------------*\
                                FONCTIONS REALISEES

                - valdateDate($date, $format = 'Y-m-d H:i:s')
                    => pour choisir un formta de sorite grâce à $format

                - compareDateToNow($date)
                    => tt est dans le nom ;)

    \*-------------------------------------------------------------------*/
    
        /*-----------------------------------------------------------------------*\
                                FONCTION VALIDE A TRANSFERER DANS function.inc.php
                            2 - les dates sont des dates (ok)
                                http://php.net/manual/fr/function.checkdate.php
                                https://www.alsacreations.com/tuto/lire/1407-formulaire-html5-type-date-time-local.html
        \*-----------------------------------------------------------------------*/
            function validateDate($date, $format = 'Y-m-d H:i:s')
            {
                $d = DateTime::createFromFormat($format, $date);
                return $d && $d->format($format) == $date;
            }
            // var_dump(validateDate('28/02/2012', 'd/m/Y')); // true
            // echo '<pre>$date_arrivee : '; var_dump(validateDate($date_arrivee, 'd-m-Y H:i')); // $format true = 28-02-2012 09:00 (ne pas oublier les 0)
            // echo '<br>$date_depart : '; var_dump(validateDate($date_depart, 'd-m-Y H:i')); echo '</pre>';
        /*-----------------------------------------------------------------------*\
                                
                                        ESSAI FONCTION
                                2.1 - date_arrivee < date_depart

        \*-----------------------------------------------------------------------*/
            // http://php.net/manual/fr/datetime.diff.php   
            function compareDateToNow($date)
            {
                // $date format = 28-02-2017 09:00 (n\'oubliez pas les 0 et les -)
                // function return = bool => true si $date > Now
                $date_now = new DateTime("now");
                // http://php.net/manual/fr/datetime.diff.php
                echo '<pre>';
                echo '<br>$date_now : '; var_dump($date_now);
                echo '<br>$date_now : '; var_dump($date_now->date);
                echo '<br>$date_arrivee_compare < $date_now : '; var_dump($date_arrivee_compare < $date_now->date);
                echo '<br>$date_arrivee_compare > $date_now : '; var_dump($date_arrivee_compare > $date_now->date);
                echo '<br>$date_depart_compare < $date_arrivee_compare : '; var_dump($date_depart_compare < $date_arrivee_compare);
                echo '<br>$date_depart_compare > $date_arrivee_compare : '; var_dump($date_depart_compare > $date_arrivee_compare);
                echo '</pre>';

                // VALIDER L'ARGUMENT date => son format
                // http://php.net/manual/fr/datetime.createfromformat.php
                function validateDate($date, $format = 'Y-m-d H:i:s')
                {
                    $d = DateTime::createFromFormat($format, $date);
                    return $d && $d->format($format) == $date;
                }
                if(!validateDate($date, 'd-m-Y H:i'))
                {
                    $message .= '<div class="row"><div class="col-sm-4 col-sm-offset-4 alert alert-danger" role="alert">Attention, vous devez respecter le format<br>Exemple : 28-02-2017 09:00 (n\'oubliez pas les 0 et les -)</div></div>';
                }

                if($date > $date_now->date ? "TRUE" : "FALSE");

            }

    /*-----------------------------------------------------------------------*\
                        
                        2 - les dates sont des dates (ok)
                        
    \*-----------------------------------------------------------------------*/
        if(!validateDate($date_arrivee, 'd-m-Y H:i') || !validateDate($date_depart, 'd-m-Y H:i'))
        {
            $message .= '<div class="row"><div class="col-sm-4 col-sm-offset-4 alert alert-danger" role="alert">Attention, vous devez respecter le format<br>Exemple : 28-02-2017 09:00 (n\'oubliez pas les 0 et les -)</div></div>';
            $erreur = true;
        }
        /*-----------------------------------------------------------------------*\
                            
                            2.1 - date_arrivee < date_depart (ok)

        \*-----------------------------------------------------------------------*/
        else
        {
            // rechanger le format date pour la class DateTime
                // http://php.net/manual/fr/datetime.createfromformat.php
                // $newDateArrivee = DateTime::createFromFormat('d-m-Y H:i', '28-02-2017 09:00');
                $newDateArrivee = DateTime::createFromFormat('d-m-Y H:i', $date_arrivee);
                $newDateDepart = DateTime::createFromFormat('d-m-Y H:i', $date_depart);
                $date_arrivee_compare = $newDateArrivee->format('Y-m-d');
                $date_depart_compare = $newDateDepart->format('Y-m-d');
                echo '<pre>$date_arrivee_compare : ' . $date_arrivee_compare . '<br>';
                echo '$date_depart_compare : ' . $date_depart_compare . '</pre>';

                $date_now = new DateTime("now");
                // http://php.net/manual/fr/datetime.diff.php
                echo '<pre>';
                echo '<br>$date_now : '; var_dump($date_now);
                echo '<br>$date_now : '; var_dump($date_now->date);
                echo '<br>$date_arrivee_compare < $date_now : '; var_dump($date_arrivee_compare < $date_now->date);
                echo '<br>$date_arrivee_compare > $date_now : '; var_dump($date_arrivee_compare > $date_now->date);
                echo '<br>$date_depart_compare < $date_arrivee_compare : '; var_dump($date_depart_compare < $date_arrivee_compare);
                echo '<br>$date_depart_compare > $date_arrivee_compare : '; var_dump($date_depart_compare > $date_arrivee_compare);
                echo '</pre>';
            // if($date_arrivee_compare < $date_now || $date_depart_compare < $date_arrivee_compare)
            if($date_arrivee_compare < $date_now->date)
            {
                $message .= '<div class="row"><div class="col-sm-4 col-sm-offset-4 alert alert-danger" role="alert">Attention, la date d\'arrivée doit être supérieur à aujourd\'hui.</div></div>';
                $erreur = true;
            }
        /*-----------------------------------------------------------------------*\
                            
                            2.2 - date_arrivee < date_depart (ok)

        \*-----------------------------------------------------------------------*/
            elseif($date_depart_compare < $date_arrivee_compare)
            {
                $message .= '<div class="row"><div class="col-sm-4 col-sm-offset-4 alert alert-danger" role="alert">Attention, la date de départ doit être superieur à la date d\'arrivée</div></div>';
                $erreur = true;
            }
            
        }
    /*-----------------------------------------------------------------------*\
    
                    3 - les prix sont number ou integer ou float...

    \*-----------------------------------------------------------------------*/
        if(!is_numeric($prix))
        {
            $message .= '<div class="row"><div class="col-sm-4 col-sm-offset-4 alert alert-danger" role="alert">Attention, le prix doit être un chiffre.<br>ET SI IL Y A UNE VIRGULE (,) VOUS DEVEZ LA REMPLaCER PAR UN POINT (.)</div></div>';
            $erreur = true;
        }

    /*-----------------------------------------------------------------------*\
                    
                    A - INSERTION produit DANS LA TABLE (ok)

    \*-----------------------------------------------------------------------*/
        if(!$erreur)
        {
            // $record_produit = $pdo->prepare('INSERT INTO produit (id_salle, date_arrivee, date_depart, prix) VALUES (:id_salle, :date_arrivee, :date_depart, :prix)'); // objet qui récupère ts les commentaires
            // $record_produit->bindParam(":id_salle", $id_salle_full, PDO::PARAM_STR);
            // $record_produit->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
            // $record_produit->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);
            // $record_produit->bindParam(":prix", $prix, PDO::PARAM_STR);
            // $record_produit->execute();
            $message .= '<div class="row"><div class="col-sm-4 col-sm-offset-4 alert alert-success" role="alert">Votre produit a bien été enregistré</div></div>';

        }
    
}
    /*-----------------------------------------------------------------------*\
                    
                    1 - tous les champs sont remplis (ok)
                    
    \*-----------------------------------------------------------------------*/
else
{
    $message .= '<div class="row"><div class="col-sm-4 col-sm-offset-4 alert alert-danger" role="alert">Attention, vous n\'avez renseigné tous les champs</div></div>';
    $erreur = true;
}



// la ligne suivante commence les affichages dans la page
require("../inc/header.inc.php");
require("../inc/nav.inc.php");
?>
<!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
    
                                            
                                     ___________________________________________________
                                   /                                                     \
                                  |     _____________________________________________     |
                                  |    |                                             |    |
                                  |    |  C:\> _                                     |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |             DEBUT AFFICHAGE HTML            |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |                                             |    |
                                  |    |_____________________________________________|    |
                                  |                                                       |
                                   \_____________________________________________________/
                                          \_______________________________________/
                                       _______________________________________________
                                    _-'    .-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.  --- `-_
                                _-'.-.-. .---.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.--.  .-.-.`-_
                            _-'.-.-.-. .---.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-`__`. .-.-.-.`-_
                            _-'.-.-.-.-. .-----.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-----. .-.-.-.-.`-_
                        _-'.-.-.-.-.-. .---.-. .-----------------------------. .-.---. .---.-.-.-.`-_
                        :-----------------------------------------------------------------------------:
                        `---._.-----------------------------------------------------------------._.---'                                            
                                                                    
    

    $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$-->
<div class="container">

    <div class="starter-template">
        <h1>Gestion des disponibilités produit</h1>
        <?php //echo $message; // messages destinés à l'utilisateur ?>
        <?= $message; // cette balise php inclue un echo, elle est équivalente à la ligne du dessus ?>
        <?= $salles; // cette balise php inclue un echo, elle est équivalente à la ligne du dessus ?>
    </div>
    
     <div class='row'>
        <div class="col-sm-10 col-sm-offset-1"> 
            
            <form action="../admin/gestion_produits.php" method="post" enctype="">

                <div class='row'>
                
                    <div class="col-sm-5 col-sm-offset-1">
                        <!-- <div class="form-group"> -->
                            <!-- <input type="hidden" name="id_article" id="id_article" value="<?php //echo $id_article; ?>" class="form-control"> -->
                        <!-- </div> -->
                        <div class="form-group">
                            <label for="date_arrivee">Date d'arrivée</label>
                            <input type="text" name="date_arrivee" id="date_arrivee" class="form-control" value="<?php echo $date_arrivee; ?>">
                        </div>
                        <div class="form-group">
                            <label for="text">Date de départ</label>
                            <input type="text" name="date_depart" id="date_depart" class="form-control" value="<?php echo $date_depart; ?>">
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