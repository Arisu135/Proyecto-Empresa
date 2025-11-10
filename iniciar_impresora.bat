@echo off
title Sistema de Impresion Automatica - Kiosco
color 0A
echo.
echo ========================================
echo   SISTEMA DE IMPRESION AUTOMATICA
echo ========================================
echo.
cd /d "%~dp0"
python auto_print.py
pause
