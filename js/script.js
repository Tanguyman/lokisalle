// affichage conditionnel du formulaire d'ajout de salle + boutton masquer formulaire
document.getElementById('ajouter').addEventListener('click', function() {
    console.log('click');
    document.getElementById('formAjout').style.display = "block";
    document.getElementById('masquer').style.display = "inline-block";
});


// affichage conditionnel du formulaire lors d'une modification des informations de la salle

// récupération de l'url (dans le GET)
var url = window.location.href;
//console.log(url);

// récupération de l'id_salle= dans le l'url
var urlId = url.split("&");
var idSalle = urlId[1];
//console.log(idSalle);
//console.log(typeof idSalle);

// si on clique sur modifier, alors idSalle est défini
if (typeof idSalle != "undefined") {
    console.log("modif");
    document.getElementById(idSalle).addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('formAjout').style.display = "block";
        document.getElementById('masquer').style.display = "inline-block";
        document.getElementById('titreModif').style.display = "block";
    });
} else {
    console.log('pas modif');
}


// masquer le formulaire
document.getElementById('masquer').addEventListener('click', function() {
    console.log('mask');
    document.getElementById('formAjout').style.display = "none";
    document.getElementById('masquer').style.display = "none";
    document.getElementById('titreModif').style.display = "none";
})