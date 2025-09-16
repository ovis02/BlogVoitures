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
window.agrandirImage = agrandirImage;
// ------------------------------------------------------------
// ------------------------------------------------------------
// ------------------------------------------------------------
// Vote asynchrone : message + CLASSEMENT SIMPLE
// Requiert dans le template :
//  - <form id="form-vote" data-endpoint="{{ path('app_vote_ajax') }}">
//  - <div id="message-vote"></div>
//  - <div id="classement-vote"><ol id="classement-list"></ol>...</div>
// La route app_vote_ajax renvoie: { ok, message, total, items:[{label,votes}] } triés DESC
// ------------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-vote");
    const msgBox = document.getElementById("message-vote");
    const wrap = document.getElementById("classement-vote");
    const listEl = document.getElementById("classement-list");
    const totalEl = document.getElementById("classement-total");
    if (!form || !msgBox || !wrap || !listEl || !totalEl) return;

    const endpoint = form.dataset.endpoint || form.action;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        msgBox.innerHTML = '<div class="alert alert-info">Envoi du vote…</div>';
        const btn = form.querySelector('button[type="submit"]');
        btn?.setAttribute("disabled", "disabled");

        try {
            const res = await fetch(endpoint, {
                method: "POST",
                body: new FormData(form),
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const json = await res.json();
            if (!res.ok || !json.ok) throw new Error(json.message || "Erreur");

            // message
            msgBox.innerHTML = `<div class="alert alert-success">${json.message}</div>`;

            // classement
            renderRanking(json.items || [], json.total || 0);

            // auto-hide message
            setTimeout(() => {
                const a = msgBox.querySelector(".alert");
                if (a) {
                    a.style.transition = "opacity .3s";
                    a.style.opacity = "0";
                    setTimeout(() => (msgBox.innerHTML = ""), 300);
                }
            }, 3000);
        } catch (err) {
            msgBox.innerHTML = `<div class="alert alert-danger">${
                err.message || "Une erreur est survenue."
            }</div>`;
        } finally {
            btn?.removeAttribute("disabled");
        }
    });

    function renderRanking(items, total) {
        // items déjà triés côté serveur (votes DESC)
        listEl.innerHTML = items
            .map(
                (it, idx) => `
      <li class="list-group-item d-flex justify-content-between align-items-center ${
          idx === 0 ? "active" : ""
      }">
        <span>${it.label}</span>
        <span class="badge bg-secondary rounded-pill">${it.votes} vote${
                    it.votes > 1 ? "s" : ""
                }</span>
      </li>
    `
            )
            .join("");
        totalEl.textContent = `${total} vote${total > 1 ? "s" : ""} au total`;
        wrap.style.display = "block";
        wrap.scrollIntoView({ behavior: "smooth", block: "center" });
    }
});
