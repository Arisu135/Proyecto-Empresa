@echo off
echo ========================================
echo   DEPLOY A RENDER
echo ========================================
echo.
echo Desplegando cambios a Render...
echo.

curl -X POST "https://api.render.com/deploy/srv-d4667i3ipnbc73bifuh0?key=itJ-lE-qB_k"

echo.
echo ========================================
echo   DEPLOY INICIADO
echo ========================================
echo.
echo Render esta construyendo tu aplicacion.
echo Revisa el progreso en: https://dashboard.render.com
echo.
pause
