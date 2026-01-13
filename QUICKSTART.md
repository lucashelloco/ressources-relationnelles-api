# 🚀 Guide de Démarrage Rapide

## Installation en 3 minutes

### Méthode 1 : Installation automatique (recommandée)

```bash
# Depuis le répertoire contenant les fichiers
bash install.sh /chemin/vers/votre-projet-laravel
```

Le script copiera automatiquement tous les fichiers au bon endroit.

### Méthode 2 : Installation manuelle

```bash
# 1. Copier les migrations
cp database/migrations/*.php votre-projet/database/migrations/

# 2. Copier les modèles
cp app/Models/*.php votre-projet/app/Models/

# 3. Copier les enums
cp app/Enums/*.php votre-projet/app/Enums/

# 4. Copier les contrôleurs
cp app/Http/Controllers/API/*.php votre-projet/app/Http/Controllers/API/

# 5. Copier le seeder
cp database/seeders/DatabaseSeeder.php votre-projet/database/seeders/
```

## Configuration

### 1. Base de données (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ressources_relationnelles
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Créer la base de données

```bash
# MySQL
mysql -u root -p
CREATE DATABASE ressources_relationnelles CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Ou PostgreSQL
psql -U postgres
CREATE DATABASE ressources_relationnelles;
\q
```

### 3. Exécuter les migrations

```bash
cd votre-projet-laravel
php artisan migrate
```

### 4. Peupler avec des données de test (optionnel)

```bash
php artisan db:seed
```

Cela créera :
- 4 rôles (Admin, Modérateur, Membre, Visiteur)
- 4 utilisateurs de test
- 8 catégories
- 10 tags
- 2 ressources exemples

### 5. Installer l'authentification API (Sanctum)

```bash
php artisan install:api
```

## Premiers tests

### Tester avec Postman ou cURL

#### 1. Créer un utilisateur (Register)

```bash
POST http://localhost:8000/api/v1/register
Content-Type: application/json

{
    "email": "test@example.com",
    "mot_de_passe": "password123",
    "nom": "Doe",
    "prenom": "John",
    "cgu_accepte": true
}
```

#### 2. Se connecter (Login)

```bash
POST http://localhost:8000/api/v1/login
Content-Type: application/json

{
    "email": "test@example.com",
    "mot_de_passe": "password123"
}
```

Réponse :
```json
{
    "success": true,
    "token": "1|xxxxxxxxxxxxxxxxxxxxx",
    "user": { ... }
}
```

#### 3. Récupérer les ressources (avec token)

```bash
GET http://localhost:8000/api/v1/ressources
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxx
```

#### 4. Créer une ressource

```bash
POST http://localhost:8000/api/v1/ressources
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxx
Content-Type: application/json

{
    "titre": "Ma première ressource",
    "description": "Une description intéressante",
    "type_ressource": "article",
    "type_relation": "familiale",
    "niveau": "debutant",
    "niveau_partage": "public",
    "categories": [1, 2],
    "tags": [1, 3]
}
```

## Comptes de test (après db:seed)

```
Admin:
- Email: admin@ressources-relationnelles.fr
- Mot de passe: password

Modérateur:
- Email: moderateur@ressources-relationnelles.fr
- Mot de passe: password

Membres:
- Email: jean.martin@example.com / Mot de passe: password
- Email: sophie.durand@example.com / Mot de passe: password
```

## Configuration des routes

Copiez le contenu de `routes/api-example.php` dans votre fichier `routes/api.php` :

```bash
cat routes/api-example.php >> routes/api.php
```

Ou intégrez manuellement les routes dont vous avez besoin.

## Structure des dossiers créés

```
votre-projet-laravel/
├── app/
│   ├── Enums/
│   │   └── Enums.php              # Toutes les énumérations
│   ├── Http/
│   │   └── Controllers/
│   │       └── API/
│   │           ├── RessourceController.php
│   │           └── ActiviteController.php
│   └── Models/
│       ├── Role.php
│       ├── Utilisateur.php
│       ├── Ressource.php
│       ├── Categorie.php
│       ├── Tag.php
│       ├── Commentaire.php
│       ├── Activite.php
│       ├── Participant.php
│       ├── Message.php
│       ├── Notification.php
│       └── Statistique.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_roles_table.php
│   │   ├── 2024_01_01_000002_create_utilisateurs_table.php
│   │   └── ... (12 autres migrations)
│   └── seeders/
│       └── DatabaseSeeder.php
└── routes/
    └── api-example.php
```

## Vérification de l'installation

```bash
# 1. Vérifier que les migrations sont bien installées
php artisan migrate:status

# 2. Vérifier les tables créées
php artisan tinker
> DB::select('SHOW TABLES');

# 3. Tester un modèle
> App\Models\Role::all();
> App\Models\Utilisateur::count();

# 4. Vérifier les routes API
php artisan route:list --path=api
```

## Commandes utiles

```bash
# Créer un nouveau contrôleur
php artisan make:controller API/MonController

# Créer un test
php artisan make:test RessourceTest

# Effacer et recréer la base
php artisan migrate:fresh --seed

# Créer une factory pour les tests
php artisan make:factory RessourceFactory

# Générer la documentation API
php artisan api:generate
```

## Dépannage

### Erreur "Class not found"
```bash
composer dump-autoload
```

### Erreur de migration
```bash
# Rollback
php artisan migrate:rollback

# Ou reset complet
php artisan migrate:fresh
```

### Erreur 500 sur les routes
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vérifier la configuration
php artisan config:cache
php artisan route:cache
```

## Prochaines étapes

1. ✅ Installation terminée
2. ⬜ Créer les controllers manquants
3. ⬜ Ajouter les validations (FormRequest)
4. ⬜ Créer les policies d'autorisation
5. ⬜ Ajouter les tests unitaires
6. ⬜ Configurer le frontend
7. ⬜ Déployer en production

## Ressources

- 📖 [Documentation complète](README-API.md)
- 🏗️ [Architecture détaillée](ARCHITECTURE-API.md)
- 🔗 [Exemple de routes](routes/api-example.php)
- 🌐 [Documentation Laravel](https://laravel.com/docs)
- 🔐 [Laravel Sanctum](https://laravel.com/docs/sanctum)

## Support

Si vous rencontrez des problèmes :

1. Consultez les logs : `storage/logs/laravel.log`
2. Vérifiez votre configuration : `.env`
3. Activez le mode debug : `APP_DEBUG=true`
4. Vérifiez la version PHP : `php -v` (minimum 8.2)
5. Vérifiez les permissions : `storage/` et `bootstrap/cache/`

---

**Bon développement ! 🎉**
