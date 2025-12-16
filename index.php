<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jeu du Pendu</title>
  <link rel="stylesheet" href="main.css">
  
</head>
<body>
  <header>
    <h1>Jeu du Pendu</h1>
<p><h2>Tentez de deviner le mot secret en entrant des lettres une par une au clavier. Ne gaspillez pas vos coups, car si trop de vos choix sont erronés vous tuerez le pendu et vous perdrez la partie.</h2></p>


  </header>

    <div id="app-container">
    
        <div id="game-content">
        
            <section id="game-info" class="card-group">
                    <section id="word-section" class="card">
              <p class="label">Mot à deviner :</p>
              <div id="word-display">_ _ _ _ _ _ _ _</div>
          </section>

                    <section id="hangman-drawing" class="card">
              <p class="label">Statut du Pendu :</p>
              <div id="hangman-image">
                                    <img src="placeholder_pendu.png" alt="Dessin du pendu" style="max-width: 150px;">
              </div>
              <p class="label">Vies restantes : <span id="lives">6</span></p>
          </section>
      </section>
      
            <section id="alphabet" class="card">
          <p class="label">Choisis une lettre :</p>
          <div class="keys">
                            <div class="rangee-clavier">
                  <span class="key">A</span><span class="key">Z</span><span class="key">E</span><span class="key">R</span><span class="key">T</span>
                  <span class="key">Y</span><span class="key">U</span><span class="key">I</span><span class="key">O</span><span class="key">P</span>
              </div>
                            <div class="rangee-clavier">
                  <span class="key">Q</span><span class="key">S</span><span class="key">D</span><span class="key">F</span><span class="key">G</span>
                  <span class="key">H</span><span class="key">J</span><span class="key">K</span><span class="key">L</span><span class="key">M</span>
              </div>
                            <div class="rangee-clavier">
                  <span class="key">W</span><span class="key">X</span><span class="key">C</span><span class="key">V</span><span class="key">B</span><span class="key">N</span>
              </div>
          </div>
      </section>

            <section id="actions" class="card">
        <button id="restart">Nouvelle partie</button>
      </section>
      
    </div>         <aside id="right-sidebar">
        <div class="sidebar-card">
            <h3>Compte</h3>
            <nav class="sidebar-nav">
                <a href="#">Inscription</a>
                <a href="#">Connexion</a>
                <a href="#">Classements</a>
            </nav>
        </div>
        
        <div class="sidebar-card">
            <h3>Changer de thème</h3>
            <nav class="sidebar-nav themes">
                <nav class="sidebar-nav themes">
            <a href="?theme=boissons" class="theme-link">Boissons</a>
            <a href="?theme=couleurs" class="theme-link">Couleurs</a>
            <a href="?theme=animaux" class="theme-link">Animaux</
            <a href="?theme=info" class="theme-link">Informatique</a>
        </nav>
        </div>
    </aside>     
  </div>   <footer>
    <p>Version projet transversal made by  cedric , clara , brondon </p> 
  </footer>
</body>
</html>