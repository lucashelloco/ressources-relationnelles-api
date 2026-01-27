# Configuration de Pusher pour les Discussions en Temps Réel

Ce guide explique comment configurer Pusher pour activer les discussions en temps réel dans l'application.

## 1. Créer un compte Pusher

1. Allez sur [pusher.com](https://pusher.com/)
2. Créez un compte gratuit
3. Créez une nouvelle application (Channels app)
4. Sélectionnez la région la plus proche de vos utilisateurs (par exemple: `eu` pour l'Europe)

## 2. Obtenir les credentials Pusher

Dans le dashboard de votre application Pusher, allez dans "App Keys" et notez:
- App ID
- Key
- Secret
- Cluster

## 3. Configuration Backend (Laravel)

### Mettre à jour le fichier `.env`

Remplacez les valeurs placeholder par vos vraies credentials:

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=eu  # ou votre cluster
```

### Configuration déjà en place

✅ Le package `pusher/pusher-php-server` est installé
✅ Les événements de broadcast sont configurés
✅ L'event `DiscussionMessageSent` est créé
✅ Les routes API sont en place

## 4. Configuration Frontend (Vue.js)

### Installer les dépendances

```bash
cd ressources-relationnelles-frontend
npm install pusher-js laravel-echo
```

### Configurer Echo dans le projet Vue

Les fichiers de configuration seront créés automatiquement dans:
- `src/services/echo.js` - Configuration Echo/Pusher
- `src/services/discussionService.js` - Service pour les discussions
- `src/components/discussions/DiscussionRoom.vue` - Composant de discussion

### Variables d'environnement Frontend

Créez/modifiez le fichier `.env` dans le frontend:

```env
VITE_PUSHER_APP_KEY=your-app-key
VITE_PUSHER_APP_CLUSTER=eu
VITE_API_URL=http://localhost:8000/api/v1
```

## 5. Test de la configuration

1. Lancez le backend Laravel:
   ```bash
   php artisan serve
   ```

2. Lancez le frontend Vue:
   ```bash
   npm run dev
   ```

3. Visitez une ressource et créez une discussion

4. Ouvrez la même ressource dans deux onglets différents

5. Envoyez un message depuis un onglet - il devrait apparaître instantanément dans l'autre

## 6. Debugger

### Dashboard Pusher

Le dashboard Pusher affiche en temps réel:
- Les connexions actives
- Les messages broadcastés
- Les erreurs éventuelles

### Console du navigateur

Activez le debugging Pusher en ajoutant dans Echo:
```javascript
window.Pusher.logToConsole = true;
```

## 7. Limites du plan gratuit

Le plan gratuit Pusher offre:
- 200,000 messages/jour
- 100 connexions simultanées max
- Parfait pour le développement et les petites applications

## Alternatives à Pusher

Si vous préférez une solution auto-hébergée:
- **Laravel WebSockets** - Compatible avec Pusher, hébergé sur votre serveur
- **Socket.io** - Nécessite un serveur Node.js
- **Soketi** - Alternative open-source compatible Pusher

## Support

Pour toute question:
- Documentation Pusher: https://pusher.com/docs/channels
- Documentation Laravel Broadcasting: https://laravel.com/docs/broadcasting
