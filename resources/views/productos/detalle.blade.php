<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle - Personalizar Producto</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',      // Marrón Oscuro (del logo)
                        'brand-accent': '#ff9800',    // Naranja (Flor del logo)
                        'brand-green': '#1b5e20',     // Verde Oscuro
                        'brand-soft-green': '#81c784',// Verde Suave
                        'brand-red': '#E5002B',       // Rojo
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7; /* Fondo ligeramente gris para contraste */
        }
        .option-card {
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            user-select: none;
        }
        .option-selected {
            border-color: #ff9800; /* Naranja Accent */
            background-color: #fff3e0; /* Fondo naranja muy suave */
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.2);
        }
    </style>
</head>
<body>

    @php
        // Simulación de datos para Limonada (el ID 1)
        // En una app real, esto vendría de la base de datos por el $producto_id
        $producto = (object)[
            'id' => 1,
            'nombre' => 'Limonada Clásica',
            'categoria' => 'Limonadas',
            'precio_base' => 12.00,
            'descripcion' => 'Nuestra limonada estrella, perfectamente balanceada entre lo dulce y lo ácido, hecha con limones frescos.',
            'imagen_url' => 'https://placehold.co/800x400/F0F4C3/4d2925?text=Limonada+Clasica',
            'opciones' => [
                'Tamaño' => [
                    (object)['nombre' => 'Mediana (12oz)', 'precio_extra' => 0.00, 'id' => 'tam_m', 'default' => true],
                    (object)['nombre' => 'Grande (16oz)', 'precio_extra' => 3.00, 'id' => 'tam_g'],
                ],
                'Endulzante' => [
                    (object)['nombre' => 'Azúcar estándar', 'precio_extra' => 0.00, 'id' => 'end_azucar', 'default' => true],
                    (object)['nombre' => 'Stevia', 'precio_extra' => 1.00, 'id' => 'end_stevia'],
                    (object)['nombre' => 'Sin endulzante', 'precio_extra' => 0.00, 'id' => 'end_none'],
                ],
                'Extras' => [ // Ejemplo de opción MÚLTIPLE
                    (object)['nombre' => 'Menta fresca', 'precio_extra' => 1.50, 'id' => 'extra_menta'],
                    (object)['nombre' => 'Toque de jengibre', 'precio_extra' => 2.00, 'id' => 'extra_jengibre'],
                ]
            ]
        ];
    @endphp

    <!-- Botón de Volver/Cerrar (Top Izquierda) -->
    <div class="fixed top-0 left-0 p-4 z-30">
        <!-- CORRECCIÓN AQUÍ: Cambiado 'catalogo.menu' a 'productos.menu' -->
        <a href="{{ route('productos.menu') }}" class="flex items-center text-brand-dark hover:text-brand-accent transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="ml-1 font-semibold">Menú</span>
        </a>
    </div>

    <!-- Contenido Principal -->
    <main class="max-w-4xl mx-auto pb-36"> 
        <form id="detalleForm" action="{{ route('carrito.agregar', ['producto' => $producto->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="producto_id" value="{{ $producto->id }}">

            <!-- Imagen del Producto (Banner) -->
            <div class="h-48 md:h-64 w-full bg-gray-200 overflow-hidden shadow-lg">
                <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
            </div>

            <!-- Información y Título -->
            <div class="bg-white p-6 md:p-8 shadow-xl rounded-b-3xl -mt-4 relative z-10">
                <h1 class="text-4xl font-extrabold text-brand-dark mb-2">{{ $producto->nombre }}</h1>
                <p class="text-brand-accent text-lg font-bold mb-4">S/. <span id="currentPrice">{{ number_format($producto->precio_base, 2) }}</span></p>
                <p class="text-gray-600 border-b pb-4 mb-6">{{ $producto->descripcion }}</p>

                <!-- Opciones de Personalización -->
                @foreach ($producto->opciones as $grupo_nombre => $opciones_grupo)
                    @php
                        // Determinar si es una selección simple (RADIO) o múltiple (CHECKBOX)
                        $is_single_select = $grupo_nombre != 'Extras'; 
                        $input_type = $is_single_select ? 'radio' : 'checkbox';
                        $input_name = $is_single_select ? strtolower(str_replace(' ', '_', $grupo_nombre)) : 'extras[]';
                    @endphp
                    
                    <div class="mb-8 border-b pb-6">
                        <h2 class="text-2xl font-bold text-brand-dark mb-4 flex items-center">
                            {{ $grupo_nombre }}
                            @if($is_single_select)
                                <span class="ml-3 text-sm font-medium text-brand-accent px-3 py-1 bg-brand-accent/10 rounded-full">Obligatorio</span>
                            @else
                                <span class="ml-3 text-sm font-medium text-gray-500 px-3 py-1 bg-gray-100 rounded-full">Opcional</span>
                            @endif
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach ($opciones_grupo as $opcion)
                                @php
                                    $opcion_price_display = $opcion->precio_extra > 0 ? '+ S/. ' . number_format($opcion->precio_extra, 2) : 'Incluido';
                                    $is_default = isset($opcion->default) && $opcion->default;
                                @endphp
                                <label 
                                    for="{{ $opcion->id }}" 
                                    class="option-card flex justify-between items-center p-4 border-2 border-gray-200 rounded-xl bg-gray-50 hover:bg-white
                                        {{ $is_single_select && $is_default ? 'option-selected' : '' }}"
                                    data-price="{{ $opcion->precio_extra }}"
                                    data-type="{{ $input_type }}"
                                >
                                    <div class="flex items-center">
                                        <input 
                                            type="{{ $input_type }}" 
                                            name="{{ $input_name }}" 
                                            id="{{ $opcion->id }}" 
                                            value="{{ $opcion->nombre }}" 
                                            class="form-{{ $input_type }} h-5 w-5 text-brand-accent focus:ring-brand-accent border-gray-300 rounded-full"
                                            @if($is_default) checked @endif
                                        >
                                        <span class="ml-3 text-lg font-semibold text-brand-dark">{{ $opcion->nombre }}</span>
                                    </div>
                                    <span class="text-md font-bold {{ $opcion->precio_extra > 0 ? 'text-brand-green' : 'text-gray-500' }}">
                                        {{ $opcion_price_display }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Contenedor de Formulario (solo para el POST) -->
            <input type="hidden" name="cantidad" id="hiddenQuantity" value="1">
            <input type="hidden" name="opciones_seleccionadas" id="hiddenOptions">
            <input type="hidden" name="precio_final" id="hiddenFinalPrice">
            
        </form>
    </main>

    <!-- Barra Inferior Flotante (Añadir al Carrito y Contador) -->
    <div class="fixed bottom-0 left-0 w-full bg-white shadow-2xl p-4 z-20 border-t border-gray-200">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            
            <!-- Contador de Cantidad -->
            <div class="flex items-center space-x-3">
                <button type="button" id="decreaseBtn" class="bg-gray-200 text-brand-dark p-3 rounded-full hover:bg-gray-300 transition duration-150 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                </button>
                <span id="quantityDisplay" class="text-2xl font-bold text-brand-dark w-10 text-center">1</span>
                <button type="button" id="increaseBtn" class="bg-brand-accent text-white p-3 rounded-full hover:bg-brand-accent/90 transition duration-150 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                </button>
            </div>

            <!-- Botón de Añadir al Carrito -->
            <button type="submit" form="detalleForm" id="addToCartBtn" class="px-8 py-3 bg-brand-dark text-white font-bold rounded-full shadow-xl hover:bg-brand-dark/90 transition duration-150 transform hover:scale-105 text-lg">
                Añadir al Pedido - S/. <span id="finalPriceDisplay">{{ number_format($producto->precio_base, 2) }}</span>
            </button>
        </div>
    </div>
    
    <script>
        const basePrice = {{ $producto->precio_base }};
        let currentQuantity = 1;

        // Elementos del DOM
        const quantityDisplay = document.getElementById('quantityDisplay');
        const hiddenQuantity = document.getElementById('hiddenQuantity');
        const currentPriceDisplay = document.getElementById('currentPrice');
        const finalPriceDisplay = document.getElementById('finalPriceDisplay');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const hiddenOptions = document.getElementById('hiddenOptions');
        const hiddenFinalPrice = document.getElementById('hiddenFinalPrice');
        const allOptions = document.querySelectorAll('.option-card');

        // Función para calcular el precio total basado en las opciones y la cantidad
        function calculateTotal() {
            let optionPrice = 0;
            let selectedOptions = [];

            // Iterar sobre todas las opciones
            document.querySelectorAll('input:checked').forEach(input => {
                const label = input.closest('label');
                const price = parseFloat(label.dataset.price);
                optionPrice += price;
                
                // Recolectar opciones para el envío al backend
                selectedOptions.push({
                    name: input.name,
                    value: input.value,
                    price: price
                });

                // Si es radio, añadir la clase 'option-selected' al label
                if (input.type === 'radio') {
                    document.querySelectorAll(`input[name="${input.name}"]`).forEach(radio => {
                        radio.closest('label').classList.remove('option-selected');
                    });
                    label.classList.add('option-selected');
                }
            });

            const subtotal = basePrice + optionPrice;
            const finalTotal = subtotal * currentQuantity;

            // Actualizar la interfaz de usuario
            currentPriceDisplay.textContent = finalTotal.toFixed(2);
            finalPriceDisplay.textContent = finalTotal.toFixed(2);

            // Actualizar inputs ocultos para el formulario POST
            hiddenOptions.value = JSON.stringify(selectedOptions);
            hiddenFinalPrice.value = finalTotal.toFixed(2);
            
            // Actualizar el texto del botón
            addToCartBtn.innerHTML = `Añadir al Pedido - S/. ${finalTotal.toFixed(2)}`;
        }

        // Manejadores de Eventos de Cantidad
        document.getElementById('increaseBtn').addEventListener('click', () => {
            currentQuantity++;
            quantityDisplay.textContent = currentQuantity;
            hiddenQuantity.value = currentQuantity;
            calculateTotal();
        });

        document.getElementById('decreaseBtn').addEventListener('click', () => {
            if (currentQuantity > 1) {
                currentQuantity--;
                quantityDisplay.textContent = currentQuantity;
                hiddenQuantity.value = currentQuantity;
                calculateTotal();
            }
        });

        // Manejador de Eventos para Opciones (Radio/Checkbox)
        document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', calculateTotal);
        });

        // Manejador de Eventos para el CLIC en el Label (Mejora la UX móvil)
        allOptions.forEach(label => {
            label.addEventListener('click', (event) => {
                const input = label.querySelector('input');
                // Si el clic no fue directamente en el input, simular el cambio para la UX de la clase
                if (event.target !== input) {
                    input.checked = (input.type === 'radio') ? true : !input.checked;
                    calculateTotal();
                }

                // Si es un radio, manejar la clase de selección
                if (input.type === 'radio') {
                    // Deseleccionar todos los radios del mismo grupo
                    document.querySelectorAll(`input[name="${input.name}"]`).forEach(radio => {
                        radio.closest('label').classList.remove('option-selected');
                    });
                    // Seleccionar el radio actual
                    label.classList.add('option-selected');
                }
            });
        });


        // Inicializar el precio y las clases al cargar la página
        window.onload = () => {
            // Asegurar que las opciones por defecto tengan la clase 'option-selected'
            document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                input.closest('label').classList.add('option-selected');
            });
            calculateTotal();
        };

    </script>
</body>
</html>
