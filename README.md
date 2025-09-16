# 🚗 Blog Auto — Vote ta voiture préférée (Symfony + MongoDB)

Un mini-projet clair, propre et fun : les visiteurs votent pour leur voiture favorite.  
Back-end **Symfony**, base **MongoDB**, front **AJAX**, anti multi-vote avec **cookie** + index unique.  
➡️ **Prêt en 1 commande avec Docker** · **Déployable sur Heroku + Atlas**

---

## Ce que ça fait

-   Page d’accueil : bandeau héro, 6 voitures, **formulaire de vote**.
-   Clic “Valider” → **AJAX** → vote enregistré → **classement en direct**.
-   Anti triche simple : **cookie** `voter_id` (+ index unique Mongo).
-   Pas de JS ? Ça marche **quand même** (fallback POST + messages flash).

---

## Comment ça marche

-   **Controller** `HomeController` :
    -   `GET /` → affiche la page + pose `voter_id` si absent
    -   `POST /vote/submit` → vérifie CSRF, **inc** le compteur dans `votes`, **insère** le votant dans `voters`, renvoie `{ ok, total, items[] }`
-   **MongoDB** :
    -   `votes` : `{ car: "BMW M3", votes: 12 }`
    -   `voters` : `{ voterId, choice, at, ip, ua }` avec **index unique** sur `voterId`
-   **Front** : petit JS `fetch()` qui poste le formulaire et met à jour le DOM

---

## ⚙️ Variables d’environnement (les 4 clés)

> Utilisées via `config/services.yaml` → `MongoDB\Client: ['%env(string:MONGODB_DSN)%']`

```ini
MONGODB_DSN=mongodb://127.0.0.1:27017       # (local) ou mongodb+srv://USER:PASS@cluster/...?appName=... en production
MONGODB_DB=blog_auto
MONGODB_COLLECTION_VOTES=votes
MONGODB_COLLECTION_VOTERS=voters

APP_ENV=dev
APP_SECRET=dev_secret_please_change
```

---

## 🐳 Lancer en local avec Docker (Dockerfile + docker-compose.yml)

> Rappel : **docker-compose.yml = le plan**, **Dockerfile = la recette**.  
> Les **deux fichiers** sont déjà à la racine du projet.

### Prérequis

-   Docker Desktop installé (Windows/Mac) ou Docker Engine (Linux).

### Démarrer (build + run)

Dans un terminal **à la racine du projet** (là où se trouvent `Dockerfile` et `docker-compose.yml`) :

```bash
docker compose up --build
```
