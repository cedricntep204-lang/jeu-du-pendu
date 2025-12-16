# Installation et Démarrage

## Prérequis

- **PHP** (version 7.4 ou supérieure)
- **MySQL**
- Un serveur web (Apache)
- **Git** pour le clonage du dépôt.

### Instructions d'Installation

1.  **Cloner le dépôt :**

    ```bash
    git clone https://github.com/votre_utilisateur/jeu-du-pendu.git(https://github.com/votre_utilisateur/jeu-du-pendu.git)
    cd jeu-du-pendu/
    ```

2.  **Configuration du Serveur :**

    - Placez le dossier `jeu-du-pendu` dans le répertoire `www/` de votre environnement WAMP/MAMP.

3.  **Base de Données :**

    - Créez une base de données nommée `jeux_du_pendu` dans phpMyAdmin.
    - Ecrire le schéma initial dans le fichier `jeu_pendu_sql`

4.  **Configuration des Accès (si applicable) :**

    - Modifiez le fichier `config.php` pour y entrer vos identifiants de base de données :
      ```php
      // config.php
      $db_host = 'localhost';
      $db_user = 'root';
      $db_pass = 'votre_mot_de_passe';
      // ...
      ```

5.  **Accès au Projet :**
    - Ouvrez votre navigateur à l'adresse : `http://localhost/jeu-du-pendu/`

#### Architecture

- Les fichiers `index.php` et `head.php` gèrent la logique d'affichage et l'initialisation de la session.
- La logique du jeu est contenue dans `game.php`
- La connexion à la base de données est centralisée dans `config.php`.

##### Structure des Fichiers Clés

| Fichier/Dossier | Rôle                                                                  |
| :-------------- | :-------------------------------------------------------------------- |
| `index.php`     | Page d'accueil et lancement du jeu.                                   |
| `config.php`    | Paramètres de connexion BDD et variables globales.                    |
| `head.php`      | Contient les fonctions ou l'en-tête HTML réutilisable.                |
| `css/`          | Contient toutes les feuilles de style.                                |
| `jeu_pendu.sql` | Schéma de la base de données et données initiales.                    |
| `img/`          | Contient les images du pendu (`cpendu.jpg`, `homme_idee.jpg`, etc...) |

###### Base de Données

Le schéma de base de données est simple et contient une seule table clé :

- **Table `mots`** : Stocke les mots à deviner.
  - `id` (INT) : Clé primaire.
  - `mot` (VARCHAR) : Le mot à deviner.
  - `difficulte` (ENUM) : Niveau de difficulté ('facile', 'moyen', 'difficile').

###### Contribution

1.  Créer une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`).
2.  Committer vos changements (`git commit -m 'Ajout d'une fonctionnalité fantastique'`).
3.  Pousser vers la branche (`git push origin feature/AmazingFeature`).
4.  Ouvrir une Pull Request.
