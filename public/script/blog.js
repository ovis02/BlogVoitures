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
// Vote asynchrone : message + CLASSEMENT SIMPLE (robuste JSON)
// Requiert dans le template :
//  - <form id="form-vote" data-endpoint="{{ path('app_vote_ajax') }}">
//  - <div id="message-vote"></div>
//  - <div id="classement-vote"><ol id="classement-list"></ol>...</div>
// La route app_vote_ajax renvoie: { ok, message, total, items:[{label,votes}], detail? }
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

        // Timeout de sécurité (10s)
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), 10000);

        try {
            const res = await fetch(endpoint, {
                method: "POST",
                body: new FormData(form),
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
                redirect: "follow",
                signal: controller.signal,
            });

            // Si le serveur redirige (ex: vers /login), on l'affiche clairement
            if (res.redirected) {
                throw new Error(`Redirection inattendue vers ${res.url}`);
            }

            // Vérifie le Content-Type AVANT d'appeler res.json()
            const ct = res.headers.get("content-type") || "";
            if (!ct.includes("application/json")) {
                const text = await res.text();
                throw new Error(
                    `Réponse non-JSON (HTTP ${res.status}) : ${text.slice(
                        0,
                        200
                    )}`
                );
            }

            // Parse JSON
            const json = await res.json();

            // Gestion des statuts non-OK côté HTTP OU ok:false côté JSON
            if (!res.ok || !json.ok) {
                const extra = json.detail ? ` — ${json.detail}` : "";
                const base = json.message || `Erreur HTTP ${res.status}`;
                throw new Error(base + extra);
            }

            // Succès
            msgBox.innerHTML = `<div class="alert alert-success">${json.message}</div>`;
            renderRanking(
                Array.isArray(json.items) ? json.items : [],
                Number(json.total) || 0
            );

            // Auto-hide message
            setTimeout(() => {
                const a = msgBox.querySelector(".alert");
                if (a) {
                    a.style.transition = "opacity .3s";
                    a.style.opacity = "0";
                    setTimeout(() => (msgBox.innerHTML = ""), 300);
                }
            }, 3000);
        } catch (err) {
            const isAbort =
                err &&
                (err.name === "AbortError" || /aborted/i.test(err.message));
            const msg = isAbort
                ? "Requête annulée (timeout)."
                : err?.message || "Une erreur est survenue.";
            msgBox.innerHTML = `<div class="alert alert-danger">${msg}</div>`;
            console.error(err);
        } finally {
            clearTimeout(timer);
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
        <span>${escapeHtml(it.label ?? "")}</span>
        <span class="badge bg-secondary rounded-pill">${
            Number(it.votes) || 0
        } vote${(Number(it.votes) || 0) > 1 ? "s" : ""}</span>
      </li>
    `
            )
            .join("");

        totalEl.textContent = `${total} vote${total > 1 ? "s" : ""} au total`;
        wrap.style.display = "block";
        wrap.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    // Petite fonction d'escape pour éviter une injection accidentelle dans le DOM
    function escapeHtml(str) {
        return String(str)
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }
});
