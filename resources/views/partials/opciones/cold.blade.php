@php
    // Define las opciones de bebidas frías
    $conditional_options['Temperatura'] = [
        [
            'id' => 'temp-fria',
            'nombre' => 'Fría/Helada',
            'precio_extra' => 0.00,
            'default' => true, 
        ],
        [
            'id' => 'temp-ambiente',
            'nombre' => 'Temperatura ambiente',
            'precio_extra' => 0.00,
        ],
    ];
@endphp