@php
    // Define las opciones de bebidas calientes
    $conditional_options['Temperatura'] = [
        [
            'id' => 'temp-caliente',
            'nombre' => 'Caliente',
            'precio_extra' => 0.00,
            'default' => true,
        ],
        [
            'id' => 'temp-tibio',
            'nombre' => 'Tibio',
            'precio_extra' => 0.00,
        ],
    ];
    
    // Opcional: Agregar Endulzante solo para bebidas calientes
    $conditional_options['Endulzante'] = [
        [
            'id' => 'endulzante-azucar',
            'nombre' => 'Azúcar',
            'precio_extra' => 0.00,
            'default' => true,
        ],
        [
            'id' => 'endulzante-stevia',
            'nombre' => 'Stevia',
            'precio_extra' => 0.00,
        ],
        [
            'id' => 'endulzante-ninguno',
            'nombre' => 'Sin endulzante',
            'precio_extra' => 0.00,
        ],
    ];
@endphp