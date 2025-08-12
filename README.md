# StockMaster Pro - Système de Gestion d'Inventaire avec IA

## 🚀 Description

StockMaster Pro est une application web complète de gestion d'inventaire moderne avec :
- 🔐 **Authentification sécurisée** avec Symfony Security
- 💬 **Chat en temps réel** avec WebSocket
- 🤖 **Assistant IA** intégré pour la gestion des stocks
- 📊 **Dashboard analytics** avec graphiques interactifs
- 🔄 **API REST complète** pour intégrations
- 🐳 **Déploiement Docker** production-ready
- 📱 **Interface responsive** moderne

## 🏗️ Architecture

```
StockMaster-Pro/
├── backend/                 # API Symfony 7
│   ├── config/             # Configuration Symfony
│   ├── src/
│   │   ├── Controller/     # Contrôleurs API
│   │   ├── Entity/         # Entités Doctrine
│   │   ├── Repository/     # Repositories Doctrine
│   │   ├── Service/        # Services métier
│   │   ├── Security/       # Authentification
│   │   └── EventSubscriber/ # Events WebSocket
│   ├── migrations/         # Migrations BDD
│   └── templates/          # Templates Twig (admin)
├── frontend/               # React + TypeScript
│   ├── src/
│   │   ├── components/     # Composants React
│   │   ├── pages/          # Pages principales
│   │   ├── services/       # Services API
│   │   ├── hooks/          # Hooks personnalisés
│   │   └── utils/          # Utilitaires
│   └── public/
├── websocket-server/       # Serveur Node.js WebSocket
├── database/               # Scripts MySQL
├── docker/                 # Configuration Docker
├── scripts/                # Scripts d'automatisation
└── docs/                   # Documentation
```

## 🛠️ Stack Technique

### Backend (Symfony)
- **Framework** : Symfony 7.1
- **PHP** : 8.2+
- **ORM** : Doctrine ORM
- **Base de données** : MySQL 8.0
- **Cache** : Redis
- **Authentification** : Symfony Security + JWT
- **API** : API Platform + Serializer
- **Tests** : PHPUnit

### Frontend (React)
- **Framework** : React 18 + TypeScript
- **Styling** : Tailwind CSS
- **Graphiques** : Chart.js / Recharts
- **État** : React Query + Zustand
- **Routing** : React Router v6
- **WebSocket** : Socket.io client

### WebSocket & Services
- **WebSocket Server** : Node.js + Socket.io
- **Queue System** : Symfony Messenger + Redis
- **IA Integration** : OpenAI/Claude API
- **Email** : Symfony Mailer

### DevOps
- **Containerisation** : Docker + docker-compose
- **Web Server** : Nginx
- **Process Manager** : Supervisor
- **Monitoring** : Adminer pour MySQL

## 🎯 Fonctionnalités

### Core Features
- ✅ **Authentification complète** (Register/Login/JWT)
- ✅ **Gestion des produits** (CRUD complet)
- ✅ **Suivi des stocks** en temps réel
- ✅ **Gestion des fournisseurs** et clients
- ✅ **Historique des mouvements** de stock
- ✅ **Alertes automatiques** de réapprovisionnement
- ✅ **Gestion des rôles** (Admin/Manager/Employee)

### Features Avancées
- 🤖 **Assistant IA** pour recommandations de stock
- 💬 **Chat temps réel** entre équipes
- 📊 **Dashboard analytics** avec KPIs
- 📈 **Prévisions de stock** basées sur l'historique
- 🔔 **Notifications push** en temps réel
- 📱 **Interface mobile** responsive
- 📤 **Export données** (CSV/Excel/PDF)
- 🔍 **Recherche avancée** et filtres

## 📋 Plan d'Action - 6 Semaines

### 🗓️ Phase 1 : Configuration & Backend Core (Semaine 1-2)

#### Semaine 1 : Setup & Base
- [ ] Installation environnement développement
- [ ] Création projet Symfony 7
- [ ] Configuration Docker
- [ ] Setup base de données MySQL
- [ ] Configuration Redis
- [ ] Entités Doctrine de base (User, Product, Category)
- [ ] Authentification Symfony Security

#### Semaine 2 : API Core
- [ ] API Platform configuration
- [ ] CRUD complet Products
- [ ] CRUD Categories & Suppliers
- [ ] Stock movements system
- [ ] JWT Authentication API
- [ ] Tests PHPUnit de base

### 🗓️ Phase 2 : Frontend React (Semaine 3-4)

#### Semaine 3 : Setup React & Auth
- [ ] Création projet React + TypeScript
- [ ] Configuration Tailwind CSS
- [ ] Setup React Router
- [ ] Composants authentification
- [ ] Services API avec Axios
- [ ] Login/Register pages

#### Semaine 4 : Dashboard & CRUD
- [ ] Dashboard principal avec KPIs
- [ ] Gestion produits (liste/création/édition)
- [ ] Gestion stock (entrées/sorties)
- [ ] Interface responsive
- [ ] Composants UI réutilisables

### 🗓️ Phase 3 : WebSocket & Chat (Semaine 5)
- [ ] Serveur WebSocket Node.js
- [ ] Intégration WebSocket dans Symfony
- [ ] Chat temps réel frontend
- [ ] Notifications en temps réel
- [ ] Events système (stock alerts, etc.)

### 🗓️ Phase 4 : IA & Finalisation (Semaine 6)
- [ ] Intégration API IA (OpenAI/Claude)
- [ ] Assistant IA pour stocks
- [ ] Analytics avancées
- [ ] Optimisation performances
- [ ] Documentation complète
- [ ] Tests finaux

## 🔧 Installation & Prérequis

### Logiciels à télécharger

#### 1. Environnement de base
```bash
# PHP 8.2+ (avec extensions)
# Sur Windows avec XAMPP ou WampServer
# Sur macOS avec Homebrew : brew install php@8.2
# Sur Linux : sudo apt install php8.2

# Extensions PHP requises
php8.2-mysql php8.2-redis php8.2-curl php8.2-mbstring 
php8.2-xml php8.2-zip php8.2-intl php8.2-gd
```

#### 2. Composer (gestionnaire de dépendances PHP)
```bash
# Téléchargement : https://getcomposer.org/download/
# Installation globale recommandée
```

#### 3. Symfony CLI
```bash
# Installation : https://symfony.com/download
# ou via Composer : composer global require symfony/cli
```

#### 4. Node.js & npm
```bash
# Version LTS : https://nodejs.org/
# Inclut npm automatiquement
```

#### 5. Docker Desktop
```bash
# https://www.docker.com/products/docker-desktop/
# Indispensable pour MySQL, Redis, et déploiement
```

#### 6. Base de données (via Docker recommandé)
```bash
# MySQL 8.0 via Docker
# Redis via Docker
# Ou installations locales si préféré
```

### Installation du projet

#### 1. Création du projet Symfony
```bash
# Créer le dossier principal
mkdir StockMaster-Pro && cd StockMaster-Pro

# Backend Symfony
symfony new backend --version="7.1.*" --webapp
cd backend

# Dépendances principales
composer require doctrine/orm
composer require symfony/security-bundle
composer require api-platform/core
composer require lexik/jwt-authentication-bundle
composer require symfony/messenger
composer require redis
composer require symfony/mailer

# Dépendances développement
composer require --dev symfony/test-pack
composer require --dev doctrine/doctrine-fixtures-bundle
```

#### 2. Configuration environnement
```bash
# Copier le fichier d'environnement
cp .env .env.local

# Configurer dans .env.local :
DATABASE_URL="mysql://root:password@127.0.0.1:3306/stockmaster?serverVersion=8.0"
REDIS_URL="redis://127.0.0.1:6379"
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase
```

#### 3. Frontend React
```bash
# Dans le dossier racine
npx create-react-app frontend --template typescript
cd frontend

# Dépendances
npm install axios react-router-dom @tanstack/react-query
npm install socket.io-client zustand
npm install tailwindcss @headlessui/react @heroicons/react
npm install chart.js react-chartjs-2
npm install @types/react @types/react-dom
```

#### 4. WebSocket Server
```bash
# Créer le serveur WebSocket
mkdir websocket-server && cd websocket-server
npm init -y
npm install socket.io express cors
npm install --save-dev nodemon @types/node
```

#### 5. Docker Setup
```bash
# Dans le dossier racine
touch docker-compose.yml
```

### Lancement rapide avec Docker
```bash
# Cloner/créer le projet
git clone [your-repo] StockMaster-Pro
cd StockMaster-Pro

# Copier la configuration
cp .env.example .env

# Démarrer tous les services
docker-compose up -d

# Accès :
# - Frontend : http://localhost:3000
# - Backend API : http://localhost:8000
# - Adminer (BDD) : http://localhost:8080
# - WebSocket : localhost:3001
```

### Lancement manuel (développement)
```bash
# Terminal 1 : Base de données
docker-compose up -d mysql redis

# Terminal 2 : Backend Symfony
cd backend
symfony server:start

# Terminal 3 : Frontend React  
cd frontend
npm start

# Terminal 4 : WebSocket Server
cd websocket-server
npm run dev
```

## 📚 Structure d'apprentissage

### Concepts PHP/Symfony couverts
- **Symfony Framework** : Architecture MVC, DI Container
- **Doctrine ORM** : Entités, Relations, Migrations, Repositories
- **API Platform** : Création d'APIs REST automatiques
- **Security Component** : Authentification, Autorisation, JWT
- **Symfony Messenger** : Queue system, Async processing
- **Event System** : Event Dispatcher, Subscribers
- **Services & DI** : Injection de dépendances
- **Tests** : PHPUnit, Fixtures, Functional tests

### Concepts React/TypeScript
- **Hooks avancés** : useState, useEffect, useContext, Custom hooks
- **State Management** : React Query pour server state, Zustand pour client state
- **TypeScript** : Interfaces, Types, Generic types
- **Performance** : React.memo, useMemo, useCallback
- **Real-time** : WebSocket integration
- **API Integration** : Axios, Error handling, Loading states

### Concepts DevOps
- **Docker** : Multi-container apps, Networks, Volumes
- **Nginx** : Reverse proxy, Load balancing
- **CI/CD** : GitHub Actions (à ajouter en bonus)

## 🧪 Tests

```bash
# Tests Backend (PHPUnit)
cd backend
php bin/phpunit

# Tests Frontend (Jest)
cd frontend  
npm test

# Tests E2E (optionnel avec Cypress)
npm run cypress:open
```

## 📊 Fonctionnalités détaillées

### Gestion des stocks
- Entrées/sorties de stock avec validation
- Calcul automatique des coûts (FIFO/LIFO/Average)
- Alertes de stock minimum
- Prévisions basées sur l'historique

### Chat en temps réel
- Canaux par équipe/projet
- Messages privés
- Notifications push
- Historique des conversations

### Assistant IA
- Recommandations de réapprovisionnement
- Analyse des tendances
- Prédictions de demande
- Optimisation des stocks

### Analytics
- KPIs temps réel
- Graphiques interactifs
- Exports personnalisables
- Rapports automatisés

## 🔐 Sécurité

- Authentification JWT sécurisée
- Validation des données côté serveur
- Protection CSRF
- Chiffrement des mots de passe
- Rate limiting API
- Sanitization des entrées

## 📱 Responsive Design

- Mobile-first approach
- Progressive Web App (PWA) features
- Offline capabilities basiques
- Touch-friendly interface

## 🚀 Déploiement Production

```bash
# Build optimisé
docker-compose -f docker-compose.prod.yml up -d

# Ou déploiement manuel
cd frontend && npm run build
cd backend && composer install --no-dev --optimize-autoloader
```

## 📖 Documentation

- [Guide d'installation détaillé](docs/installation.md)
- [Architecture et conception](docs/architecture.md)
- [API Documentation](docs/api.md)
- [Guide de développement](docs/development.md)
- [Déploiement production](docs/deployment.md)

## 🎨 Captures d'écran

[À ajouter : Dashboard, Chat, Gestion produits, Mobile view]

## 📈 Roadmap

### Version 2.0 (Futures fonctionnalités)
- [ ] Application mobile React Native
- [ ] Intégration comptabilité
- [ ] Scanner de codes-barres
- [ ] Géolocalisation des stocks
- [ ] Marketplace intégrée
- [ ] BI avancée avec Elasticsearch

## 🤝 Contribution

1. Fork le projet
2. Créez votre branche (`git checkout -b feature/NouvelleFonctionnalite`)
3. Commit vos changements (`git commit -m 'Ajout nouvelle fonctionnalité'`)
4. Push vers la branche (`git push origin feature/NouvelleFonctionnalite`)
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

## ⭐ Technologies utilisées

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-000000?style=flat-square&logo=symfony&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-007ACC?style=flat-square&logo=typescript&logoColor=white)
![React](https://img.shields.io/badge/React-20232A?style=flat-square&logo=react&logoColor=61DAFB)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=flat-square&logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-DC382D?style=flat-square&logo=redis&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker&logoColor=white)

---

*Ce projet est idéal pour apprendre le développement full-stack moderne avec PHP/Symfony et React, tout en découvrant les concepts de temps réel, IA, et DevOps.*
