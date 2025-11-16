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
    // Generar contenido del ticket (32 caracteres)
    let ticket = '';
    ticket += 'REBEL JUNGLE CAFE Y PLANTAS\n';
    ticket += '================================\n';
    ticket += `${pedido.nombre_cliente}\n`;
    ticket += `${pedido.created_at}\n`;
    ticket += `${pedido.metodo_pago.toUpperCase()}\n`;
    ticket += '--------------------------------\n';

    pedido.detalles?.forEach(detalle => {
      ticket += `${detalle.cantidad}x ${detalle.nombre_producto}\n`;
      ticket += `   S/${parseFloat(detalle.subtotal).toFixed(2)}\n`;
    });

    ticket += '--------------------------------\n';
    ticket += `TOTAL: S/${parseFloat(pedido.total).toFixed(2)}\n`;
    ticket += '================================\n';
    ticket += 'Gracias por su compra!\n';
    ticket += '@rebel_jungle_cafe\n';

    // Guardar en archivo temporal
    const tempFile = path.join(os.tmpdir(), `ticket_${pedido.id}.txt`);
    fs.writeFileSync(tempFile, ticket, 'utf8');

    console.log(`[${new Date().toLocaleTimeString()}] Archivo creado: ${tempFile}`);
    console.log(`[${new Date().toLocaleTimeString()}] Intentando imprimir...`);

    // Método 1: Intentar con notepad /p
    const printCommand1 = `notepad /p "${tempFile}"`;
    
    exec(printCommand1, (error, stdout, stderr) => {
      if (error) {
        console.error(`[${new Date().toLocaleTimeString()}] ✗ Método 1 falló, intentando método 2...`);
        
        // Método 2: Intentar con print directo
        const printCommand2 = `print "${tempFile}"`;
        exec(printCommand2, (error2, stdout2, stderr2) => {
          if (error2) {
            console.error(`[${new Date().toLocaleTimeString()}] ✗ Método 2 falló`);
            console.error('Error:', error2.message);
            console.log('SOLUCIÓN: Abrir manualmente:', tempFile);
          } else {
            console.log(`[${new Date().toLocaleTimeString()}] ✓ Pedido #${pedido.id} impreso (método 2)`);
          }
        });
      } else {
        console.log(`[${new Date().toLocaleTimeString()}] ✓ Pedido #${pedido.id} impreso (método 1)`);
      }
      
      // Limpiar archivo temporal después de 10 segundos
      setTimeout(() => {
        try {
          fs.unlinkSync(tempFile);
        } catch (e) {}
      }, 10000);
    });
  } catch (err) {
    console.error(`[${new Date().toLocaleTimeString()}] ✗ Error al imprimir:`, err.message);
  }
}

// Iniciar consulta periódica
setInterval(consultarPedidos, CHECK_INTERVAL);
consultarPedidos(); // Primera consulta inmediata
