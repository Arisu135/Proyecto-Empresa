@echo off
echo ========================================
echo   DEPLOY FIX - Proyecto Empresa
echo ========================================
echo.

echo [1/4] Agregando cambios a Git...
git add .

echo.
echo [2/4] Haciendo commit...
git commit -m "Fix: Redirigir a confirmacion en lugar de cocina + agregar tabla sessions"

echo.
echo [3/4] Haciendo push a repositorio...
git push

echo.
echo [4/4] IMPORTANTE - Acciones en Render:
echo.
echo 1. Ve a tu Dashboard de Render: https://dashboard.render.com
echo 2. Selecciona tu servicio web
echo 3. Ve a la pestaña "Manual Deploy"
echo 4. Haz clic en "Deploy latest commit"
echo 5. Espera 5-10 minutos a que termine el deploy
echo.
echo NOTA: Las migraciones se ejecutaran automaticamente
echo       incluyendo la creacion de la tabla 'sessions'
echo.
echo ========================================
echo   Deploy preparado exitosamente!
echo ========================================
pause
