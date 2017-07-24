// récupération de l'url (dans le GET) afin de savaoir dans quelle page nous nous trouvons
var url = window.location.href;
console.log(url);

// dans quelle page sommes nous ?
var page = window.location.pathname;
//console.log(page); // ex:   /lokisalle/admin/gestion_avis.php



// --------------------------- ajout_salle.php --------------------------- 
// on test si on est dans ajout salle .php
if(page.includes('gestion_salle.php')) {
    console.log('gestion_salle.php');
    // affichage conditionnel du formulaire d'ajout de salle + boutton masquer formulaire
    var testAjouter = document.getElementById('ajouter');
    if (typeof testAjouter != 'undefined' && testAjouter != null) {
        document.getElementById('ajouter').addEventListener('click', function() {
            document.getElementById('formAjout').style.display = "block";
            document.getElementById('masquer').style.display = "inline-block";
        });
    }

    // masquer le formulaire
    var testSupprimer = document.getElementById('masquer');
    if (typeof testSupprimer != 'undefined' && testSupprimer != null) {
        document.getElementById('masquer').addEventListener('click', function() {
            document.getElementById('formAjout').style.display = "none";
            document.getElementById('masquer').style.display = "none";
            document.getElementById('titreModif').style.display = "none";
        });
    }

    // affichage conditionnel du formulaire lors d'une modification des informations de la salle
    //if(url.includes("action=") && url.includes("&id_salle=")) {
        if(true) {
        // récupération de l'id_salle= dans l'url
        var urlId = url.split("&");
        var idSalle = urlId[1];
        console.log(urlId);
        console.log("idSalle: " + idSalle);
        //console.log(typeof idSalle);
 
 
        /*
        document.getElementById(idSalle).addEventListener('click', function(e) {
            e.preventDefault();
            // console.log(this);
            console.log('modifcation');
           
            document.getElementById('formAjout').style.display = "block";
            document.getElementById('masquer').style.display = "inline-block";
            document.getElementById('titreModif').style.display = "block";
            
        });

        */

        // REFAIRE EN AJAX pour affichage dynamique + controle sur bdd
        var boutonModif = document.getElementsByClassName("idSalle");
        //alert(boutonModif.length);
        for(var i = 0; i < boutonModif.length; i++)
        {
            boutonModif[i].addEventListener("click", function(e) {
                e.preventDefault();
                
                console.log('modification');
                //this.
                document.getElementById('formAjout').style.display = "block";
                document.getElementById('masquer').style.display = "inline-block";
                document.getElementById('titreModif').style.display = "block";
            });
        }
    } else {
        console.log('pas dans modif');
    }
}

// ------------------------- fin ajout_salle.php ------------------------- 


/*
// masquer le formulaire
var testSupprimer = document.getElementById('masquer');
if (typeof testSupprimer != 'undefined' && testSupprimer != null) {
    document.getElementById('masquer').addEventListener('click', function() {
        console.log('mask');
        document.getElementById('formAjout').style.display = "none";
        document.getElementById('masquer').style.display = "none";
        document.getElementById('titreModif').style.display = "none";
    })
}


/*
// demande de confirmation pour la suppression d'un commentaire
    // récupération de l'url (dans le GET)
    var url = window.location.href;
    //console.log(url);

    // récupération de l'id_avis= dans l'url
    var urlId = url.split("&");
    var idAvis = urlId[1];
    
    console.log(idAvis);

document.getElementById(idAvis).addEventListener('click', function() {
    alert('click de suppression');
});
*/
