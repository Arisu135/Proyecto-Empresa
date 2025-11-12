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
    // Comandos ESC/POS para formato
    $ESC = chr(27);
    $GS = chr(29);
    $INIT = $ESC . "@"; // Inicializar impresora
    $BOLD_ON = $ESC . "E" . chr(1); // Negrita ON
    $BOLD_OFF = $ESC . "E" . chr(0); // Negrita OFF
    $DOUBLE_ON = $GS . "!" . chr(17); // Doble tamaño (ancho y alto)
    $DOUBLE_OFF = $GS . "!" . chr(0); // Tamaño normal
    $CENTER = $ESC . "a" . chr(1); // Centrar
    $LEFT = $ESC . "a" . chr(0); // Alinear izquierda
    
    $ticket = $INIT; // Inicializar
    $ticket .= $CENTER . $BOLD_ON;
    $ticket .= "REBEL JUNGLE CAFE\n";
    $ticket .= "Kiosco Digital\n";
    $ticket .= $BOLD_OFF . $LEFT;
    $ticket .= str_repeat("=", 32) . "\n\n";
    
    $ticket .= $BOLD_ON . "Pedido: #{$pedido['id']}\n" . $BOLD_OFF;
    
    if (!empty($pedido['numero_mesa'])) {
        $ticket .= $BOLD_ON . "Mesa: {$pedido['numero_mesa']}\n" . $BOLD_OFF;
    }
    
    $ticket .= "Cliente: {$pedido['nombre_cliente']}\n";
    $ticket .= "Fecha: {$pedido['created_at']}\n";
    $ticket .= "Metodo Pago: " . strtoupper($pedido['metodo_pago']) . "\n\n";
    
    $ticket .= str_repeat("-", 32) . "\n";
    $ticket .= $BOLD_ON . "PRODUCTOS:\n" . $BOLD_OFF;
    $ticket .= str_repeat("-", 32) . "\n\n";
    
    // PRODUCTOS EN GRANDE Y NEGRITA
    foreach ($pedido['detalles'] as $detalle) {
        $ticket .= $DOUBLE_ON . $BOLD_ON;
        $ticket .= "{$detalle['cantidad']}x {$detalle['nombre_producto']}\n";
        $ticket .= $DOUBLE_OFF . $BOLD_OFF;
        $ticket .= "    S/ " . number_format($detalle['subtotal'], 2) . "\n\n";
    }
    
    $ticket .= str_repeat("=", 32) . "\n";
    $ticket .= $BOLD_ON . $DOUBLE_ON;
    $ticket .= "TOTAL: S/ " . number_format($pedido['total'], 2) . "\n";
    $ticket .= $DOUBLE_OFF . $BOLD_OFF;
    $ticket .= str_repeat("=", 32) . "\n\n";
    
    $ticket .= $CENTER;
    $ticket .= "Gracias por su compra!\n";
    $ticket .= "@rebel_jungle_cafe\n\n\n";
    $ticket .= $LEFT;
    
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
