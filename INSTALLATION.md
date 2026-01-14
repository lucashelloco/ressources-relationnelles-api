# 📚 Ressources Relationnelles - Guide d'installation

Bienvenue dans le projet **Ressources Relationnelles** ! Ce guide vous aidera à installer l'application complète sur votre machine.

---

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

### Outils nécessaires
- **Git** : https://git-scm.com/downloads
- **PHP 8.2 ou supérieur** : https://www.php.net/downloads
- **Composer** : https://getcomposer.org/download/
- **MySQL 8.0 ou supérieur** : https://dev.mysql.com/downloads/mysql/
- **Node.js 20.x ou supérieur** : https://nodejs.org/
- **npm** (inclus avec Node.js)

### Vérifier les installations

```bash
php --version        # Doit afficher 8.2+
composer --version   # Doit afficher Composer 2.x
mysql --version      # Doit afficher MySQL 8.0+
node --version       # Doit afficher v20.x+
npm --version        # Doit afficher 10.x+
git --version        # Doit afficher git 2.x+
```

---

## 🚀 Installation

### 1️⃣ Cloner les repositories

```bash
# Créer un dossier pour le projet
mkdir ~/Sites
cd ~/Sites

# Cloner le backend
git clone https://github.com/MLKgodx/ressources-relationnelles-api.git
cd ressources-relationnelles-api

# Revenir au dossier parent
cd ..

# Cloner le frontend
git clone https://github.com/MLKgodx/ressources-relationnelles-frontend.git
```

Vous devriez maintenant avoir :
```
~/Sites/
├── ressources-relationnelles-api/
└── ressources-relationnelles-frontend/
```

---

## 🗄️ 2️⃣ Configuration de la base de données MySQL

### Créer la base de données

```bash
# Se connecter à MySQL
mysql -u root -p
# Entrez votre mot de passe MySQL
```

Dans le prompt MySQL, tapez :
```sql
CREATE DATABASE ressources_relationnelles CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

---

## ⚙️ 3️⃣ Installation du Backend (API Laravel)

```bash
cd ~/Sites/ressources-relationnelles-api

# Installer les dépendances PHP
composer install

# Copier le fichier de configuration
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### Configurer le fichier `.env`

Ouvrez le fichier `.env` et modifiez ces lignes :

```bash
nano .env
# Ou utilisez votre éditeur préféré (VS Code, Sublime, etc.)
```

**Paramètres à modifier :**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ressources_relationnelles
DB_USERNAME=root
DB_PASSWORD=VOTRE_MOT_DE_PASSE_MYSQL
```

Sauvegardez et fermez le fichier.

### Créer la structure de la base de données

```bash
# Lancer les migrations (créer les tables)
php artisan migrate

# Remplir avec les données de test
php artisan db:seed
```

Vous devriez voir :
```
✅ 4 rôles créés
✅ 4 utilisateurs de test créés
✅ 5 catégories créées
✅ 8 tags créés
✅ 3 ressources créées
```

### Démarrer le serveur API

```bash
php artisan serve
```

✅ L'API est maintenant accessible sur : **http://localhost:8000**

Testez en ouvrant dans votre navigateur : http://localhost:8000/api/v1/categories

Vous devriez voir du JSON avec les catégories.

**⚠️ Gardez ce terminal ouvert !** Le serveur doit rester actif.

---

## 🎨 4️⃣ Installation du Frontend (Vue.js)

Ouvrez un **nouveau terminal** (gardez l'autre ouvert pour l'API).

```bash
cd ~/Sites/ressources-relationnelles-frontend

# Installer les dépendances Node.js
npm install

# Copier le fichier de configuration
cp .env.example .env
```

### Configurer le fichier `.env`

Ouvrez le fichier `.env` et vérifiez cette ligne :

```bash
nano .env
```

**Le fichier doit contenir :**
```env
VITE_API_URL=http://localhost:8000/api/v1
```

Sauvegardez et fermez.

### Démarrer le serveur frontend

```bash
npm run dev
```

✅ Le frontend est maintenant accessible sur : **http://localhost:3000** (ou 5173)

**⚠️ Gardez ce terminal ouvert aussi !**

---

## 🧪 5️⃣ Tester l'application

1. Ouvrez votre navigateur
2. Allez sur : **http://localhost:3000** (ou 5173)
3. Naviguez vers la page "Ressources"
4. Vous devriez voir les 3 ressources de test

### Se connecter avec un compte de test

Cliquez sur "Connexion" et utilisez un de ces comptes :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| 🔴 Super Admin | sophie.martin@admin.com | SuperAdmin123! |
| 🟠 Admin | pierre.dubois@admin.com | Admin123! |
| 🟢 Citoyen | marie.lefebvre@example.com | Citoyen123! |
| 🔵 Visiteur | lucas.moreau@example.com | Visiteur123! |

### Connexion temporaire en mode développement

Si la page de login n'est pas encore implémentée, ouvrez la console du navigateur (F12) et tapez :

```javascript
localStorage.setItem('user', JSON.stringify({
  prenom: 'Marie',
  nom: 'Lefebvre',
  email: 'marie.lefebvre@example.com',
  role: {nom: 'Citoyen Connecté', slug: 'citoyen'}
}))
localStorage.setItem('token', 'fake-token-dev')
location.reload()
```

Vous devriez maintenant voir les initiales **ML** en haut à droite et pouvoir accéder au menu utilisateur.

---

## 🔧 Commandes utiles

### Backend

```bash
cd ~/Sites/ressources-relationnelles-api

# Réinitialiser la base de données (attention : supprime toutes les données !)
php artisan migrate:fresh --seed

# Voir toutes les routes API
php artisan route:list

# Lancer les tests (si existants)
php artisan test

# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
```

### Frontend

```bash
cd ~/Sites/ressources-relationnelles-frontend

# Réinstaller les dépendances
npm install

# Build de production
npm run build

# Prévisualiser le build
npm run preview

# Linter (corriger le code)
npm run lint
```

---

## 🌳 Workflow Git avec SourceTree

### Installation de SourceTree

1. Téléchargez : https://www.sourcetreeapp.com
2. Installez et connectez votre compte GitHub
3. Ajoutez les 2 repositories :
   - **File** → **Add Existing Local Repository**
   - Sélectionnez `~/Sites/ressources-relationnelles-api`
   - Répétez pour `~/Sites/ressources-relationnelles-frontend`

### Workflow quotidien

**🔽 Avant de commencer à travailler :**
1. Ouvrez SourceTree
2. Cliquez sur **"Pull"** pour récupérer les changements des collègues

**🔼 Après avoir codé :**
1. Les fichiers modifiés apparaissent dans "Unstaged files"
2. Cliquez sur **"Stage All"** (ou sélectionnez les fichiers)
3. Écrivez un **message de commit** clair
4. Cliquez sur **"Commit"**
5. Cliquez sur **"Push"** pour envoyer sur GitHub

### Exemples de bons messages de commit

✅ **Bons exemples :**
- "Ajout de la page profil utilisateur"
- "Fix: correction bug menu dropdown"
- "Feature: API ressources avec pagination"
- "Refactor: amélioration du code RessourceController"

❌ **Mauvais exemples :**
- "update"
- "fix"
- "modif"
- "test"

---

## 🐛 Problèmes courants et solutions

### Erreur : "Connection refused" sur l'API
**Problème :** Le serveur Laravel n'est pas démarré.
**Solution :** Lancez `php artisan serve` dans le terminal backend.

### Erreur : "Access denied for user 'root'@'localhost'"
**Problème :** Mauvais mot de passe MySQL dans `.env`.
**Solution :** Vérifiez `DB_PASSWORD` dans `backend/.env`.

### Erreur : "Base table or view not found"
**Problème :** Les migrations n'ont pas été lancées.
**Solution :** `php artisan migrate --seed`

### Page blanche sur le frontend
**Problème :** Erreur JavaScript non visible.
**Solution :** 
1. Ouvrez la console (F12)
2. Regardez les erreurs en rouge
3. Vérifiez que l'API est bien lancée

### Erreur CORS
**Problème :** Le frontend ne peut pas communiquer avec l'API.
**Solution :** Vérifiez le fichier `backend/config/cors.php` et que l'API est bien sur `http://localhost:8000`.

### Port 8000 déjà utilisé
**Problème :** Un autre processus utilise le port 8000.
**Solution :** 
```bash
# Tuer le processus qui utilise le port 8000
lsof -ti:8000 | xargs kill -9

# Ou utiliser un autre port
php artisan serve --port=8001
# Pensez à modifier VITE_API_URL dans frontend/.env
```

### Les ressources ne s'affichent pas
**Problème :** Les seeders n'ont pas été lancés ou l'API ne répond pas.
**Solution :**
```bash
# Backend
php artisan db:seed

# Tester l'API directement
curl http://localhost:8000/api/v1/ressources
```

---

## 📦 Structure des projets

### Backend (API Laravel)
```
ressources-relationnelles-api/
├── app/
│   ├── Http/Controllers/API/  # Contrôleurs API
│   └── Models/                # Modèles Eloquent
├── database/
│   ├── migrations/            # Structure BDD
│   └── seeders/               # Données de test
├── routes/
│   └── api.php                # Routes API
├── .env.example               # Template config
└── README.md
```

### Frontend (Vue.js)
```
ressources-relationnelles-frontend/
├── src/
│   ├── components/            # Composants réutilisables
│   ├── views/                 # Pages de l'application
│   ├── stores/                # Stores Pinia (state)
│   ├── router/                # Configuration routes
│   └── services/              # Services API (axios)
├── .env.example               # Template config
└── README.md
```

---

## 🆘 Besoin d'aide ?

1. **Documentation Laravel** : https://laravel.com/docs
2. **Documentation Vue.js** : https://vuejs.org/guide/
3. **Contactez l'équipe** sur le channel Slack/Discord du projet

---

## ✅ Checklist finale

Avant de dire que tout fonctionne, vérifiez :

- [ ] MySQL est installé et la base de données `ressources_relationnelles` existe
- [ ] `php artisan serve` tourne sans erreur
- [ ] `npm run dev` tourne sans erreur
- [ ] http://localhost:8000/api/v1/categories retourne du JSON
- [ ] http://localhost:3000 affiche l'application
- [ ] Les ressources s'affichent sur la page "Ressources"
- [ ] Vous pouvez vous "connecter" (même en mode dev avec localStorage)
- [ ] Les 2 repos sont dans SourceTree

---

## 🎉 Félicitations !

Vous êtes maintenant prêt à développer sur le projet **Ressources Relationnelles** !

**N'oubliez pas :**
- 🔄 Toujours faire un **Pull** avant de commencer à coder
- 💬 Messages de commit clairs et descriptifs
- 🤝 Communiquer avec l'équipe si vous travaillez sur les mêmes fichiers

---

**Bon développement ! 🚀**
