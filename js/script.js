// affichage conditionnel du formulaire d'ajout de salle + boutton masquer formulaire
document.getElementById('ajouter').addEventListener('click', function() {
    console.log('click');
    document.getElementById('formAjout').style.display = "block";
    document.getElementById('masquer').style.display = "inline-block";
});


// affichage conditionnel du formulaire lors d'une modification des informations de la salle

/* je n'arrive pas à récupérer l'évènement click sur le bouton id="modif" par cette methode */
var modif = document.getElementsByClassName('modif').length;
console.log(modif); // je récupère une liste de tous les élements du DOM de class="modif"
// comment savoir sur laquelle j'ai cliquée ? -> j'ai une id de type id-salle-X
var elementModif;
/* voir les fonction de récup de l'url : demain
for (i = 0; i < modif; i++) {

    elementModif = getElementById(i);
    if(elementModif == ){

    };

};
*/



// masquer le formulaire
document.getElementById('masquer').addEventListener('click', function() {
    console.log('mask');
    document.getElementById('formAjout').style.display = "none";
    document.getElementById('masquer').style.display = "none";
})