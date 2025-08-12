# StockMaster Pro - Version Twig

## Vue d'ensemble

Cette version de StockMaster Pro utilise **Twig** comme moteur de template au lieu de React. Twig est intégré directement dans Symfony, offrant une approche plus traditionnelle et intégrée pour le développement web.

## Architecture

### Backend (Symfony 7.1)
- **PHP 8.2+** avec Symfony Framework
- **Doctrine ORM** pour la gestion de la base de données
- **Twig** pour le rendu des templates
- **Symfony Security** avec authentification JWT
- **API Platform** pour les API REST

### Frontend (Twig)
- **Templates Twig** intégrés dans Symfony
- **Tailwind CSS** pour le styling
- **Chart.js** pour les graphiques
- **JavaScript vanilla** pour l'interactivité

### Base de données
- **MySQL 8.0** pour les données principales
- **Redis** pour le cache et les sessions

## Structure des fichiers

```
StockMasterPro/
├── backend/                          # Application Symfony
│   ├── src/
│   │   ├── Controller/              # Contrôleurs Twig
│   │   ├── Entity/                  # Entités Doctrine
│   │   └── Repository/              # Repositories
│   ├── templates/                    # Templates Twig
│   │   ├── base.html.twig          # Template de base
│   │   ├── security/                # Pages d'authentification
│   │   ├── dashboard/               # Tableau de bord
│   │   └── product/                 # Gestion des produits
│   └── config/                      # Configuration Symfony
├── database/                         # Scripts de base de données
├── websocket-server/                 # Serveur WebSocket Node.js
└── scripts/                          # Scripts de démarrage
```

## Installation et démarrage

### 1. Prérequis
- Docker et Docker Compose
- PHP 8.2+
- Composer
- Node.js (pour le serveur WebSocket)

### 2. Démarrage rapide
```bash
# Démarrer les services Docker
scripts/start_twig.bat

# Dans le dossier backend/
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console server:start
```

### 3. Accès
- **Application** : http://localhost:8000
- **Adminer** : http://localhost:8080
- **API** : http://localhost:8000/api

## Fonctionnalités principales

### Tableau de bord
- **KPI** : Total produits, stock faible, valeur totale, croissance
- **Graphiques** : Mouvements de stock, distribution par catégorie, top produits
- **Actions rapides** : Ajouter produit, mouvement de stock, nouvelle commande
- **Activité récente** : Historique des actions

### Gestion des produits
- **Liste** avec filtres et pagination
- **CRUD** complet (Créer, Lire, Modifier, Supprimer)
- **Statuts de stock** : Faible, Normal, Élevé
- **Recherche** par nom, SKU, code-barres

### Interface utilisateur
- **Design responsive** avec Tailwind CSS
- **Navigation latérale** avec menu mobile
- **Thème moderne** avec icônes Font Awesome
- **Animations** et transitions fluides

## Avantages de Twig

### 1. Intégration native
- **Pas de build** nécessaire
- **Déploiement simplifié** (un seul serveur)
- **Développement plus rapide**

### 2. Performance
- **Rendu côté serveur** plus rapide
- **Moins de JavaScript** à charger
- **SEO optimisé** par défaut

### 3. Maintenance
- **Code centralisé** dans Symfony
- **Gestion des sessions** simplifiée
- **Débogage** plus facile

## Développement

### Créer un nouveau template
```twig
{# templates/mon_template.html.twig #}
{% extends 'base.html.twig' %}

{% block title %}Mon Titre{% endblock %}
{% block page_title %}Ma Page{% endblock %}

{% block body %}
    <!-- Contenu de la page -->
{% endblock %}
```

### Créer un nouveau contrôleur
```php
<?php
// src/Controller/MonController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonController extends AbstractController
{
    #[Route('/ma-route', name: 'app_ma_route')]
    public function index(): Response
    {
        return $this->render('mon_template.html.twig', [
            'data' => 'Valeur'
        ]);
    }
}
```

### Utiliser les données dans Twig
```twig
{% for item in items %}
    <div class="item">
        <h3>{{ item.name }}</h3>
        <p>{{ item.description }}</p>
        {% if item.isActive %}
            <span class="badge badge-success">Actif</span>
        {% endif %}
    </div>
{% endfor %}
```

## Personnalisation

### Modifier le thème
- **Couleurs** : Modifier `tailwind.config.js` dans le template de base
- **Layout** : Éditer `templates/base.html.twig`
- **Styles** : Ajouter des classes CSS personnalisées

### Ajouter des fonctionnalités
- **Nouveaux modules** : Créer contrôleur + templates
- **API** : Utiliser API Platform
- **WebSocket** : Étendre le serveur Node.js

## Déploiement

### Production
```bash
# Optimiser Symfony
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod

# Configurer le serveur web (Nginx/Apache)
# Pointer vers le dossier public/ de Symfony
```

### Docker
```bash
# Construire l'image
docker build -t stockmaster-pro .

# Lancer le conteneur
docker run -p 8000:8000 stockmaster-pro
```

## Support et contribution

Pour toute question ou contribution :
1. Consultez la documentation Symfony
2. Vérifiez les logs dans `var/log/`
3. Utilisez la barre de débogage Symfony en mode dev

## Migration depuis React

Si vous migrez depuis la version React :
1. **Supprimez** le dossier `frontend/`
2. **Adaptez** les contrôleurs pour Twig
3. **Convertissez** les composants React en templates Twig
4. **Testez** toutes les fonctionnalités

---

**StockMaster Pro** - Système de gestion d'inventaire moderne avec Twig et Symfony
