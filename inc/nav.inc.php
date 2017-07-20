    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo URL; ?>boutique.php">Site Ma Boutique</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">

            <li class=""><a href="<?php echo URL; ?>boutique.php">Accueil</a></li>
            <li class=""><a href="<?php echo URL; ?>panier.php">Panier</a></li>


            <?php 
              // mode d'écriture (pratique: façon éclatée) pour personnalité les liens du menu
              if(!utilisateur_est_connecte())
              {
            ?>

                <li class=""><a href="<?php echo URL; ?>inscription.php">Inscription</a></li>
                <li class=""><a href="<?php echo URL; ?>connexion.php">Connexion</a></li>
                
            <?php
              }
              else
              {
            ?>

                <li class=""><a href="<?php echo URL; ?>profil.php">Profil</a></li>
                <li class=""><a href="<?php echo URL; ?>connexion.php?action=deconnexion">Deconnexion</a></li>

            <?php
              }
              // rajout des liens d'administration si utilisateur est admin
              if(utilisateur_est_admin())
              {
                echo '<li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration<span class="caret"></span></a>';
                echo '<ul class="dropdown-menu">';

                  echo '<li><a href="' . URL . 'admin/gestion_boutique.php">Gestion boutique</a></li>';
                  echo '<li><a href="' . URL . 'admin/gestion_commandes.php">Gestion commande</a></li>';
                  echo '<li><a href="' . URL . 'admin/gestion_utilisateur.php">Gestion utilisateur</a></li>';

                echo '</ul></li>';
              }






            ?>



          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>