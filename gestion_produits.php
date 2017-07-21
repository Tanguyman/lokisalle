<?php
require("inc/init.inc.php");


// TEMPLATE A REPRENDRE SUR TOUTES LES PAGES DU SITE !!






// la ligne suivante commence les affichages dans la page
require("inc/header.inc.php");
require("inc/nav.inc.php");
?>
<div class="container">

    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user"></span> Profil</h1>
        <?php //echo $message; // messages destinés à l'utilisateur ?>
        <?= $message; // cette balise php inclue un echo, elle est équivalente à la ligne du dessus ?>
    </div>


    <div class='row'>
        <div class="col-sm-4 col-sm-offset-4 text-center">
        <span class="text-danger">*</span> champs obligatoires
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-10 col-sm-offset-1">
            <form action="" method="post" enctype="multipart/form-data">

            <div class='row'>
                <div class="col-sm-5 col-sm-offset-1">
            
                <!-- id_article = caché -->
                <?php echo $afficher_photo_actuelle; ?>

                <div class="form-group">
                    <label for="photo">photo</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>

                <div class="form-group">
                    <input type="hidden" name="id_article" id="id_article" value="<?php echo $id_article; ?>" class="form-control">
                </div>
                </div><!--col-sm-5 col-sm-offset-1"-->
        
                <div class="col-sm-5"> 
                <div class="form-group">
                    <label for="reference">Reference<span class="text-danger">*</span></label>
                    <input type="text" name="reference" id="reference" class="form-control" value="<?php echo $reference; ?>">
                </div>

                <div class="form-group">
                    <label for="prix">prix</label>

                    <input type="text" name="prix" id="prix" class="form-control" value="<?php echo $prix; ?>">
                </div>
                
                <div class="form-group">
                    <label for="stock">stock</label>
                    <input type="text" name="stock" id="stock" class="form-control" value="<?php echo $stock; ?>">
                </div>
                

                <div class="form-group">
                    <label for="titre">titre<span class="text-danger">*</span></label>
                    <input type="text" name="titre" id="titre" class="form-control" value="<?php echo $titre; ?>">
                </div>
                
                <div class="form-group">
                    <label for="categorie">categorie</label>
                    <select type="text" name="categorie" id="categorie" class="form-control">
                        <option>Chemise</option>
                        <option <?php if($categorie == "T-shirt") ?> >T-shirt</option>
                        <option <?php if($categorie == "Pantalon"){ echo 'selected'; } ?> >Pantalon</option>
                        <option <?php if($categorie == "Pull"){ echo 'selected'; } ?> >Pull</option>
                        <option <?php if($categorie == "Costume"){ echo 'selected'; } ?> >Costume</option>
                        <option <?php if($categorie == "Chaussure"){ echo 'selected'; } ?> >Chaussure</option>
                        <option <?php if($categorie == "Accessoires"){ echo 'selected'; } ?> >Accessoires</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="couleur" id="couleur">couleur</label>
                    <select name="couleur" id="couleur" class="form-control">
                        <option value="Blanc">Blanc</option>
                        <option value="Noir" <?php if($couleur == 'Noir'){ echo 'selected'; } ?> >Noir</option>
                        <option value="Bleu" <?php if($couleur == 'Bleu'){ echo 'selected'; } ?> >Bleu</option>
                        <option value="Rouge" <?php if($couleur == 'Rouge'){ echo 'selected'; } ?> >Rouge</option>
                        <option value="Jaune" <?php if($couleur == 'Jaune'){ echo 'selected'; } ?> >Jaune</option>
                        <option value="Gris" <?php if($couleur == 'Gris'){ echo 'selected'; } ?> >Gris</option>
                        <!-- Si il y a plusieurs champs option il faut les vérifier (if + echo 'selected')  -->
                        <!-- selected = checked pour les box -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="taille">taille</label>
                    <select name="taille" id="taille" class="form-control">
                        <option value="XL">XL</option>
                        <option value="S" <?php if($taille == "S"){ echo 'selected'; } ?> >S</option>
                        <option value="L" <?php if($taille == "L"){ echo 'selected'; } ?> >L</option>
                        <option value="2XL" <?php if($taille == "2XL"){ echo 'selected'; } ?> >2XL</option>
                        <option value="M" <?php if($taille == "M"){ echo 'selected'; } ?> >M</option>
                    </select>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sexe" id="sexe">Sexe</label>
                    <select name="sexe" id="sexe" class="form-control">
                        <option value="m">Homme</option>
                        <option value="f" <?php if($sexe == 'f'){ echo 'selected'; } ?> >Femme</option>
                        <!-- Si il y a plusieurs champs option il faut les vérifier (if + echo 'selected')  -->
                        <!-- selected = checked pour les box -->
                    </select>
                </div>

                </div>

            <div class='row'>
                <div class="col-sm-10 col-sm-offset-1"> 
                <div class="form-group">
                    <label for="description">description</label>
                    <textarea name="description" id="description" class="form-control"><?php echo $description; ?></textarea>
                    <!-- ne pas mettre </textarea> à la ligne car cela crée des espaces-->
                </div>
                </div><!--col-sm-10 col-sm-offset-1-->
            </div><!--row-->
                
            <div class='row'>
                <div class="col-sm-10 col-sm-offset-1"> 
                <div class="form-group">
                    <button type="submit" name="inscription" id="inscription" class="form-control btn btn-info"><span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span> Inscription <span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span><span class="glyphicon glyphicon-star" style="color: red;"></span></button>
                </div>
                </div><!--col-sm-10 col-sm-offset-1-->
            </div><!--row-->
            
            
            </form><!--col-sm-10 col-sm-offset-1-->
        </div>
    </div>

</div><!-- /.container -->

<?php
    require("inc/footer.inc.php");