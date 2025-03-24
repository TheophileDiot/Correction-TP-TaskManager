# TP Symfony – Gestion de Tâches

## Correction du TP

Ce dépôt contient la correction du TP sur la création d'une application de gestion de tâches avec Symfony. L'application implémente toutes les fonctionnalités demandées dans le sujet du TP.

## Installation et démarrage du projet

Pour faire fonctionner ce projet correctement, suivez ces étapes dans l'ordre :

1. **Cloner le dépôt** (si ce n'est pas déjà fait) :
   ```bash
   git clone <url-du-depot>
   cd gestion-taches
   ```

2. **Installer les dépendances PHP** :
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript** :
   ```bash
   npm install
   ```

4. **Compiler les assets** :
   ```bash
   npm run build
   # ou pour le mode développement avec auto-refresh
   npm run watch
   ```

5. **Configurer la base de données** :
   - Vérifiez que les paramètres de connexion dans le fichier `.env` correspondent à votre environnement
   - Par défaut, le projet est configuré pour utiliser MariaDB avec les paramètres suivants :
     ```
     DATABASE_URL="mysql://symfony:secret@127.0.0.1:3306/db?serverVersion=mariadb-11.7.2&charset=utf8mb4"
     ```
   - Modifiez ces paramètres si nécessaire

6. **Créer la base de données** (si elle n'existe pas) :
   ```bash
   php bin/console doctrine:database:create
   ```

7. **Exécuter les migrations** :
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

8. **Charger les fixtures** (données de test, optionnel) :
   ```bash
   php bin/console doctrine:fixtures:load
   ```

9. **Démarrer le serveur de développement** :
   ```bash
   symfony server:start --no-tls
   ```

10. **Accéder à l'application** :
    - Ouvrez votre navigateur et rendez-vous sur `http://localhost:8000`

## Structure du projet

- `src/Entity/Task.php` : Entité représentant une tâche
- `src/Repository/TaskRepository.php` : Repository pour les requêtes sur les tâches
- `src/Controller/TaskController.php` : Contrôleur gérant toutes les actions CRUD
- `src/Form/TaskType.php` : Type de formulaire pour la création/édition de tâches
- `templates/task/` : Templates Twig pour l'affichage des vues

## Fonctionnalités implémentées

- ✅ Affichage de la liste des tâches avec options de tri
- ✅ Affichage du détail d'une tâche
- ✅ Création d'une nouvelle tâche
- ✅ Modification d'une tâche existante
- ✅ Suppression d'une tâche
- ✅ Changement de statut d'une tâche (terminée/en cours)

## Contexte du TP

Dans ce TP, vous allez développer une application web simple de gestion de tâches. L'application permettra de :

- **Ajouter, afficher, modifier et supprimer des tâches.**
- Marquer une tâche comme terminée.
- Consulter le détail d'une tâche.

Ce mini-projet fil rouge vous permettra de vous familiariser avec :

- La structure d'un projet Symfony.
- La création d'entités et de contrôleurs.
- La manipulation de la base de données avec Doctrine.
- La création et la gestion de formulaires avec Twig.

Pour en savoir plus sur le contexte et l'architecture de Symfony, consultez la [documentation Symfony - Structure du projet](https://symfony.com/doc/current/setup.html).