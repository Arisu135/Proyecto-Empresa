<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',      // Marrón Oscuro
                        'brand-accent': '#ff9800',    // Naranja (Énfasis)
                        'btn-green': '#4CAF50',        // Verde para el botón de Acción
                        'soft-border': '#e5e7eb',      // Gris muy claro para bordes
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
    </style>
</head>
<body>

    <div class="max-w-xl w-full mx-auto mt-8 bg-white rounded-xl shadow-2xl overflow-hidden"> 
        
        <div class="bg-btn-green text-white p-6 md:p-8 flex items-center justify-between">
            <h1 class="text-2xl md:text-3xl font-bold">✅ Pedido Confirmado</h1>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="p-6 md:p-8">
            <p class="text-xl text-gray-700 mb-6">¡Gracias! Tu pedido ha sido enviado a cocina.</p>
            
            <h2 class="text-xl font-bold text-brand-dark mb-4 border-b pb-2">Detalles del Pedido:</h2> 

            {{-- Aquí se muestran los 4 datos clave --}}
            <div class="space-y-3 mb-8 text-lg">
                <p><strong>Número de Pedido:</strong> <span class="text-brand-accent font-extrabold text-2xl">#{{ $pedido->id ?? '???' }}</span></p>
                
                {{-- Asumiendo que el nombre es "Cliente 1" si no se ha implementado la captura --}}
                <p><strong>Cliente:</strong> <span class="font-semibold">{{ $pedido->nombre_cliente ?? 'Cliente Kiosco' }}</span></p>
                
                <p><strong>Fecha y Hora:</strong> <span>{{ optional($pedido->created_at)->format('d/m/Y H:i') ?? 'N/A' }}</span></p>

                <p><strong>Estado:</strong> 
                    <span class="font-bold text-red-500">
                        {{ $pedido->estado ?? 'En Preparación' }}
                    </span>
                </p>

                <div class="pt-4 border-t border-soft-border mt-4">
                    <p class="text-2xl font-extrabold text-brand-dark">TOTAL PAGADO:</p>
                    <p class="text-3xl font-extrabold text-btn-green">S/. {{ number_format($pedido->total ?? 0, 2) }}</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mb-3">Productos Incluidos:</h3>
            <ul class="list-disc list-inside space-y-1 text-gray-600 ml-4">
                {{-- Usar la relación 'detalles' y los campos reales del modelo PedidoDetalle --}}
                @forelse ($pedido->detalles ?? [] as $item)
                    <li>{{ $item->cantidad }}x {{ $item->nombre_producto }} - S/. {{ number_format($item->subtotal, 2) }}</li>
                @empty
                    <li>No se encontraron detalles de productos.</li>
                @endforelse
            </ul>
        </div>

        <div class="p-6 md:p-8 pt-0 flex flex-col space-y-3">
            {{-- Botón para volver al menú principal --}}
            <a href="{{ route('productos.menu') }}" class="w-full text-center py-3 bg-brand-dark text-white font-bold rounded-xl shadow-lg hover:bg-brand-dark/90 transition duration-150 text-lg">
                Volver al Menú Principal
            </a>
        </div>

    </div>

    <script>
    // Imprimir ticket automáticamente al cargar la página
    window.addEventListener('load', function() {
        setTimeout(function() {
            imprimirTicketAutomatico();
        }, 500);
    });

    function imprimirTicketAutomatico() {
        const pedidoId = {{ $pedido->id }};
        const mesa = '{{ $pedido->numero_mesa ?? "" }}';
        const cliente = '{{ $pedido->nombre_cliente }}';
        const total = {{ $pedido->total }};
        const fecha = new Date('{{ $pedido->created_at }}').toLocaleString('es-PE', { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit' 
        });
        
        let itemsHTML = '';
        @foreach($pedido->detalles as $item)
            itemsHTML += `
                <tr>
                    <td>{{ $item->cantidad }}x</td>
                    <td>{{ $item->nombre_producto }}</td>
                    <td style="text-align: right;">S/ {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            `;
        @endforeach
        
        const ventana = window.open('', '_blank', 'width=300,height=600');
        ventana.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Ticket #${pedidoId}</title>
                <style>
                    @media print {
                        @page { margin: 0; size: 80mm auto; }
                        body { margin: 0; }
                    }
                    body {
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        width: 80mm;
                        margin: 0 auto;
                        padding: 5mm;
                    }
                    .center { text-align: center; }
                    .bold { font-weight: bold; }
                    .line { border-top: 1px dashed #000; margin: 5px 0; }
                    .double-line { border-top: 2px solid #000; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    td { padding: 2px 0; }
                    .total-row { font-size: 14px; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="center bold" style="font-size: 16px; margin-bottom: 5px;">
                    REBEL JUNGLE CAFE
                </div>
                <div class="center" style="font-size: 10px; margin-bottom: 10px;">
                    Kiosco Digital
                </div>
                <div class="line"></div>
                
                <table>
                    <tr>
                        <td class="bold">Pedido:</td>
                        <td style="text-align: right;">#${pedidoId}</td>
                    </tr>
                    ${mesa ? `<tr><td class="bold">Mesa:</td><td style="text-align: right;">${mesa}</td></tr>` : ''}
                    <tr>
                        <td class="bold">Cliente:</td>
                        <td style="text-align: right;">${cliente}</td>
                    </tr>
                    <tr>
                        <td class="bold">Fecha:</td>
                        <td style="text-align: right;">${fecha}</td>
                    </tr>
                </table>
                
                <div class="double-line"></div>
                
                <table>
                    ${itemsHTML}
                </table>
                
                <div class="double-line"></div>
                
                <table class="total-row">
                    <tr>
                        <td>TOTAL:</td>
                        <td style="text-align: right;">S/ ${parseFloat(total).toFixed(2)}</td>
                    </tr>
                </table>
                
                <div class="line"></div>
                <div class="center" style="margin-top: 10px; font-size: 10px;">
                    ¡Gracias por su compra!
                </div>
                <div class="center" style="font-size: 10px;">
                    @rebel_jungle_cafe
                </div>
            </body>
            </html>
        `);
        ventana.document.close();
        
        // Esperar a que cargue y luego imprimir
        setTimeout(() => {
            ventana.print();
        }, 250);
    }
    </script>

</body>
</html>
