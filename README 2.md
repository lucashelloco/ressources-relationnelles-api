# API Laravel - (RE)SOURCES RELATIONNELLES

## Description
API REST pour la plateforme de ressources relationnelles basée sur Laravel 11.x

## Structure de la Base de Données

### Tables Principales

#### 1. **utilisateurs**
Gère les comptes utilisateurs de la plateforme
- Informations personnelles (nom, prénom, email, etc.)
- Authentification (mot_de_passe)
- Rôle associé (role_id)
- Statuts (actif, email vérifié, CGU acceptées)

#### 2. **roles**
Définit les rôles et permissions des utilisateurs
- Niveaux d'autorisation
- Permissions JSON

#### 3. **ressources**
Contenus partagés par les utilisateurs
- Types : article, video, audio, document, lien, infographie
- Types de relation : familiale, amicale, amoureuse, professionnelle, thérapeutique
- Statuts : brouillon, en_attente, publié, archivé, rejeté
- Niveaux de partage : privé, membres, public

#### 4. **categories**
Classification des ressources
- Nom, slug, description
- Icône et couleur pour l'UI
- Ordre d'affichage

#### 5. **tags**
Étiquettes pour les ressources
- Compteur d'utilisations

#### 6. **commentaires**
Système de commentaires sur les ressources
- Support des réponses (parent_id)
- Modération (statut)
- Likes

#### 7. **activites**
Événements, ateliers, discussions
- Types : discussion, événement, atelier, groupe_echange, conférence
- Gestion de participants avec limite
- En ligne ou présentiel

#### 8. **participants**
Table pivot pour les inscriptions aux activités
- Statuts : inscrit, confirmé, absent, présenté

#### 9. **messages**
Messages dans les activités/discussions
- Suivi de lecture

#### 10. **notifications**
Notifications utilisateurs
- Plusieurs types d'événements
- Statut de lecture

#### 11. **statistiques**
Tracking anonymisé des actions
- Types d'événements
- Métadonnées JSON pour flexibilité

### Tables Pivot
- **ressource_categorie** : Relation N-N ressources/catégories
- **ressource_tag** : Relation N-N ressources/tags
- **favoris** : Ressources favorites des utilisateurs

## Installation

### 1. Copier les fichiers
```bash
# Copier les migrations
cp database/migrations/* votre-projet-laravel/database/migrations/

# Copier les modèles
cp app/Models/* votre-projet-laravel/app/Models/
```

### 2. Exécuter les migrations
```bash
php artisan migrate
```

### 3. (Optionnel) Créer des seeders
```bash
php artisan make:seeder RoleSeeder
php artisan make:seeder UtilisateurSeeder
php artisan make:seeder CategorieSeeder
```

## Relations Eloquent

### Utilisateur
- `role()` - BelongsTo Role
- `ressourcesCreees()` - HasMany Ressource (en tant qu'auteur)
- `ressourcesModerees()` - HasMany Ressource (en tant que modérateur)
- `favoris()` - BelongsToMany Ressource
- `commentaires()` - HasMany Commentaire
- `activitesOrganisees()` - HasMany Activite
- `activitesParticipees()` - BelongsToMany Activite
- `messages()` - HasMany Message
- `notifications()` - HasMany Notification
- `statistiques()` - HasMany Statistique

### Ressource
- `auteur()` - BelongsTo Utilisateur
- `moderateur()` - BelongsTo Utilisateur
- `categories()` - BelongsToMany Categorie
- `tags()` - BelongsToMany Tag
- `commentaires()` - HasMany Commentaire
- `utilisateursFavoris()` - BelongsToMany Utilisateur
- `statistiques()` - HasMany Statistique

### Activite
- `organisateur()` - BelongsTo Utilisateur
- `participants()` - BelongsToMany Utilisateur
- `messages()` - HasMany Message

## Scopes Utiles

### Ressource
```php
// Ressources publiées
Ressource::publiees()->get();

// Ressources publiques
Ressource::publiques()->get();
```

### Commentaire
```php
// Commentaires publiés
Commentaire::publies()->get();

// Commentaires racine (sans parent)
Commentaire::racine()->get();
```

### Activite
```php
// Activités à venir
Activite::aVenir()->get();

// Activités en cours
Activite::enCours()->get();
```

### Notification
```php
// Notifications non lues
Notification::nonLues()->get();

// Notifications récentes (24h)
Notification::recentes()->get();
```

### Statistique
```php
// Par type d'événement
Statistique::parType('vue_ressource')->get();

// Sur une période
Statistique::periode('2024-01-01', '2024-12-31')->get();
```

## Exemples de Contrôleurs API

Voir les fichiers dans `app/Http/Controllers/API/` pour des exemples complets :
- `RessourceController.php`
- `UtilisateurController.php`
- `ActiviteController.php`
- `CommentaireController.php`

## Sécurité

### Recommandations
1. Utiliser Laravel Sanctum pour l'authentification API
2. Valider toutes les entrées utilisateur
3. Implémenter des policies pour l'autorisation
4. Anonymiser les statistiques (IP, données sensibles)
5. Utiliser le soft delete pour les suppressions

### Installation de Sanctum
```bash
php artisan install:api
```

## Routes API Suggérées

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    // Ressources
    Route::apiResource('ressources', RessourceController::class);
    Route::post('ressources/{id}/favoris', [RessourceController::class, 'toggleFavori']);
    
    // Commentaires
    Route::apiResource('commentaires', CommentaireController::class);
    
    // Activités
    Route::apiResource('activites', ActiviteController::class);
    Route::post('activites/{id}/inscription', [ActiviteController::class, 'inscrire']);
    
    // Utilisateur
    Route::get('profil', [UtilisateurController::class, 'profil']);
    Route::put('profil', [UtilisateurController::class, 'updateProfil']);
    
    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::put('notifications/{id}/lire', [NotificationController::class, 'marquerLue']);
});
```

## Tests

```bash
# Créer des tests
php artisan make:test RessourceTest
php artisan make:test UtilisateurTest

# Exécuter les tests
php artisan test
```

## Performances

### Indexation
Les migrations incluent déjà des index sur :
- Clés étrangères
- Champs de recherche fréquents (email, statut, etc.)
- Full-text search sur titre et description des ressources

### Optimisations
```php
// Eager loading pour éviter N+1
Ressource::with(['auteur', 'categories', 'tags'])->get();

// Cache des données statiques
Cache::remember('categories', 3600, function () {
    return Categorie::where('est_active', true)->get();
});
```

## Documentation API

Considérer l'utilisation de :
- **Laravel API Documentation Generator** : `php artisan api:generate`
- **Swagger/OpenAPI** via `darkaonline/l5-swagger`
- **Scribe** : Documentation automatique

## Support

Pour toute question ou problème :
1. Consulter la documentation Laravel : https://laravel.com/docs
2. Vérifier les logs : `storage/logs/laravel.log`
3. Activer le mode debug : `APP_DEBUG=true` dans `.env`

## Licence

MIT License
