# 📚 Ressources Relationnelles - API Backend

API REST Laravel pour la plateforme de partage de ressources relationnelles.

## 🏗️ Stack technique
- Laravel 11
- MySQL 8.0
- PHP 8.2+

## 🚀 Installation
```bash
# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Configurer MySQL dans .env
DB_DATABASE=ressources_relationnelles
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Créer la base de données
mysql -u root -p
CREATE DATABASE ressources_relationnelles;
exit

# Lancer migrations + seeders
php artisan migrate --seed

# Démarrer le serveur
php artisan serve
```

## 👥 Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| 🔴 Super Admin | sophie.martin@admin.com | SuperAdmin123! |
| 🟠 Admin | pierre.dubois@admin.com | Admin123! |
| 🟢 Citoyen | marie.lefebvre@example.com | Citoyen123! |
| 🔵 Visiteur | lucas.moreau@example.com | Visiteur123! |

## 📡 API Endpoints

- `GET /api/v1/categories` - Liste des catégories
- `GET /api/v1/ressources` - Liste des ressources
- `GET /api/v1/tags` - Liste des tags
- Plus de détails dans la documentation API

## 🔧 Commandes utiles
```bash
# Réinitialiser la BDD
php artisan migrate:fresh --seed

# Voir les routes
php artisan route:list
```
