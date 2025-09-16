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

//------------- Navigation bouton burger ---------------------
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("logo-burger-button"); // bouton ☰
    const nav = document.getElementById("vertical-navbar"); // <nav id="vertical-navbar">
    const close = document.getElementById("close-button"); // bouton ✕

    if (!btn || !nav || !close) return; // sécurité minimale

    btn.addEventListener("click", (e) => {
        e.preventDefault();
        nav.classList.toggle("open"); // le CSS mobile affiche .open { display:flex }
    });

    close.addEventListener("click", () => {
        nav.classList.remove("open"); // ferme la barre
    });
});

//------------------Agrandir l'image-------------------------
function agrandirImage(image) {
    image.classList.toggle("agrandie");
}
window.agrandirImage = agrandirImage;

// ------------------------------------------------------------
// asynchrone + mongoDB + cookie
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
            });
            const json = await res.json();

            if (json.ok) {
                msgBox.innerHTML = `<div class="alert alert-success">${json.message}</div>`;
                renderRanking(json.items || [], json.total || 0);
            } else {
                msgBox.innerHTML = `<div class="alert alert-danger">${
                    json.message || "Erreur"
                }</div>`;
            }
        } catch (_) {
            msgBox.innerHTML = `<div class="alert alert-danger">Erreur</div>`;
        } finally {
            btn?.removeAttribute("disabled");
        }
    });

    function renderRanking(items, total) {
        listEl.innerHTML = items
            .map(
                (it, i) => `
      <li class="list-group-item d-flex justify-content-between align-items-center ${
          i === 0 ? "active" : ""
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
    }
});
