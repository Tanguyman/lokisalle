// affichage conditionnel du formulaire d'ajout de salle + boutton masquer formulaire
document.getElementById('ajouter').addEventListener('click', function() {
    console.log('click');
    document.getElementById('formAjout').style.display = "block";
    document.getElementById('masquer').style.display = "inline-block";
});

// masquer le formulaire
document.getElementById('masquer').addEventListener('click', function() {
    console.log('mask');
    document.getElementById('formAjout').style.display = "none";
    document.getElementById('masquer').style.display = "none";
})