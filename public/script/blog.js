//------------Allumer les phares ou eteindre----------------

document.addEventListener("DOMContentLoaded", () => {
    const light = document.getElementById("car-light");
    if (!light) return;

    const ON = "/images/lightOn.gif";
    const OFF = "/images/lightOff.gif";

    document.querySelectorAll(".links a, .texte-bienvenue").forEach((el) => {
        el.addEventListener("mouseenter", () => (light.src = ON));
        el.addEventListener("mouseleave", () => (light.src = OFF));
    });
});

//-------------Navigation bouton burger---------------------

document.addEventListener("DOMContentLoaded", () => {
    const logoBurgerButton = document.getElementById("logo-burger-button");
    const verticalNavbar = document.getElementById("vertical-navbar");
    const closeButton = document.getElementById("close-button");

    let isNavbarVisible = false;

    function openNavbar() {
        verticalNavbar.style.display = "flex"; // Affiche la navbar
        isNavbarVisible = true;
    }

    function closeNavbar() {
        verticalNavbar.style.display = "none"; // Masque la navbar
        isNavbarVisible = false;
    }

    closeButton.addEventListener("click", closeNavbar);

    logoBurgerButton.addEventListener("click", (event) => {
        event.preventDefault(); // Évitez le comportement par défaut du lien
        if (!isNavbarVisible) {
            openNavbar();
        } else {
            closeNavbar();
        }
    });
});

//------------------Agrandir l'image-------------------------
function agrandirImage(image) {
    image.classList.toggle("agrandie");
}

//Utilisation d'AJAX pour envoyer les données du formulaire de vote sans recharger la page

document
    .getElementById("vote-form")
    .addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du formulaire

        var formData = new FormData(this); // Récupération des données du formulaire

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById("results").innerHTML =
                        xhr.responseText; // Affichage des résultats
                } else {
                    console.error("Une erreur est survenue.");
                }
            }
        };
        xhr.open("POST", "vote/vote.php", true);
        xhr.send(formData);
    });
