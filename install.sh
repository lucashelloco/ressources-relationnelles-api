#!/bin/bash

# Script d'installation automatique pour l'API (RE)SOURCES RELATIONNELLES
# Usage: bash install.sh /chemin/vers/votre-projet-laravel

set -e

# Couleurs pour l'affichage
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   Installation API (RE)SOURCES RELATIONNELLES         ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════╝${NC}"
echo ""

# Vérifier si un chemin a été fourni
if [ -z "$1" ]; then
    echo -e "${RED}❌ Erreur: Veuillez spécifier le chemin vers votre projet Laravel${NC}"
    echo -e "${YELLOW}Usage: bash install.sh /chemin/vers/votre-projet-laravel${NC}"
    exit 1
fi

PROJECT_PATH="$1"

# Vérifier que le chemin existe
if [ ! -d "$PROJECT_PATH" ]; then
    echo -e "${RED}❌ Erreur: Le chemin '$PROJECT_PATH' n'existe pas${NC}"
    exit 1
fi

# Vérifier que c'est un projet Laravel
if [ ! -f "$PROJECT_PATH/artisan" ]; then
    echo -e "${RED}❌ Erreur: '$PROJECT_PATH' ne semble pas être un projet Laravel${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Projet Laravel détecté${NC}"
echo ""

# Demander confirmation
echo -e "${YELLOW}⚠️  Cette opération va copier les fichiers suivants:${NC}"
echo "  - Migrations (14 fichiers)"
echo "  - Modèles (11 fichiers)"
echo "  - Enums (1 fichier)"
echo "  - Contrôleurs API (2 fichiers)"
echo "  - Seeders (1 fichier)"
echo "  - Routes API exemple (1 fichier)"
echo ""
read -p "Continuer? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}Installation annulée${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}Début de l'installation...${NC}"
echo ""

# Créer les répertoires s'ils n'existent pas
echo -e "${YELLOW}Création des répertoires...${NC}"
mkdir -p "$PROJECT_PATH/app/Models"
mkdir -p "$PROJECT_PATH/app/Enums"
mkdir -p "$PROJECT_PATH/app/Http/Controllers/API"
mkdir -p "$PROJECT_PATH/database/migrations"
mkdir -p "$PROJECT_PATH/database/seeders"
echo -e "${GREEN}✓ Répertoires créés${NC}"
echo ""

# Copier les migrations
echo -e "${YELLOW}Copie des migrations...${NC}"
cp database/migrations/*.php "$PROJECT_PATH/database/migrations/"
echo -e "${GREEN}✓ 14 fichiers de migration copiés${NC}"
echo ""

# Copier les modèles
echo -e "${YELLOW}Copie des modèles...${NC}"
cp app/Models/*.php "$PROJECT_PATH/app/Models/"
echo -e "${GREEN}✓ 11 modèles copiés${NC}"
echo ""

# Copier les enums
echo -e "${YELLOW}Copie des enums...${NC}"
cp app/Enums/*.php "$PROJECT_PATH/app/Enums/"
echo -e "${GREEN}✓ Enums copiés${NC}"
echo ""

# Copier les contrôleurs
echo -e "${YELLOW}Copie des contrôleurs API...${NC}"
cp app/Http/Controllers/API/*.php "$PROJECT_PATH/app/Http/Controllers/API/"
echo -e "${GREEN}✓ 2 contrôleurs copiés${NC}"
echo ""

# Copier le seeder
echo -e "${YELLOW}Copie du seeder...${NC}"
cp database/seeders/DatabaseSeeder.php "$PROJECT_PATH/database/seeders/"
echo -e "${GREEN}✓ Seeder copié${NC}"
echo ""

# Copier l'exemple de routes
echo -e "${YELLOW}Copie de l'exemple de routes...${NC}"
cp routes/api-example.php "$PROJECT_PATH/routes/"
echo -e "${GREEN}✓ Fichier de routes copié${NC}"
echo ""

# Copier la documentation
echo -e "${YELLOW}Copie de la documentation...${NC}"
cp README.md "$PROJECT_PATH/README-API.md"
cp ARCHITECTURE.md "$PROJECT_PATH/ARCHITECTURE-API.md"
echo -e "${GREEN}✓ Documentation copiée${NC}"
echo ""

echo -e "${BLUE}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   Installation terminée avec succès! ✓                ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════╝${NC}"
echo ""

echo -e "${GREEN}Prochaines étapes:${NC}"
echo ""
echo "1. Vérifier votre fichier .env (configuration base de données)"
echo "   ${YELLOW}cd $PROJECT_PATH${NC}"
echo ""
echo "2. Exécuter les migrations:"
echo "   ${YELLOW}php artisan migrate${NC}"
echo ""
echo "3. (Optionnel) Peupler la base avec des données de test:"
echo "   ${YELLOW}php artisan db:seed${NC}"
echo ""
echo "4. (Recommandé) Installer Laravel Sanctum pour l'authentification:"
echo "   ${YELLOW}php artisan install:api${NC}"
echo ""
echo "5. Consulter la documentation:"
echo "   - ${YELLOW}README-API.md${NC} : Documentation complète de l'API"
echo "   - ${YELLOW}ARCHITECTURE-API.md${NC} : Vue d'ensemble de l'architecture"
echo "   - ${YELLOW}routes/api-example.php${NC} : Exemple de routes API"
echo ""
echo -e "${BLUE}Bon développement! 🚀${NC}"
