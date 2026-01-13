# Architecture API Laravel - (RE)SOURCES RELATIONNELLES

## 📋 Vue d'ensemble

Cette API a été générée à partir de votre MCD (Modèle Conceptuel de Données) et comprend tous les modèles, migrations et relations nécessaires pour votre plateforme de ressources relationnelles.

## 📦 Contenu du package

### 1️⃣ Migrations (14 fichiers)
Toutes les tables de la base de données avec leurs contraintes, index et relations :

- ✅ `2024_01_01_000001_create_roles_table.php`
- ✅ `2024_01_01_000002_create_utilisateurs_table.php`
- ✅ `2024_01_01_000003_create_categories_table.php`
- ✅ `2024_01_01_000004_create_ressources_table.php`
- ✅ `2024_01_01_000005_create_tags_table.php`
- ✅ `2024_01_01_000006_create_commentaires_table.php`
- ✅ `2024_01_01_000007_create_activites_table.php`
- ✅ `2024_01_01_000008_create_participants_table.php`
- ✅ `2024_01_01_000009_create_messages_table.php`
- ✅ `2024_01_01_000010_create_notifications_table.php`
- ✅ `2024_01_01_000011_create_statistiques_table.php`
- ✅ `2024_01_01_000012_create_ressource_categorie_table.php`
- ✅ `2024_01_01_000013_create_ressource_tag_table.php`
- ✅ `2024_01_01_000014_create_favoris_table.php`

### 2️⃣ Modèles Eloquent (11 fichiers)
Avec toutes les relations définies :

- ✅ `Role.php` - Gestion des rôles et permissions
- ✅ `Utilisateur.php` - Comptes utilisateurs (10 relations)
- ✅ `Categorie.php` - Classification des ressources
- ✅ `Tag.php` - Étiquettes pour ressources
- ✅ `Ressource.php` - Contenus principaux (8 relations + scopes)
- ✅ `Commentaire.php` - Commentaires et réponses (4 relations + scopes)
- ✅ `Activite.php` - Événements et ateliers (3 relations + scopes)
- ✅ `Participant.php` - Inscriptions aux activités
- ✅ `Message.php` - Messages dans les activités
- ✅ `Notification.php` - Notifications utilisateurs
- ✅ `Statistique.php` - Tracking anonymisé

### 3️⃣ Contrôleurs API (2 exemples complets)

- ✅ `RessourceController.php` - CRUD complet + favoris + publication
- ✅ `ActiviteController.php` - CRUD + gestion des inscriptions

### 4️⃣ Fichiers de configuration

- ✅ `Enums.php` - Toutes les énumérations typées (PHP 8.1+)
- ✅ `DatabaseSeeder.php` - Données de test (roles, utilisateurs, catégories, etc.)
- ✅ `api-example.php` - Structure complète des routes API

### 5️⃣ Documentation

- ✅ `README.md` - Documentation complète de l'API
- ✅ `ARCHITECTURE.md` - Ce fichier

## 🏗️ Structure de la base de données

```
┌─────────────────┐
│     ROLES       │
└────────┬────────┘
         │ 1
         │
         │ n
┌────────▼────────────────────────────────────┐
│          UTILISATEURS                       │
├────────────────────────────────────────────┤
│ - Informations personnelles                │
│ - Authentification                         │
│ - Rôle (role_id)                          │
└────────┬────────────────────────┬──────────┘
         │ 1                      │ n
         │                        │
         │ n                      ▼
┌────────▼────────┐      ┌───────────────┐
│   RESSOURCES    │◄─────┤   FAVORIS     │
├─────────────────┤      └───────────────┘
│ - Contenus      │
│ - Types         │◄─────┐
│ - Statuts       │      │ n
└────────┬────────┘      │
         │ n             │
         │               │
         ▼               │
┌────────────────┐       │
│  COMMENTAIRES  │       │
└────────────────┘       │
                         │
         ┌───────────────┤
         │ n             │
         ▼               │
┌────────────────┐       │
│   CATEGORIES   │───────┘
└────────────────┘
         ▲
         │ n
         │
         │ n
┌────────┴────────┐
│      TAGS       │
└─────────────────┘

┌─────────────────┐
│   ACTIVITES     │
├─────────────────┤
│ - Événements    │
│ - Ateliers      │
└────────┬────────┘
         │ 1
         │
         │ n
┌────────▼────────┐
│  PARTICIPANTS   │
└─────────────────┘
         │ n
         │
         │ 1
┌────────▼────────┐
│    MESSAGES     │
└─────────────────┘

┌──────────────────┐
│  NOTIFICATIONS   │
└──────────────────┘

┌──────────────────┐
│  STATISTIQUES    │
└──────────────────┘
```

## 🔗 Relations principales

### Utilisateur
- **BelongsTo** Role (1-1)
- **HasMany** Ressource (auteur) (1-n)
- **HasMany** Ressource (modérateur) (1-n)
- **BelongsToMany** Ressource (favoris) (n-n)
- **HasMany** Commentaire (1-n)
- **HasMany** Activite (organisateur) (1-n)
- **BelongsToMany** Activite (participant) (n-n)
- **HasMany** Message (1-n)
- **HasMany** Notification (1-n)
- **HasMany** Statistique (1-n)

### Ressource
- **BelongsTo** Utilisateur (auteur) (n-1)
- **BelongsTo** Utilisateur (modérateur) (n-1)
- **BelongsToMany** Categorie (n-n)
- **BelongsToMany** Tag (n-n)
- **HasMany** Commentaire (1-n)
- **BelongsToMany** Utilisateur (favoris) (n-n)
- **HasMany** Statistique (1-n)

### Activite
- **BelongsTo** Utilisateur (organisateur) (n-1)
- **BelongsToMany** Utilisateur (participants) (n-n)
- **HasMany** Message (1-n)

## 📊 Types de données

### Type Ressource
- article
- video
- audio
- document
- lien
- infographie

### Type Relation
- familiale
- amicale
- amoureuse
- professionnelle
- therapeutique
- autre

### Statut Ressource
- brouillon
- en_attente
- publie
- archive
- rejete

### Niveau Partage
- prive (seulement l'auteur)
- membres (utilisateurs inscrits)
- public (tout le monde)

### Type Activité
- discussion
- evenement
- atelier
- groupe_echange
- conference
- autre

### Statut Activité
- planifie
- en_cours
- termine
- annule

## 🚀 Installation rapide

```bash
# 1. Copier les fichiers dans votre projet Laravel
cp -r database/migrations/* votre-projet/database/migrations/
cp -r app/Models/* votre-projet/app/Models/
cp -r app/Enums/* votre-projet/app/Enums/
cp -r app/Http/Controllers/API/* votre-projet/app/Http/Controllers/API/
cp -r database/seeders/* votre-projet/database/seeders/

# 2. Exécuter les migrations
php artisan migrate

# 3. (Optionnel) Peupler avec des données de test
php artisan db:seed

# 4. (Optionnel) Installer Sanctum pour l'authentification API
php artisan install:api
```

## 🔐 Sécurité

### Points importants
1. ✅ Soft delete sur utilisateurs, ressources, commentaires, activités
2. ✅ Foreign keys avec contraintes (cascade/restrict/set null)
3. ✅ Index sur les champs fréquemment recherchés
4. ✅ Full-text search sur titre et description des ressources
5. ✅ Anonymisation des statistiques (IP anonymisée)
6. ✅ Validation des données avec Enums
7. ✅ Permissions basées sur les rôles

### À implémenter
- [ ] Laravel Sanctum pour l'authentification API
- [ ] Policies Laravel pour l'autorisation
- [ ] Rate limiting sur les routes sensibles
- [ ] Validation des uploads de fichiers
- [ ] HTTPS obligatoire en production
- [ ] CORS configuré correctement

## 📱 Exemples d'utilisation

### Créer une ressource
```php
$ressource = Ressource::create([
    'titre' => 'Mon article',
    'type_ressource' => 'article',
    'type_relation' => 'familiale',
    'statut' => 'brouillon',
    'auteur_id' => auth()->id()
]);

// Attacher des catégories
$ressource->categories()->attach([1, 2, 3]);

// Attacher des tags
$ressource->tags()->attach([1, 5, 7]);
```

### Rechercher des ressources
```php
// Ressources publiées et publiques avec eager loading
$ressources = Ressource::with(['auteur', 'categories', 'tags'])
    ->publiees()
    ->publiques()
    ->orderBy('date_publication', 'desc')
    ->paginate(15);
```

### Gérer une activité
```php
// Créer une activité
$activite = Activite::create([
    'titre' => 'Atelier Communication',
    'type' => 'atelier',
    'date_debut' => now()->addDays(7),
    'organisateur_id' => auth()->id()
]);

// Inscrire un participant
$activite->participants()->attach($utilisateur->id, [
    'date_inscription' => now(),
    'statut' => 'inscrit'
]);
```

## 🧪 Tests suggérés

```bash
# Feature tests à créer
php artisan make:test RessourceTest
php artisan make:test UtilisateurTest
php artisan make:test ActiviteTest
php artisan make:test CommentaireTest
php artisan make:test NotificationTest

# Unit tests
php artisan make:test --unit RolePermissionTest
php artisan make:test --unit EnumTest
```

## 📈 Optimisations

### Base de données
- Index déjà créés sur les clés étrangères
- Index sur les champs de recherche (email, statut, etc.)
- Full-text index pour la recherche
- Soft deletes pour l'historique

### Code
```php
// Eager loading pour éviter N+1
Ressource::with(['auteur', 'categories', 'tags'])->get();

// Cache des données statiques
Cache::remember('categories', 3600, fn() => 
    Categorie::where('est_active', true)->get()
);

// Chunking pour les gros volumes
Statistique::chunk(1000, function ($stats) {
    // Traitement par lots
});
```

## 🎯 Prochaines étapes

1. [ ] Intégrer dans votre projet Laravel
2. [ ] Configurer l'authentification (Sanctum)
3. [ ] Créer les policies d'autorisation
4. [ ] Ajouter la validation des formulaires
5. [ ] Implémenter les tests
6. [ ] Configurer le frontend (Vue.js, React, etc.)
7. [ ] Déployer en production

## 📞 Support

Pour toute question sur l'utilisation de cette API :
- Consulter le README.md principal
- Documentation Laravel : https://laravel.com/docs
- Vérifier les logs : `storage/logs/laravel.log`

---

**Version :** 1.0.0  
**Date de création :** 13 janvier 2025  
**Framework :** Laravel 11.x  
**PHP :** 8.2+  
**Base de données :** MySQL 8.0+ / PostgreSQL 14+
