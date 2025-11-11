<?php
/**
 * Sistema Local de Impresión Automática
 * Consulta pedidos pagados y los imprime automáticamente
 */

// Configuración
$RENDER_URL = "https://proyecto-empresa-web-eepa.onrender.com";
$CHECK_INTERVAL = 3; // segundos
$PRINTER_NAME = ""; // Dejar vacío para impresora predeterminada

// IDs de pedidos ya impresos (en memoria)
$pedidos_impresos = [];

echo "========================================\n";
echo "  SISTEMA LOCAL DE IMPRESION\n";
echo "========================================\n";
echo "Conectando a: $RENDER_URL\n";
echo "Intervalo: $CHECK_INTERVAL segundos\n";
echo "========================================\n";
echo "Sistema iniciado. Presiona Ctrl+C para detener.\n\n";

while (true) {
    try {
        // Consultar pedidos
        $response = @file_get_contents("$RENDER_URL/api/print/pending");
        
        if ($response === false) {
            echo "[" . date('H:i:s') . "] Error de conexión\n";
            sleep($CHECK_INTERVAL);
            continue;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !$data['success']) {
            sleep($CHECK_INTERVAL);
            continue;
        }
        
        // Procesar pedidos
        foreach ($data['pedidos'] as $pedido) {
            $pedido_id = $pedido['id'];
            
            // Si ya fue impreso, saltar
            if (in_array($pedido_id, $pedidos_impresos)) {
                continue;
            }
            
            echo "[" . date('H:i:s') . "] Nuevo pedido #$pedido_id - Imprimiendo...\n";
            
            // Generar ticket
            $ticket = generarTicket($pedido);
            
            // Imprimir
            if (imprimirTicket($ticket, $PRINTER_NAME)) {
                $pedidos_impresos[] = $pedido_id;
                echo "[" . date('H:i:s') . "] ✓ Pedido #$pedido_id impreso\n";
                
                // Mantener solo últimos 100 IDs en memoria
                if (count($pedidos_impresos) > 100) {
                    array_shift($pedidos_impresos);
                }
            } else {
                echo "[" . date('H:i:s') . "] ✗ Error al imprimir pedido #$pedido_id\n";
            }
        }
        
        sleep($CHECK_INTERVAL);
        
    } catch (Exception $e) {
        echo "[" . date('H:i:s') . "] Error: " . $e->getMessage() . "\n";
        sleep($CHECK_INTERVAL);
    }
}

function generarTicket($pedido) {
    $ticket = str_repeat("=", 32) . "\n";
    $ticket .= "    REBEL JUNGLE CAFE\n";
    $ticket .= "      Kiosco Digital\n";
    $ticket .= str_repeat("=", 32) . "\n\n";
    $ticket .= "Pedido: #{$pedido['id']}\n";
    
    if (!empty($pedido['numero_mesa'])) {
        $ticket .= "Mesa: {$pedido['numero_mesa']}\n";
    }
    
    $ticket .= "Cliente: {$pedido['nombre_cliente']}\n";
    $ticket .= "Fecha: {$pedido['created_at']}\n";
    $ticket .= "Metodo Pago: " . strtoupper($pedido['metodo_pago']) . "\n\n";
    $ticket .= str_repeat("-", 32) . "\n";
    $ticket .= "PRODUCTOS:\n";
    $ticket .= str_repeat("-", 32) . "\n";
    
    foreach ($pedido['detalles'] as $detalle) {
        $ticket .= "{$detalle['cantidad']}x {$detalle['nombre_producto']}\n";
        $ticket .= str_repeat(" ", 20) . "S/ " . number_format($detalle['subtotal'], 2) . "\n";
    }
    
    $ticket .= "\n" . str_repeat("=", 32) . "\n";
    $ticket .= "TOTAL PAGADO: S/ " . number_format($pedido['total'], 2) . "\n";
    $ticket .= str_repeat("=", 32) . "\n\n";
    $ticket .= "  Gracias por su compra!\n";
    $ticket .= "   @rebel_jungle_cafe\n\n\n";
    
    return $ticket;
}

function imprimirTicket($ticket, $printer_name = "") {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows
        $temp_file = tempnam(sys_get_temp_dir(), 'ticket_');
        file_put_contents($temp_file, $ticket);
        
        if (empty($printer_name)) {
            // Impresora predeterminada
            exec("print /D:PRN \"$temp_file\"", $output, $return);
        } else {
            exec("print /D:\"$printer_name\" \"$temp_file\"", $output, $return);
        }
        
        unlink($temp_file);
        return $return === 0;
    } else {
        // Linux/Mac
        if (empty($printer_name)) {
            exec("echo " . escapeshellarg($ticket) . " | lpr", $output, $return);
        } else {
            exec("echo " . escapeshellarg($ticket) . " | lpr -P " . escapeshellarg($printer_name), $output, $return);
        }
        return $return === 0;
    }
}
