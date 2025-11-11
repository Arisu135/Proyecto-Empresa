@echo off
title Sistema de Impresion Local - Kiosco
color 0A
echo.
echo ========================================
echo   SISTEMA DE IMPRESION LOCAL
echo ========================================
echo.
cd /d "%~dp0"
php print_local.php
pause
