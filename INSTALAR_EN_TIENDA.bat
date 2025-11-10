@echo off
title Instalador Sistema de Impresion - Kiosco
color 0B
echo.
echo ========================================
echo   INSTALADOR SISTEMA DE IMPRESION
echo ========================================
echo.
echo Este script instalara todo automaticamente
echo.
pause

echo.
echo [1/5] Verificando Python...
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo ❌ Python NO esta instalado
    echo.
    echo Por favor instala Python desde:
    echo https://www.python.org/downloads/
    echo.
    echo IMPORTANTE: Marca "Add Python to PATH"
    echo.
    pause
    exit /b 1
)
echo ✅ Python instalado correctamente

echo.
echo [2/5] Creando carpeta C:\Kiosco...
if not exist "C:\Kiosco" mkdir "C:\Kiosco"
echo ✅ Carpeta creada

echo.
echo [3/5] Copiando archivos...
copy /Y "%~dp0auto_print.py" "C:\Kiosco\" >nul
copy /Y "%~dp0requirements.txt" "C:\Kiosco\" >nul
copy /Y "%~dp0iniciar_impresora.bat" "C:\Kiosco\" >nul
copy /Y "%~dp0INSTALACION_IMPRESORA.md" "C:\Kiosco\" >nul
echo ✅ Archivos copiados

echo.
echo [4/5] Instalando dependencias Python...
echo (Esto puede tardar 1-2 minutos)
cd /d "C:\Kiosco"
python -m pip install --upgrade pip >nul 2>&1
python -m pip install -r requirements.txt
if %errorlevel% neq 0 (
    echo ❌ Error al instalar dependencias
    pause
    exit /b 1
)
echo ✅ Dependencias instaladas

echo.
echo [5/5] Configurando inicio automatico...
set STARTUP_FOLDER=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup
if not exist "%STARTUP_FOLDER%\Impresora-Kiosco.bat" (
    echo @echo off > "%STARTUP_FOLDER%\Impresora-Kiosco.bat"
    echo cd /d "C:\Kiosco" >> "%STARTUP_FOLDER%\Impresora-Kiosco.bat"
    echo start "" "C:\Kiosco\iniciar_impresora.bat" >> "%STARTUP_FOLDER%\Impresora-Kiosco.bat"
    echo ✅ Inicio automatico configurado
) else (
    echo ⚠️  Inicio automatico ya estaba configurado
)

echo.
echo ========================================
echo   ✅ INSTALACION COMPLETADA
echo ========================================
echo.
echo El sistema esta listo para usar.
echo.
echo Para iniciar ahora:
echo   1. Abre: C:\Kiosco\iniciar_impresora.bat
echo.
echo Para probar:
echo   1. Desde tablet, marca un pedido como pagado
echo   2. El programa imprimira automaticamente
echo.
echo El programa se iniciara automaticamente
echo al encender la PC.
echo.
pause

echo.
echo ¿Deseas iniciar el programa ahora? (S/N)
set /p RESPUESTA=
if /i "%RESPUESTA%"=="S" (
    echo.
    echo Iniciando programa...
    start "" "C:\Kiosco\iniciar_impresora.bat"
)

echo.
echo ¡Listo! Presiona cualquier tecla para salir.
pause >nul
