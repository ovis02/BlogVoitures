<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog Automobile</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Ingrid+Darling&family=Kameron&family=Libre+Franklin:wght@100&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="blog.css" />
  </head>
  <body>
  <a href="#" class="admin">Admin</a>
  <div class="login-form">
    <form action="verificationAdmin.php" method="POST">
      <input type="text" name="username" placeholder="Nom d'utilisateur">
      <input type="password" name="password" placeholder="Mot de passe">
      <button type="submit">Se connecter</button>
    </form>
  </div>
    <header>
     
             <nav>
              <img class="fond-ecran" src="images/lightOff.png" />

        <div class="plate-number">
          <div class="rectangle">
            <img src="images/france.png" alt="Image 1" />
          </div>
          <ul class="links">
            <li>
              <a
                href="#intro"
                onmouseover="allumerPhares()"
                onmouseout="eteindrePhares()"
                >Accueil</a
              >
            </li>
            <li>
              <a
                href="#voitures"
                onmouseover="allumerPhares()"
                onmouseout="eteindrePhares()"
                >Voitures</a
              >
            </li>
            <li>
              <a
                href="#vote"
                onmouseover="allumerPhares()"
                onmouseout="eteindrePhares()"
                >Vote</a
              >
            </li>
          </ul>
          <div
            class="texte-bienvenue"
            onmouseover="allumerPhares()"
            onmouseout="eteindrePhares()"
          >
            BIENVENUE
          </div>

          <div class="rectangle">
            <img src="images/75.png" alt="Image 2" />
          </div>
        </div>
        <div id="vertical-navbar" class="vertical-navbar">
          <a href="#intro">Accueil</a>
          <a href="#voitures">Voitures</a>
          <a href="#vote">Vote</a>
          <button id="close-button">Fermer</button>
        </div>
        <div class="burger-container">
          <a href="#" id="logo-burger-button">
            <img
              id="logo-burger"
              src="logos/burgerBlanc.png"
              alt="logo-burger"
              class="logo-burger"
          /></a>
        </div>
      </nav>
     
<!--.............................Introduction..............................-->
      <h1 id="intro">
        Légendes Automobiles:<br> Six voitures qui ont marqué l'Histoire
      </h1>
        <div class="login-form">
    <form action="process_login.php" method="POST">
      <input type="text" name="username" placeholder="Nom d'utilisateur">
      <input type="password" name="password" placeholder="Mot de passe">
      <button type="submit">Se connecter</button>
    </form>
  </div>
    </header>
    <main>
      <div class="introduction">
        <p>
          Bienvenue sur mon site dédié à l'excellence automobile, où je vous
          convie à découvrir et à apprécier six des voitures parmi les plus
          légendaires de tous les temps. Les amateurs de voitures du monde
          entier reconnaissent ces modèles emblématiques pour leur puissance,
          leur élégance et leur héritage inégalé. Parmi les modèles que j'ai
          sélectionnés, plongez dans la puissance sauvage de la Mustang GT, la
          performance inégalée de la Skyline GT-R, la sophistication de la
          Toyota Supra, l'icône sportive M3 E46, et la Porsche 911, intemporelle
          toutes années confondues. Explorez chaque modèle à travers des photos
          magnifiques qui captent l'essence de leur conception et de leur
          performance exceptionnelle. À la fin de votre exploration, je vous
          invite à exprimer votre choix en participant à mon système de vote.
          Partagez avec nous quelle voiture a capturé votre cœur et votre
          imagination.
        </p>
      </div>
    </main>
    <!--.......................................carSection.................................-->
    <section>
      <div class="voiture1">
        <article class="voiture">
          <img
            src="images/skyline.jpeg"
            alt="Voiture 1"
            onclick="agrandirImage(this)"
          />
        </article>
        <article class="description">
          <h2 id="voitures">Nissan Skyline GT-R 34</h2>
          <p>
            La Nissan Skyline GT-R R34, née à la fin des années 1990, est une
            voiture de sport japonaise qui a rapidement conquis les cœurs des
            amateurs de voitures. Sous son capot, un moteur turbo 2,6 litres
            offre une puissance impressionnante, tandis que sa transmission
            intégrale assure une tenue de route exceptionnelle. Son design, avec
            ses phares ronds caractéristiques, est devenu emblématique, et son
            allure musclée témoigne de sa performance. À l'intérieur, la R34
            offre un mélange de confort et de technologies avancées. C'est une
            voiture qui allie puissance, style intemporel et maniabilité, en
            faisant une légende de l'histoire automobile.
          </p>
        </article>
      </div>

      <div class="voiture2">
        <article class="voiture">
          <img
            src="images/supra.jpeg"
            alt="Voiture 2"
            onclick="agrandirImage(this)"
          />
        </article>
        <article class="description">
          <h2>Toyota Supra</h2>
          <p>
            La Toyota Supra, née dans les années 1970, a atteint son apogée avec
            la quatrième génération, notamment la Supra Mk4 des années 1990.
            Sous le capot, elle abrite un moteur turbo puissant, le 2JZ-GTE, qui
            lui confère une réputation légendaire. Ses lignes épurées et ses
            phares rétractables ajoutent à son charme intemporel. Sur la route,
            la Supra excelle avec sa transmission arrière et sa suspension
            sportive, offrant une expérience de conduite dynamique. À
            l'intérieur, l'accent est mis sur le conducteur, avec des
            caractéristiques sportives et des technologies avancées. La Toyota
            Supra, véritable icône, continue de susciter l'admiration des
            passionnés de voitures pour sa performance et son design distinctif.
          </p>
        </article>
      </div>

      <div class="voiture3">
        <article class="voiture">
          <img
            src="images/mustangGT.jpeg"
            alt="Voiture 3"
            onclick="agrandirImage(this)"
          />
        </article>
        <article class="description">
          <h2>Ford Mustang GT</h2>
          <p>
            La Ford Mustang GT, née dans les années 1960, a évolué avec grâce au
            fil des décennies. Les modèles plus récents, notamment après les
            années 2000, perpétuent l'héritage puissant de cette voiture de
            sport emblématique. Sous le capot, un V8 rugissant délivre des
            performances inégalées, tandis que son design audacieux et sa
            calandre distinctive incarnent l'esprit indomptable de la Mustang
            GT. À l'intérieur, elle marie confort et technologie pour une
            expérience de conduite inoubliable. La Ford Mustang GT moderne
            demeure une icône de la route, fusionnant le passé et le présent
            avec élégance et puissance..
          </p>
        </article>
      </div>
      <div class="voiture4">
        <article class="voiture">
          <img
            src="images/lancer.jpeg"
            alt="Voiture 4"
            onclick="agrandirImage(this)"
          />
        </article>
        <article class="description">
          <h2>Mitsubishi Lancer Evolution</h2>
          <p>
            La Mitsubishi Lancer Evolution, surnommée Evo, est une voiture de
            sport japonaise réputée pour ses performances de pointe depuis les
            années 1990. Avec un moteur turbocompressé sous le capot et un
            design distinctif, notamment son célèbre aileron arrière, la Lancer
            Evo offre une expérience de conduite sportive unique. À l'intérieur,
            elle allie fonctionnalité et confort, en faisant de cette voiture
            compacte une référence dans le monde des amateurs de conduite
            passionnée. La Lancer Evo, un nom qui résonne avec l'héritage de la
            course automobile.
          </p>
        </article>
      </div>
      <div class="voiture5">
        <article class="voiture">
          <img
            src="images/m3.jpeg"
            alt="Voiture 5"
            onclick="agrandirImage(this)"
          />
        </article>
        <article class="description">
          <h2>BMW M3 E46</h2>
          <p>
            La BMW M3 E46, née au début des années 2000, incarne l'essence de la
            voiture de sport allemande. Avec son moteur six cylindres en ligne
            et son design athlétique, elle offre une puissance remarquable et un
            style inoubliable. À l'intérieur, entre raffinement et performance,
            la M3 E46 demeure une icône intemporelle de l'ingénierie automobile
            allemande.Elle demeure une référence intemporelle dans le monde des
            voitures sportives.
          </p>
        </article>
      </div>

      <div class="voiture6">
        <article class="voiture">
          <img
            src="images/porsche.jpeg"
            alt="Voiture 6"
            onclick="agrandirImage(this)"
          />
        </article>
        <article class="description">
          <h2>Porsche 911</h2>
          <p>
            La Porsche 911, icône intemporelle depuis les années 1960, allie
            performance et élégance avec son moteur arrière, son design
            distinctif et ses performances remarquables. Dotée d'une gamme de
            motorisations puissantes, la 911 incarne le chic sportif, offrant
            une expérience de conduite inégalée. Avec son charme intemporel et
            son équilibre parfait entre style et puissance, la Porsche 911
            demeure une légende de l'automobile.
          </p>
        </article>
      </div>
    </section>
    <!---------------------------vote-form----------------------------------->
    <section>
      <div class="container" id="vote">
        <div class="vote-section">
          <h2>Quelle est votre voiture préférée ?</h2>
          <form action="vote.php" method="post" id="vote-form">
            <div class="vote-options">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="vote"
                  id="vote1"
                  value="Nissan Skyline GT-R"
                />
                <label class="form-check-label" for="vote1"
                  >Nissan Skyline GT-R</label
                >
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="vote"
                  id="vote2"
                  value="Toyota Supra"
                />
                <label class="form-check-label" for="vote2">Toyota Supra</label>
              </div>

              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="vote"
                  id="vote3"
                  value="Mustang GT"
                />
                <label class="form-check-label" for="vote3">Mustang GT</label>
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="vote"
                  id="vote4"
                  value="Mitsubishi Lancer Evo"
                />
                <label class="form-check-label" for="vote4"
                  >Mitsubishi Lancer Evo</label
                >
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="vote"
                  id="vote5"
                  value="BMW M3"
                />
                <label class="form-check-label" for="vote5">BMW M3</label>
              </div>
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="radio"
                  name="vote"
                  id="vote6"
                  value="Porsche 911"
                />
                <label class="form-check-label" for="vote6">Porsche 911</label>
              </div>
            </div>
            <button type="submit" class="submit-btn">Valider</button>
            <div class="container" id="results">
          <!--RESULTAT-->
           </div>           
        </div>
      </form>
     </div>
   </div>
</section>

    <!---------------------------Comment-form----------------------------------->
    <div class="comment-section">
      <h2>Laisser un commentaire</h2>
      <form action="comments.php" method="POST" id="comment-form" class="comment-form">
        <div class="form-group">
          <label for="name">Nom:</label>
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Votre nom"
            required  
          />
        </div>
        <div class="form-group">
          <label for="email">Adresse de messagerie:</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="Votre adresse email"
            required 
          />
        </div>
        <div class="form-group">
          <label for="comment">Commentaire:</label>
          <textarea
            class="form-control"
            id="comment"
            name="comment"
            rows="4"
            placeholder="Votre commentaire"
            required 
          ></textarea>
        </div>
        <button type="submit" class="submit-btn">Valider</button>
      </form>
       <!--Code pour la confirmation du commentaire envoyé-->
         <?php if (isset($_SESSION['message'])): ?>
        <div class="confirmation-message">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <!--------------------------------------------------------->
        <div class="approved-comments">
    <?php
      // Code pour récupérer et afficher les commentaires approuvés depuis la base de données
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "BlogAuto";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
  }

  $sql = "SELECT * FROM Comments WHERE validated = 1"; // Récupérer les commentaires validés
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<div class=\"comment\">";
      echo "<p><strong>Date :</strong> " . $row['created_at'] . "</p>"; // Afficher la date
      echo "<p><strong>Nom :</strong> " . $row['name'] . "</p>";
      echo "<p><strong>Commentaire :</strong> " . $row['comment'] . "</p>";
      echo "</div>";
    }
  } else {
    echo "Aucun commentaire approuvé.";
  }

  $conn->close();
  ?>
  </div>
 </div>
    <!---------------------------footer----------------------------------->
    <footer class="footer">
      <div class="container">
        <div class="col-12">
          <div class="row d-flex justify-content-center">
            <div class="col-md-2 col-sm-1">
              <a href="https://www.facebook.com/oves.moh">
              <img src="logos/facebook.png" alt="Logo 1" />
              </a>
            </div>
            <div class="col-md-2 col-sm-1">
              <a href="https://www.instagram.com/ovismo786/">
              <img src="logos/instagram.png" alt="Logo 2" />
              </a>
            </div>
            <div class="col-md-2 col-sm-1">
              <a href="https://www.linkedin.com/in/ovis-m-45763328a/">
              <img src="logos/linkedin.png" alt="Logo 3" />
              </a>
            </div>
            <div class="col-md-2 col-sm-1">
              <a href="https://twitter.com/oviss02">
              <img src="logos/twitter.png" alt="Logo 4" />
              </a>
            </div>
            <div class="col-md-2 col-sm-1">
              <a href="https://github.com/ovis02">
              <img src="logos/github.png" alt="Logo 5" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
    <script src="blog.js"></script>
  </body>
</html>
