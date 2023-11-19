//Allumer les phares ou eteindre

function allumerPhares() {
  document.querySelector(".fond-ecran").src = "images/lightOn.png";
}

function eteindrePhares() {
  document.querySelector(".fond-ecran").src = "images/lightOff.png";
}

//Navigation bouton burger

// Sélectionnez le logo burger bouton et la barre de navigation verticale
const logoBurgerButton = document.getElementById("logo-burger-button");
const verticalNavbar = document.getElementById("vertical-navbar");

// Créez une variable pour suivre l'état de la barre de navigation
let isNavbarVisible = false;

// Fonction pour ouvrir la barre de navigation
function openNavbar() {
  verticalNavbar.style.right = "0";
  isNavbarVisible = true;
}

// Fonction pour fermer la barre de navigation
function closeNavbar() {
  verticalNavbar.style.right = "-200px";
  isNavbarVisible = false;
}

// Sélectionnez le bouton "Fermer"
const closeButton = document.getElementById("close-button");

// Ajoutez un gestionnaire d'événement au bouton "Fermer"
closeButton.addEventListener("click", () => {
  closeNavbar();
});

// Ajoutez un gestionnaire d'événement au logo burger bouton
logoBurgerButton.addEventListener("click", () => {
  if (!isNavbarVisible) {
    openNavbar();
  } else {
    closeNavbar();
  }
});

//agrandir l'image
function agrandirImage(image) {
  image.classList.toggle("agrandie");
}

//Utilisation d'AJAX pour envoyer les données du formulaire sans recharger la page

document
  .getElementById("vote-form")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Empêche le comportement par défaut du formulaire

    var formData = new FormData(this); // Récupération des données du formulaire

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          document.getElementById("results").innerHTML = xhr.responseText; // Affichage des résultats
        } else {
          console.error("Une erreur est survenue.");
        }
      }
    };
    xhr.open("POST", "vote.php", true);
    xhr.send(formData);
  });

//Formulaire administrateur

const adminLink = document.querySelector(".admin");
const loginForm = document.querySelector(".login-form");

adminLink.addEventListener("click", function (event) {
  event.preventDefault();

  if (loginForm.style.display === "block") {
    loginForm.style.display = "none"; // Cache le formulaire s'il est déjà affiché
  } else {
    loginForm.style.display = "block"; // Affiche le formulaire s'il est caché
  }
});
