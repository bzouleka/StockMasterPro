const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
require('dotenv').config();

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
  cors: {
    origin: process.env.FRONTEND_URL || "http://localhost:3000",
    methods: ["GET", "POST"]
  }
});

// Middleware
app.use(cors());
app.use(express.json());

// Stockage en mémoire des utilisateurs connectés et des messages
const connectedUsers = new Map();
const chatHistory = new Map();
const channels = ['general', 'stock', 'support', 'admin'];

// Initialiser l'historique des canaux
channels.forEach(channel => {
  chatHistory.set(channel, []);
});

// Gestion des connexions WebSocket
io.on('connection', (socket) => {
  console.log(`Nouvelle connexion: ${socket.id}`);

  // Authentification de l'utilisateur
  socket.on('authenticate', (userData) => {
    const { userId, email, firstName, lastName } = userData;
    
    // Stocker les informations de l'utilisateur
    connectedUsers.set(socket.id, {
      id: userId,
      email,
      firstName,
      lastName,
      socketId: socket.id,
      connectedAt: new Date()
    });

    // Rejoindre le canal général par défaut
    socket.join('general');
    
    // Notifier les autres utilisateurs
    socket.to('general').emit('userJoined', {
      user: { firstName, lastName },
      message: `${firstName} a rejoint le chat`,
      timestamp: new Date()
    });

    // Envoyer l'historique du canal
    const history = chatHistory.get('general') || [];
    socket.emit('chatHistory', { channel: 'general', messages: history });

    console.log(`Utilisateur authentifié: ${firstName} ${lastName}`);
  });

  // Rejoindre un canal
  socket.on('joinChannel', (channelName) => {
    if (channels.includes(channelName)) {
      socket.join(channelName);
      const history = chatHistory.get(channelName) || [];
      socket.emit('chatHistory', { channel: channelName, messages: history });
      console.log(`Utilisateur a rejoint le canal: ${channelName}`);
    }
  });

  // Envoyer un message
  socket.on('sendMessage', (data) => {
    const { channel, message, isPrivate, recipientId } = data;
    const user = connectedUsers.get(socket.id);
    
    if (!user) {
      socket.emit('error', { message: 'Utilisateur non authentifié' });
      return;
    }

    const messageData = {
      id: Date.now().toString(),
      userId: user.id,
      userEmail: user.email,
      firstName: user.firstName,
      lastName: user.lastName,
      message,
      channel,
      isPrivate: isPrivate || false,
      recipientId: recipientId || null,
      timestamp: new Date()
    };

    if (isPrivate && recipientId) {
      // Message privé
      const recipientSocket = Array.from(connectedUsers.entries())
        .find(([_, userData]) => userData.id === recipientId);
      
      if (recipientSocket) {
        socket.emit('privateMessage', messageData);
        io.to(recipientSocket[0]).emit('privateMessage', messageData);
      }
    } else {
      // Message public
      if (channels.includes(channel)) {
        // Stocker dans l'historique
        if (!chatHistory.has(channel)) {
          chatHistory.set(channel, []);
        }
        chatHistory.get(channel).push(messageData);
        
        // Limiter l'historique à 100 messages
        if (chatHistory.get(channel).length > 100) {
          chatHistory.get(channel).shift();
        }

        // Diffuser le message
        io.to(channel).emit('newMessage', messageData);
      }
    }

    console.log(`Message envoyé dans ${channel}: ${message}`);
  });

  // Typing indicator
  socket.on('typing', (data) => {
    const { channel, isTyping } = data;
    const user = connectedUsers.get(socket.id);
    
    if (user && channel) {
      socket.to(channel).emit('userTyping', {
        userId: user.id,
        firstName: user.firstName,
        isTyping
      });
    }
  });

  // Déconnexion
  socket.on('disconnect', () => {
    const user = connectedUsers.get(socket.id);
    
    if (user) {
      // Notifier les autres utilisateurs
      socket.to('general').emit('userLeft', {
        user: { firstName: user.firstName, lastName: user.lastName },
        message: `${user.firstName} a quitté le chat`,
        timestamp: new Date()
      });

      // Supprimer de la liste des utilisateurs connectés
      connectedUsers.delete(socket.id);
      console.log(`Utilisateur déconnecté: ${user.firstName} ${user.lastName}`);
    }
  });

  // Gestion des erreurs
  socket.on('error', (error) => {
    console.error('Erreur WebSocket:', error);
  });
});

// Routes API pour le serveur WebSocket
app.get('/api/status', (req, res) => {
  res.json({
    status: 'online',
    connectedUsers: connectedUsers.size,
    channels: channels,
    uptime: process.uptime()
  });
});

app.get('/api/users', (req, res) => {
  const users = Array.from(connectedUsers.values()).map(user => ({
    id: user.id,
    email: user.email,
    firstName: user.firstName,
    lastName: user.lastName,
    connectedAt: user.connectedAt
  }));
  
  res.json(users);
});

app.get('/api/channels/:channel/messages', (req, res) => {
  const { channel } = req.params;
  const messages = chatHistory.get(channel) || [];
  res.json(messages);
});

// Gestion des erreurs globales
process.on('uncaughtException', (error) => {
  console.error('Exception non capturée:', error);
});

process.on('unhandledRejection', (reason, promise) => {
  console.error('Promesse rejetée non gérée:', reason);
});

const PORT = process.env.PORT || 3001;

server.listen(PORT, () => {
  console.log(`🚀 Serveur WebSocket démarré sur le port ${PORT}`);
  console.log(`📡 Canaux disponibles: ${channels.join(', ')}`);
  console.log(`🌐 CORS activé pour: ${process.env.FRONTEND_URL || 'http://localhost:3000'}`);
});

module.exports = { app, server, io };
