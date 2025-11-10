#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Sistema de Impresión Automática para Kiosco
Consulta pedidos pendientes y los imprime automáticamente
"""

import requests
import time
import win32print
import win32api
from datetime import datetime

# ========== CONFIGURACIÓN ==========
RENDER_URL = "https://proyecto-empresa-web-eepa.onrender.com"
CHECK_INTERVAL = 3  # Segundos entre consultas
PRINTER_NAME = None  # None = impresora predeterminada

# ===================================

def get_default_printer():
    """Obtiene la impresora predeterminada"""
    try:
        return win32print.GetDefaultPrinter()
    except:
        return None

def get_pending_orders():
    """Consulta pedidos pendientes de impresión"""
    try:
        response = requests.get(f"{RENDER_URL}/api/print/pending", timeout=10)
        if response.status_code == 200:
            return response.json()
        return None
    except Exception as e:
        print(f"❌ Error al consultar pedidos: {e}")
        return None

def mark_as_printed(pedido_id):
    """Marca un pedido como impreso"""
    try:
        response = requests.post(f"{RENDER_URL}/api/print/{pedido_id}/mark-printed", timeout=10)
        return response.status_code == 200
    except Exception as e:
        print(f"❌ Error al marcar como impreso: {e}")
        return False

def format_ticket(pedido):
    """Formatea el ticket para impresión térmica"""
    ticket = []
    ticket.append("=" * 32)
    ticket.append("    REBEL JUNGLE CAFE")
    ticket.append("      Kiosco Digital")
    ticket.append("=" * 32)
    ticket.append("")
    ticket.append(f"Pedido: #{pedido['id']}")
    
    if pedido.get('numero_mesa'):
        ticket.append(f"Mesa: {pedido['numero_mesa']}")
    
    ticket.append(f"Cliente: {pedido['nombre_cliente']}")
    ticket.append(f"Fecha: {pedido['created_at']}")
    ticket.append(f"Metodo Pago: {pedido['metodo_pago'].upper()}")
    ticket.append("")
    ticket.append("-" * 32)
    ticket.append("PRODUCTOS:")
    ticket.append("-" * 32)
    
    for detalle in pedido['detalles']:
        cantidad = detalle['cantidad']
        nombre = detalle['nombre_producto']
        subtotal = detalle['subtotal']
        
        ticket.append(f"{cantidad}x {nombre}")
        ticket.append(f"{'':>20} S/ {subtotal:.2f}")
    
    ticket.append("")
    ticket.append("=" * 32)
    ticket.append(f"TOTAL PAGADO: S/ {pedido['total']:.2f}")
    ticket.append("=" * 32)
    ticket.append("")
    ticket.append("  Gracias por su compra!")
    ticket.append("   @rebel_jungle_cafe")
    ticket.append("")
    ticket.append("")
    
    return "\n".join(ticket)

def print_ticket(ticket_text, printer_name=None):
    """Imprime el ticket en la impresora especificada"""
    try:
        if printer_name is None:
            printer_name = get_default_printer()
        
        if not printer_name:
            print("❌ No se encontró impresora")
            return False
        
        hPrinter = win32print.OpenPrinter(printer_name)
        try:
            hJob = win32print.StartDocPrinter(hPrinter, 1, ("Ticket", None, "RAW"))
            try:
                win32print.StartPagePrinter(hPrinter)
                win32print.WritePrinter(hPrinter, ticket_text.encode('utf-8'))
                win32print.EndPagePrinter(hPrinter)
            finally:
                win32print.EndDocPrinter(hPrinter)
        finally:
            win32print.ClosePrinter(hPrinter)
        
        return True
    except Exception as e:
        print(f"❌ Error al imprimir: {e}")
        return False

def main():
    """Función principal del servidor de impresión"""
    print("=" * 50)
    print("🖨️  SERVIDOR DE IMPRESIÓN AUTOMÁTICA")
    print("=" * 50)
    print(f"📡 Conectando a: {RENDER_URL}")
    
    printer = PRINTER_NAME or get_default_printer()
    if printer:
        print(f"🖨️  Impresora: {printer}")
    else:
        print("⚠️  No se detectó impresora predeterminada")
    
    print(f"⏱️  Intervalo de consulta: {CHECK_INTERVAL} segundos")
    print("=" * 50)
    print("✅ Sistema iniciado. Presiona Ctrl+C para detener.")
    print("")
    
    consecutive_errors = 0
    
    while True:
        try:
            result = get_pending_orders()
            
            if result and result.get('success'):
                consecutive_errors = 0
                count = result.get('count', 0)
                
                if count > 0:
                    print(f"📋 {count} pedido(s) pendiente(s) de impresión")
                    
                    for pedido in result.get('pedidos', []):
                        pedido_id = pedido['id']
                        print(f"🖨️  Imprimiendo pedido #{pedido_id}...")
                        
                        ticket_text = format_ticket(pedido)
                        
                        if print_ticket(ticket_text, PRINTER_NAME):
                            if mark_as_printed(pedido_id):
                                print(f"✅ Pedido #{pedido_id} impreso correctamente")
                            else:
                                print(f"⚠️  Pedido #{pedido_id} impreso pero no se pudo marcar")
                        else:
                            print(f"❌ Error al imprimir pedido #{pedido_id}")
                        
                        time.sleep(1)
            else:
                consecutive_errors += 1
                if consecutive_errors == 1:
                    print(f"⚠️  Error de conexión ({datetime.now().strftime('%H:%M:%S')})")
            
            time.sleep(CHECK_INTERVAL)
            
        except KeyboardInterrupt:
            print("\n\n🛑 Deteniendo servidor de impresión...")
            break
        except Exception as e:
            print(f"❌ Error inesperado: {e}")
            time.sleep(CHECK_INTERVAL)

if __name__ == "__main__":
    main()
