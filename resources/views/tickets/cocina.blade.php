<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket Cocina #{{ $pedido->id }}</title>
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
            font-size: 16px;
        }
        .info strong {
            display: inline-block;
            width: 100px;
        }
        .productos {
            border-top: 2px dashed #000;
            border-bottom: 2px dashed #000;
            padding: 10px 0;
            margin: 10px 0;
        }
        .producto {
            margin: 8px 0;
            font-size: 16px;
        }
        .producto .cantidad {
            font-weight: bold;
            font-size: 20px;
            display: inline-block;
            width: 40px;
        }
        .producto .nombre {
            font-weight: bold;
        }
        .opciones {
            margin-left: 40px;
            font-size: 14px;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
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
        <h1>🍳 COCINA</h1>
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
            <span class="cantidad">{{ $detalle->cantidad }}x</span>
            <span class="nombre">{{ $detalle->nombre_producto }}</span>
            
            @if($detalle->opciones_personalizadas)
                @php
                    $opciones = json_decode($detalle->opciones_personalizadas, true);
                @endphp
                @if($opciones && count($opciones) > 0)
                <div class="opciones">
                    @foreach($opciones as $opcion)
                        • {{ $opcion['value'] ?? $opcion }}<br>
                    @endforeach
                </div>
                @endif
            @endif
        </div>
        @endforeach
    </div>

    <div class="footer">
        <div>================================</div>
        <div style="font-size: 14px; margin-top: 5px;">TICKET COCINA</div>
        <div>{{ now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <script>
        // Auto-imprimir al cargar (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
