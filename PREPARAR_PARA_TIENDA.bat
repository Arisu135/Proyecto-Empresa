@echo off
title Preparar Archivos para la Tienda
color 0E
echo.
echo ========================================
echo   PREPARAR ARCHIVOS PARA LA TIENDA
echo ========================================
echo.
echo Este script creara una carpeta con todo
echo lo necesario para llevar a la tienda.
echo.
pause

echo.
echo Creando carpeta Kiosco-Instalador...
if exist "Kiosco-Instalador" rmdir /s /q "Kiosco-Instalador"
mkdir "Kiosco-Instalador"

echo.
echo Copiando archivos necesarios...
copy /Y "auto_print.py" "Kiosco-Instalador\" >nul
copy /Y "requirements.txt" "Kiosco-Instalador\" >nul
copy /Y "iniciar_impresora.bat" "Kiosco-Instalador\" >nul
copy /Y "INSTALAR_EN_TIENDA.bat" "Kiosco-Instalador\" >nul
copy /Y "INSTALACION_IMPRESORA.md" "Kiosco-Instalador\" >nul
copy /Y "INSTRUCCIONES_TIENDA.md" "Kiosco-Instalador\" >nul

echo.
echo ========================================
echo   ✅ CARPETA LISTA
echo ========================================
echo.
echo Carpeta creada: Kiosco-Instalador
echo.
echo Archivos incluidos:
echo   ✅ INSTALAR_EN_TIENDA.bat (PRINCIPAL)
echo   ✅ auto_print.py
echo   ✅ requirements.txt
echo   ✅ iniciar_impresora.bat
echo   ✅ INSTALACION_IMPRESORA.md
echo   ✅ INSTRUCCIONES_TIENDA.md
echo.
echo ========================================
echo   PASOS SIGUIENTES:
echo ========================================
echo.
echo 1. Copia la carpeta "Kiosco-Instalador"
echo    a una USB
echo.
echo 2. Lleva la USB a la tienda
echo.
echo 3. En la tienda:
echo    - Instala Python (python.org)
echo    - Ejecuta: INSTALAR_EN_TIENDA.bat
echo    - ¡Listo!
echo.
echo ========================================
echo.
pause

echo.
echo ¿Deseas abrir la carpeta ahora? (S/N)
set /p RESPUESTA=
if /i "%RESPUESTA%"=="S" (
    start "" "%cd%\Kiosco-Instalador"
)

echo.
echo ¡Listo! Presiona cualquier tecla para salir.
pause >nul
