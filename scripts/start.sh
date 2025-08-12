#!/bin/bash

echo "🚀 Démarrage de StockMaster Pro..."

# Vérifier si Docker est en cours d'exécution
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker n'est pas en cours d'exécution. Veuillez démarrer Docker Desktop."
    exit 1
fi

# Démarrer les services Docker
echo "🐳 Démarrage des services Docker..."
docker-compose up -d

# Attendre que MySQL soit prêt
echo "⏳ Attente que MySQL soit prêt..."
sleep 10

# Vérifier le statut des services
echo "📊 Statut des services:"
docker-compose ps

echo ""
echo "✅ Services démarrés avec succès!"
echo ""
echo "🌐 Accès aux services:"
echo "   - Frontend: http://localhost:3000"
echo "   - Backend API: http://localhost:8000"
echo "   - Adminer (BDD): http://localhost:8080"
echo "   - WebSocket: localhost:3001"
echo ""
echo "📝 Prochaines étapes:"
echo "   1. Ouvrir un terminal et lancer: cd backend && symfony server:start"
echo "   2. Ouvrir un autre terminal et lancer: cd frontend && npm start"
echo "   3. Ouvrir un autre terminal et lancer: cd websocket-server && npm run dev"
echo ""
echo "🔑 Compte de démo:"
echo "   Email: admin@stockmaster.com"
echo "   Mot de passe: admin123"
