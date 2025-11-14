@echo off
title Sistema de Impresion Termica - Kiosco
color 0A
echo.
echo ========================================
echo   INSTALANDO DEPENDENCIAS...
echo ========================================
echo.
cd /d "%~dp0"
call npm install
echo.
echo ========================================
echo   INICIANDO SISTEMA...
echo ========================================
echo.
npm start
pause
