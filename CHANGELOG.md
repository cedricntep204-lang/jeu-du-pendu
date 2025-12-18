# 📝 CHANGELOG - Jeu du Pendu

## ✨ Nouvelle version - Logique complète du jeu

### 🎨 Images du pendu (SVG)

- ✅ Créé 8 images SVG dans `source/` (pendu0.svg à pendu7.svg)
- ✅ Potence noire avec personnage rose (#ff69b4)
- ✅ Progression visuelle en 7 étapes :
  1. Potence vide
  2. Tête
  3. Corps
  4. Bras gauche
  5. Bras droit
  6. Jambe gauche
  7. Jambe droite
  8. Visage triste (défaite)

### 🎮 Nouvelle logique de jeu

#### Système de vies

- **7 vies maximum** pour tous les niveaux (au lieu de 4/6/8)
- Affichage des vies restantes : `X / 7`
- Image du pendu mise à jour selon le nombre d'erreurs

#### Gestion des lettres

- ✅ **Lettre correcte** : Révèle TOUTES les occurrences
- ✅ **Lettre incorrecte** : -1 vie + malus de points
- ✅ **Lettre déjà testée** : Message d'avertissement, aucune pénalité
- ✅ Affichage des lettres testées (vertes = bonnes, rouges = mauvaises)

#### Proposition de mot complet

- ✅ **Nouveau champ** pour proposer le mot entier
- ✅ **Mot correct** : Victoire immédiate + bonus
- ✅ **Mot incorrect** : -2 vies + double malus
- ✅ Avertissement visible : "⚠️ Attention : un mot incorrect = -2 vies !"

#### Système de points amélioré

- **Facile** : 10 pts/lettre, -5 pts/erreur, +50 pts bonus
- **Moyen** : 15 pts/lettre, -7 pts/erreur, +100 pts bonus
- **Difficile** : 20 pts/lettre, -10 pts/erreur, +200 pts bonus
- Points calculés selon le nombre d'occurrences de la lettre

#### Affichage du mot

- Format : `_ R O _ O _ I _ E`
- Lettres trouvées affichées dans leur ordre exact
- Espaces entre chaque caractère pour meilleure lisibilité

### 🔧 Améliorations techniques

#### Base de données

- Enregistrement du nombre d'erreurs (au lieu de tentatives)
- Sauvegarde unique de la partie (flag `enregistre`)
- Points sauvegardés même en cas de défaite (0 si perdu)

#### Interface utilisateur

- Messages contextuels :
  - ✅ "Bonne lettre ! +X points (Y occurrence(s))"
  - ❌ "Mauvaise lettre ! -X points"
  - ⚠️ "Lettre déjà testée ! Aucune vie perdue"
  - 🎉 "Mot correct ! Bonus de +X points !"
- Lien vers le classement en bas de page
- Bouton "Recommencer" toujours disponible

### 📁 Fichiers créés/modifiés

#### Créés

- `source/pendu0.svg` à `source/pendu7.svg` (8 images)
- `test_pendu.html` (page de test des images)
- `REGLES_DU_JEU.md` (documentation complète)
- `CHANGELOG.md` (ce fichier)

#### Modifiés

- `php/game.php` (logique complète refaite)
- `php/classements.php` (style complété)

### 🧪 Tests recommandés

1. **Tester les lettres** :

   - Proposer une lettre correcte
   - Proposer une lettre incorrecte
   - Proposer la même lettre deux fois

2. **Tester le mot complet** :

   - Proposer le bon mot
   - Proposer un mauvais mot

3. **Tester les conditions de fin** :

   - Gagner en trouvant toutes les lettres
   - Gagner en proposant le mot complet
   - Perdre en faisant 7 erreurs

4. **Tester les difficultés** :
   - Vérifier les points par niveau
   - Vérifier les bonus de victoire

### 🎯 Prochaines améliorations possibles

- [ ] Ajouter un timer
- [ ] Ajouter des indices
- [ ] Ajouter des catégories de mots
- [ ] Ajouter un mode multijoueur
- [ ] Ajouter des sons
- [ ] Ajouter des animations CSS

---

**Date** : 2025-12-17
**Version** : 2.0
**Statut** : ✅ Prêt pour les tests
