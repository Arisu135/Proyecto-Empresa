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
    // Generar contenido del ticket
    let ticket = '';
    ticket += 'REBEL JUNGLE CAFE Y PLANTAS\n';
    ticket += '________________________________\n';
    ticket += `${pedido.nombre_cliente}\n`;
    ticket += `${pedido.created_at}\n`;
    ticket += `${pedido.metodo_pago.toUpperCase()}\n`;
    ticket += '________________________________\n';

    pedido.detalles?.forEach(detalle => {
      ticket += `${detalle.cantidad}x ${detalle.nombre_producto}\n`;
      ticket += `              S/${parseFloat(detalle.subtotal).toFixed(2)}\n`;
    });

    ticket += '________________________________\n';
    ticket += `TOTAL: S/${parseFloat(pedido.total).toFixed(2)}\n`;
    ticket += '________________________________\n';
    ticket += 'Gracias por su compra!\n';
    ticket += '@rebel_jungle_cafe\n';

    // Guardar en archivo temporal
    const tempFile = path.join(os.tmpdir(), `ticket_${pedido.id}.txt`);
    fs.writeFileSync(tempFile, ticket, 'utf8');

    // Imprimir usando comando de Windows
    const printCommand = `notepad /p "${tempFile}"`;
    
    exec(printCommand, (error, stdout, stderr) => {
      if (error) {
        console.error(`[${new Date().toLocaleTimeString()}] ✗ Error al imprimir pedido #${pedido.id}:`, error.message);
        console.error('Detalles:', stderr);
      } else {
        console.log(`[${new Date().toLocaleTimeString()}] ✓ Pedido #${pedido.id} enviado a impresora`);
        console.log('Salida:', stdout);
      }
      
      // Limpiar archivo temporal después de 5 segundos
      setTimeout(() => {
        try {
          fs.unlinkSync(tempFile);
        } catch (e) {}
      }, 5000);
    });
  } catch (err) {
    console.error(`[${new Date().toLocaleTimeString()}] ✗ Error al imprimir:`, err.message);
  }
}

// Iniciar consulta periódica
setInterval(consultarPedidos, CHECK_INTERVAL);
consultarPedidos(); // Primera consulta inmediata
