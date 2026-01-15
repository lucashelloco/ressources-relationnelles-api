# Configuration de la base de données

## Exécuter les seeders

Pour peupler la base de données avec les données de référence (rôles, catégories, tags), exécutez:

```bash
php artisan db:seed
```

Ou pour exécuter un seeder spécifique:

```bash
php artisan db:seed --class=RolesTableSeeder
php artisan db:seed --class=CategoriesTableSeeder
php artisan db:seed --class=TagsTableSeeder
```

## Réinitialiser et peupler la base de données

Pour réinitialiser complètement la base de données et exécuter tous les seeders:

```bash
php artisan migrate:fresh --seed
```

## Données créées

### Rôles
- **Administrateur**: Tous les droits
- **Modérateur**: Modération de contenu
- **Membre**: Création de ressources et activités
- **Visiteur**: Lecture seule

### Catégories (8 catégories)
1. Communication
2. Relations Familiales
3. Relations Amoureuses
4. Amitiés
5. Relations Professionnelles
6. Gestion des Conflits
7. Développement Personnel
8. Émotions et Bien-être

### Tags (30 tags)
Tags variés couvrant: communication, émotions, relations, développement personnel, thérapie, etc.

## Nouveaux endpoints API

### GET /api/v1/stats
Retourne les statistiques globales du site:
```json
{
  "success": true,
  "data": {
    "ressources": 150,
    "utilisateurs": 1250,
    "activites": 45,
    "commentaires": 3200
  }
}
```

### GET /api/v1/config
Retourne toutes les options de configuration pour les formulaires:
```json
{
  "success": true,
  "data": {
    "types_ressource": [...],
    "types_relation": [...],
    "niveaux": [...],
    "niveaux_partage": [...],
    "types_activite": [...],
    "statuts_activite": [...]
  }
}
```
