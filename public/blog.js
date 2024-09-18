//------------Allumer les phares ou eteindre----------------

function allumerPhares() {
  document.querySelector(".fond-ecran").src = "images/lightOn.png";
}

function eteindrePhares() {
  document.querySelector(".fond-ecran").src = "images/lightOff.png";
}

//-------------Navigation bouton burger---------------------

// Sélection du logo burger bouton et de la barre de navigation verticale
const logoBurgerButton = document.getElementById("logo-burger-button");
const verticalNavbar = document.getElementById("vertical-navbar");

// Création d'une variable pour suivre l'état de la barre de navigation
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

// Sélection du bouton "Fermer"
const closeButton = document.getElementById("close-button");

// Ajout d'un gestionnaire d'événement au bouton "Fermer"
closeButton.addEventListener("click", () => {
  closeNavbar();
});

// Ajout d'un gestionnaire d'événement au logo burger bouton
logoBurgerButton.addEventListener("click", () => {
  if (!isNavbarVisible) {
    openNavbar();
  } else {
    closeNavbar();
  }
});

//------------------Agrandir l'image-------------------------
function agrandirImage(image) {
  image.classList.toggle("agrandie");
}

//--------------------Formulaire administrateur-----------------------

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

//----------soumettre le formulaire avec ajax et confirmation---------------------------
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("comment-form");
  if (form) {
    form.addEventListener("submit", function (event) {
      event.preventDefault(); // Empêcher le comportement par défaut du formulaire

      console.log("Formulaire soumis");

      let formData = new FormData(this);

      fetch("comments.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Réponse reçue :", data);

          let messageDiv = document.getElementById("message");
          if (data.status === "success") {
            messageDiv.style.color = "orange";
          } else {
            messageDiv.style.color = "red";
          }
          messageDiv.innerHTML = data.message;
          messageDiv.style.display = "block";
        })
        .catch((error) => {
          console.error("Erreur lors de la soumission du formulaire :", error);
        });
    });
  } else {
    console.error("Le formulaire avec l'ID 'comment-form' est introuvable.");
  }
});
