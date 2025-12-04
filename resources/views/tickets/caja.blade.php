<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket Caja #{{ $pedido->id }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none; }
            @page { size: 80mm auto; margin: 0; }
        }
        body {
            font-family: 'Courier New', monospace;
            width: 72mm;
            max-width: 72mm;
            margin: 0 auto;
            padding: 4mm;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 24px;
        }
        .info {
            margin: 10px 0;
            font-size: 14px;
        }
        .info strong {
            display: inline-block;
            width: 80px;
        }
        .productos {
            border-top: 2px dashed #000;
            padding: 10px 0;
            margin: 10px 0;
        }
        .producto {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 14px;
        }
        .producto .nombre {
            flex: 1;
        }
        .producto .precio {
            text-align: right;
            min-width: 60px;
        }
        .total-section {
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        .total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            border-top: 2px dashed #000;
            padding-top: 10px;
        }
        .btn-print {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
        }
        .btn-close {
            background: #666;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn-print">🖨️ Imprimir</button>
        <button onclick="window.close()" class="btn-close">✖️ Cerrar</button>
    </div>

    <div class="header">
        <h1>💰 CAJA</h1>
        <div style="font-size: 18px; font-weight: bold;">PEDIDO #{{ $pedido->id }}</div>
    </div>

    <div class="info">
        <div><strong>Cliente:</strong> {{ $pedido->nombre_cliente }}</div>
        <div><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</div>
        @if($pedido->numero_mesa)
        <div><strong>Mesa:</strong> {{ $pedido->numero_mesa }}</div>
        @endif
        <div><strong>Tipo:</strong> {{ $pedido->tipo_pedido }}</div>
    </div>

    <div class="productos">
        @foreach($pedido->detalles as $detalle)
        <div class="producto">
            <div class="nombre">
                {{ $detalle->cantidad }}x {{ $detalle->nombre_producto }}
            </div>
            <div class="precio">
                S/ {{ number_format($detalle->subtotal, 2) }}
            </div>
        </div>
        @endforeach
    </div>

    <div class="total-section">
        <div class="total">
            <span>TOTAL:</span>
            <span>S/ {{ number_format($pedido->total, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        <div>================================</div>
        <div style="font-size: 14px; margin-top: 5px;">TICKET CAJA</div>
        <div>{{ now()->format('d/m/Y H:i:s') }}</div>
        <div style="margin-top: 10px;">¡Gracias por su compra!</div>
    </div>

    <script>
        // Auto-imprimir al cargar (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
