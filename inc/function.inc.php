<?php

// fonction de check du password
// cette fonction renvoi FALSE si le mot de passe fait moins de 8 caractères, s'il ne contient pas au moins 1 majuscule, 1 minuscule et 1 caractère spécial défini dans l'expression régulière => '/[!@#$%^&*()\-_=+{};:,<.>]/'. Si tout est ok => TRUE
// Adapter au besoin
	
function checkPassword($password) {
   $reg1='/[A-Z]/';  // majuscule
   $reg2='/[a-z]/';  // minuscule
   //$reg3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // caractère spécial
   $reg4='/[0-9]/';  // chiffre

   // preg_match_all() compte le nombre d'occurence d'une expression régulière et place chaque occurence dans un tableau array défini en 3 ème argument (ici $tab) et renvoi un INT représentant le nombre d'occurence total.
   if(preg_match_all($reg1,$password, $tab)<1) return FALSE; // check si au moins une majuscule

   if(preg_match_all($reg2,$password, $tab)<1) return FALSE; // check si au moins une minuscule

   //if(preg_match_all($reg3,$password, $tab)<1) return FALSE; // check si au moins un caractère spécial

   if(preg_match_all($reg4,$password, $tab)<1) return FALSE; // check si au moins un chiffre

   if(mb_strlen($password, 'utf-8')<8) return FALSE; // check si taille inférieure à 8
   
   return TRUE;
}

// fonction pour savoir si un utilisateur est connecté
function utilisateur_est_connecte()
{
    if(isset($_SESSION['utilisateur']))
    {
        // si l'indice utilisateur existe alors l'utilisateur est connecté car il est passé par la page de connexion
        return true; // on sort de la fonction et le return false en dessous ne sera pas pris en compte.
    }
    return false; // si on rentre pas dans le if, on retourne false.
}

// fonction pour savoir si un utilisateur est connecté mais aussi a la statut administrateur
function utilisateur_est_admin()
{
    if(utilisateur_est_connecte() && $_SESSION['utilisateur']['statut'] == 1)
    {
        return true;
    }
    return false;
}

// Création du panier
function creation_panier()
{
    if(!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_article'] = array();
        $_SESSION['panier']['prix'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['titre'] = array();
    }
}

// function ajouter un article au panier
function ajouter_article_au_panier($id_article, $prix, $quantite, $titre)
{
    // avant d'ajouter, on verifie si l'article n'est pas déjà présent. si c'est le cas, on ne fait que modifier sa quantité.
    $position = array_search($id_article, $_SESSION['panier']['id_article']);
    // array_search() permet de vérifier si une valeur se trouve dans un tableau array. si c'est le cas, on récupère l'indice correspondant.

    if($position !== FALSE) // article déjà présent !== (car indice zero serait interprété comme false avec !=)
    {
        $_SESSION['panier']['quantite'][$position] += $quantite;
    }
    else{
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['id_article'][] = $id_article;
        $_SESSION['panier']['prix'][] = $prix;
        $_SESSION['panier']['titre'][] = $titre;
    }
}

// retirer un article du panier
function retirer_article_du_panier($id)
{
    $position = array_search($id, $_SESSION['panier']['id_article']);
    // on vérifie si l'article est bien présent dans le panier et avec array_search on récupère son indice correqpondant. (FALSE sinon)
    if($position !== FALSE)
    {
        array_splice($_SESSION['panier']['id_article'], $position, 1); // array_splice va combler un trou en réorganiser le tableau
        array_splice($_SESSION['panier']['quantite'], $position, 1);
        array_splice($_SESSION['panier']['prix'], $position, 1);
        array_splice($_SESSION['panier']['titre'], $position, 1);

        // array_splice() permet de supprimer un élément dans un tableau et surtout de réordonner les indices afin de ne pas avoir de trou dans notre tableau.
        //array_splice(le_tableau_concerné, indice_à_supprimer, nb_elements_à_supprimer)
    }
}


// calcul du prix total (c'est le nombre de ligne qui compte, c'est pourquoi j'utilise 2 indices différents)
function montant_total()
{
    if(!empty($_SESSION['panier']['titre']))
    {
        $taille_tab = count($_SESSION['panier']['id_article']);
        $total = 0;
        for($i = 0; $i < $taille_tab; $i++)
        {
            $total += $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i];
        }
        return $total;
    }
}

/* fonction pour savoir si un utilisateur est connecté mais aussi a la statut administrateur
function utilisateur_est_admin_test()
{
    if(utilisateur_est_connecte() && $_SESSION['utilisateur']['statut'] == 1)
    {
        $retour = array();
        $retour['statut'] = true;

        return true;
    }
    return false;
}
*/