//------------Allumer les phares ou eteindre----------------

function allumerPhares() {
  document.querySelector(".fond-ecran").src = "images/lightOn.png";
}

function eteindrePhares() {
  document.querySelector(".fond-ecran").src = "images/lightOff.png";
}

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
          document.getElementById("results").innerHTML = xhr.responseText; // Affichage des résultats
        } else {
          console.error("Une erreur est survenue.");
        }
      }
    };
    xhr.open("POST", "vote/vote.php", true);
    xhr.send(formData);
  });
