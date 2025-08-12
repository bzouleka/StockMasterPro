@echo off
echo ========================================
echo StockMaster Pro - Demarrage avec Twig
echo ========================================
echo.

echo 1. Demarrage des services Docker...
docker-compose up -d

echo.
echo 2. Attente du demarrage de MySQL...
timeout /t 10 /nobreak >nul

echo.
echo 3. Verification de la connexion MySQL...
docker-compose exec mysql mysql -u root -proot -e "SELECT 1;" >nul 2>&1
if %errorlevel% neq 0 (
    echo ERREUR: Impossible de se connecter a MySQL
    echo Attendez quelques secondes et relancez le script
    pause
    exit /b 1
)

echo.
echo 4. Services demarres avec succes !
echo.
echo ========================================
echo URLs d'acces:
echo ========================================
echo - Application principale: http://localhost:8000
echo - Adminer (MySQL): http://localhost:8080
echo - API: http://localhost:8000/api
echo.
echo ========================================
echo Prochaines etapes:
echo ========================================
echo 1. Ouvrez un terminal dans le dossier backend/
echo 2. Installez les dependances: composer install
echo 3. Configurez la base de donnees: php bin/console doctrine:database:create
echo 4. Lancez les migrations: php bin/console doctrine:migrations:migrate
echo 5. Demarrez le serveur: php bin/console server:start
echo.
echo ========================================
echo Serveur WebSocket (optionnel):
echo ========================================
echo Pour le chat en temps reel:
echo 1. Ouvrez un terminal dans le dossier websocket-server/
echo 2. Installez les dependances: npm install
echo 3. Demarrez le serveur: npm start
echo.
pause
