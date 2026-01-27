# Système de Commentaires

## Vue d'ensemble

Le système de commentaires permet aux utilisateurs de commenter les ressources avec:
- ✅ Validation par modérateur obligatoire
- ✅ Réponses imbriquées (commentaires de commentaires)
- ✅ Gestion complète du cycle de vie (créer, modifier, supprimer)

## Structure de la base de données

### Table `commentaires`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | bigint | Identifiant unique |
| `ressource_id` | bigint | Ressource commentée |
| `auteur_id` | bigint | Auteur du commentaire |
| `parent_id` | bigint (nullable) | Commentaire parent (pour les réponses) |
| `contenu` | text | Contenu du commentaire |
| `statut` | enum | `en_attente`, `approuve`, `rejete` |
| `moderateur_id` | bigint (nullable) | Modérateur qui a validé/rejeté |
| `date_moderation` | datetime | Date de modération |
| `raison_rejet` | text | Raison du rejet (si applicable) |
| `deleted_at` | timestamp | Soft delete |

## API Endpoints

### Routes publiques

#### Liste des commentaires approuvés
```http
GET /api/v1/ressources/{ressourceId}/commentaires
```

Retourne tous les commentaires approuvés d'une ressource avec leurs réponses imbriquées.

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "contenu": "Super article !",
      "auteur": {
        "id": 5,
        "nom": "Dupont",
        "prenom": "Jean"
      },
      "nb_reponses": 2,
      "created_at": "2026-01-15T10:00:00",
      "reponses_approuvees": [
        {
          "id": 3,
          "contenu": "Je suis d'accord",
          "auteur": {...},
          "created_at": "2026-01-15T11:00:00"
        }
      ]
    }
  ]
}
```

### Routes authentifiées

#### Créer un commentaire
```http
POST /api/v1/ressources/{ressourceId}/commentaires
Authorization: Bearer {token}
```

**Body:**
```json
{
  "contenu": "Mon commentaire",
  "parent_id": null  // Optionnel: ID du commentaire parent pour une réponse
}
```

**Réponse:**
```json
{
  "success": true,
  "message": "Commentaire envoyé. Il sera visible après modération.",
  "data": {
    "id": 10,
    "contenu": "Mon commentaire",
    "statut": "en_attente",
    "auteur": {...}
  }
}
```

#### Modifier un commentaire
```http
PUT /api/v1/ressources/{ressourceId}/commentaires/{id}
Authorization: Bearer {token}
```

**Conditions:**
- L'utilisateur doit être l'auteur
- Le commentaire doit être en statut `en_attente`

#### Supprimer un commentaire
```http
DELETE /api/v1/ressources/{ressourceId}/commentaires/{id}
Authorization: Bearer {token}
```

**Conditions:**
- L'utilisateur doit être l'auteur

### Routes modération (Modérateurs uniquement)

#### Liste des commentaires en attente
```http
GET /api/v1/commentaires/en-attente
Authorization: Bearer {token}
```

#### Approuver un commentaire
```http
POST /api/v1/commentaires/{id}/approuver
Authorization: Bearer {token}
```

#### Rejeter un commentaire
```http
POST /api/v1/commentaires/{id}/rejeter
Authorization: Bearer {token}
```

**Body:**
```json
{
  "raison": "Contenu inapproprié"
}
```

## Workflow

### Pour un utilisateur

1. L'utilisateur crée un commentaire → **Statut: `en_attente`**
2. Le commentaire est invisible pour les autres
3. L'utilisateur peut modifier ou supprimer son commentaire tant qu'il est en attente
4. Un modérateur approuve/rejette → **Statut: `approuve` ou `rejete`**
5. Si approuvé, le commentaire devient visible publiquement

### Pour un modérateur

1. Accède à la liste des commentaires en attente
2. Lit le contenu du commentaire
3. Décide d'approuver ou rejeter
4. Si rejet, fournit une raison

## Règles métier

- ✅ Tous les commentaires nécessitent une modération
- ✅ Seuls les commentaires approuvés sont visibles publiquement
- ✅ Les réponses sont également soumises à modération
- ✅ Un utilisateur ne peut modifier que ses propres commentaires en attente
- ✅ La suppression d'un commentaire parent supprime toutes ses réponses (cascade)
- ✅ Soft delete: les commentaires supprimés restent en base

## Permissions requises

- **Créer/modifier/supprimer**: Utilisateur authentifié
- **Modérer**: Permission `moderer_contenu` (Modérateurs et Administrateurs)

## Modèle Eloquent

Le modèle `Commentaire` inclut:

**Relations:**
- `auteur()` - Utilisateur auteur
- `ressource()` - Ressource commentée
- `moderateur()` - Modérateur
- `parent()` - Commentaire parent
- `reponses()` - Réponses au commentaire
- `reponsesApprouvees()` - Réponses approuvées uniquement

**Scopes:**
- `approuve()` - Filtre les commentaires approuvés
- `enAttente()` - Filtre les commentaires en attente
- `premierNiveau()` - Commentaires sans parent

**Attributs calculés:**
- `nb_reponses` - Nombre de réponses approuvées
