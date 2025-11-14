const ThermalPrinter = require('node-thermal-printer');
const axios = require('axios');

// Configuración
const RENDER_URL = 'https://proyecto-empresa-web-eepa.onrender.com';
const CHECK_INTERVAL = 3000; // 3 segundos
let pedidosImpresos = [];

// Configurar impresora térmica (Xprinter)
const printer = new ThermalPrinter.printer({
  type: 'epson',        // Tipo de impresora
  interface: 'usb',     // Puerto de conexión
  width: 42,            // Ancho de caracteres
  characterSet: 'SLOVENIA',
  removeSpecialCharacters: false,
  lineCharacter: "-",
});

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
    printer.clear();

    printer.alignCenter();
    printer.bold(true);
    printer.setTextDoubleHeight();
    printer.printLine("REBEL JUNGLE CAFE");
    printer.setTextNormal();
    printer.bold(false);
    printer.printLine("Kiosco Digital");
    printer.drawLine();

    printer.alignLeft();
    printer.bold(true);
    printer.printLine(`Pedido: #${pedido.id}`);
    printer.bold(false);
    
    if (pedido.numero_mesa) {
      printer.bold(true);
      printer.printLine(`Mesa: ${pedido.numero_mesa}`);
      printer.bold(false);
    }
    
    printer.printLine(`Cliente: ${pedido.nombre_cliente}`);
    printer.printLine(`Fecha: ${pedido.created_at}`);
    printer.printLine(`Metodo Pago: ${pedido.metodo_pago.toUpperCase()}`);
    printer.newLine();
    
    printer.drawLine();
    printer.bold(true);
    printer.printLine("PRODUCTOS:");
    printer.bold(false);
    printer.drawLine();
    printer.newLine();

    pedido.detalles?.forEach(detalle => {
      printer.bold(true);
      printer.setTextDoubleHeight();
      printer.printLine(`${detalle.cantidad}x ${detalle.nombre_producto}`);
      printer.setTextNormal();
      printer.bold(false);
      printer.printLine(`    S/ ${parseFloat(detalle.subtotal).toFixed(2)}`);
      printer.newLine();
    });

    printer.drawLine();
    printer.bold(true);
    printer.setTextDoubleHeight();
    printer.printLine(`TOTAL: S/ ${parseFloat(pedido.total).toFixed(2)}`);
    printer.setTextNormal();
    printer.bold(false);
    printer.drawLine();
    printer.newLine();
    
    printer.alignCenter();
    printer.printLine("Gracias por su compra!");
    printer.printLine("@rebel_jungle_cafe");
    printer.newLine();
    printer.newLine();
    printer.cut();

    const success = await printer.execute();
    if (success) {
      console.log(`[${new Date().toLocaleTimeString()}] ✓ Pedido #${pedido.id} impreso correctamente`);
    } else {
      console.error(`[${new Date().toLocaleTimeString()}] ✗ Falló la impresión del pedido #${pedido.id}`);
    }
  } catch (err) {
    console.error(`[${new Date().toLocaleTimeString()}] ✗ Error al imprimir:`, err.message);
  }
}

// Iniciar consulta periódica
setInterval(consultarPedidos, CHECK_INTERVAL);
consultarPedidos(); // Primera consulta inmediata
