const axios = require('axios');
const { exec } = require('child_process');
const fs = require('fs');
const path = require('path');
const os = require('os');

// Configuración
const RENDER_URL = 'https://proyecto-empresa-web-eepa.onrender.com';
const CHECK_INTERVAL = 500; // 0.5 segundos
let pedidosImpresos = [];

console.log('========================================');
console.log('  SISTEMA DE IMPRESION TERMICA');
console.log('========================================');
console.log('Conectando a:', RENDER_URL);
console.log('Intervalo:', CHECK_INTERVAL / 1000, 'segundos');
console.log('========================================');
console.log('Sistema iniciado. Presiona Ctrl+C para detener.\n');

async function consultarPedidos() {
  try {
    const response = await axios.get(`${RENDER_URL}/api/print/pending`);
    
    if (response.data && response.data.success) {
      const pedidos = response.data.pedidos;
      
      for (const pedido of pedidos) {
        if (!pedidosImpresos.includes(pedido.id)) {
          console.log(`[${new Date().toLocaleTimeString()}] Nuevo pedido #${pedido.id} - Imprimiendo...`);
          await imprimirPedido(pedido);
          pedidosImpresos.push(pedido.id);
          
          if (pedidosImpresos.length > 100) {
            pedidosImpresos.shift();
          }
        }
      }
    }
  } catch (error) {
    if (error.code === 'ECONNREFUSED' || error.code === 'ETIMEDOUT') {
      console.log(`[${new Date().toLocaleTimeString()}] Error de conexión`);
    }
  }
}

async function imprimirPedido(pedido) {
  try {
    // Generar ticket simple sin formato especial
    let ticket = '';
    ticket += 'REBEL JUNGLE CAFE Y PLANTAS\r\n';
    ticket += '================================\r\n';
    ticket += `${pedido.nombre_cliente}\r\n`;
    ticket += `${pedido.created_at}\r\n`;
    ticket += `${pedido.metodo_pago.toUpperCase()}\r\n`;
    ticket += '--------------------------------\r\n';

    pedido.detalles?.forEach(detalle => {
      ticket += `${detalle.cantidad}x ${detalle.nombre_producto}\r\n`;
      ticket += `   S/${parseFloat(detalle.subtotal).toFixed(2)}\r\n`;
    });

    ticket += '--------------------------------\r\n';
    ticket += `TOTAL: S/${parseFloat(pedido.total).toFixed(2)}\r\n`;
    ticket += '================================\r\n';
    ticket += 'Gracias por su compra!\r\n';
    ticket += '@rebel_jungle_cafe\r\n';
    ticket += '\r\n\r\n\r\n'; // Saltos de línea para cortar

    // Guardar en archivo temporal
    const tempFile = path.join(os.tmpdir(), `ticket_${pedido.id}.txt`);
    fs.writeFileSync(tempFile, ticket, 'utf8');

    console.log(`[${new Date().toLocaleTimeString()}] Imprimiendo pedido #${pedido.id}...`);

    // Imprimir directamente a la impresora predeterminada
    const printCommand = `print /D:PRN "${tempFile}"`;
    
    exec(printCommand, (error, stdout, stderr) => {
      if (error) {
        console.error(`[${new Date().toLocaleTimeString()}] ✗ Error:`, error.message);
        console.log('Archivo guardado en:', tempFile);
      } else {
        console.log(`[${new Date().toLocaleTimeString()}] ✓ Pedido #${pedido.id} impreso`);
        // Limpiar archivo después de 5 segundos
        setTimeout(() => {
          try { fs.unlinkSync(tempFile); } catch (e) {}
        }, 5000);
      }
    });
  } catch (err) {
    console.error(`[${new Date().toLocaleTimeString()}] ✗ Error:`, err.message);
  }
}

// Iniciar consulta periódica
setInterval(consultarPedidos, CHECK_INTERVAL);
consultarPedidos(); // Primera consulta inmediata
