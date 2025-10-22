<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalizar: {{ $producto->nombre }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#4d2925',       // Marrón Oscuro
                        'brand-accent': '#ff9800',     // Naranja (Énfasis)
                        'brand-green': '#1b5e20',      // Verde Oscuro
                        'brand-soft-green': '#e8f5e9', // Verde Suave (Fondo para imágenes)
                        'btn-green': '#4CAF50',        // Verde para el botón de Confirmar
                        'btn-green-hover': '#45a049',  // Verde más oscuro para hover
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
            background-color: #f7f7f7;
            padding-bottom: 2rem; 
        }
        .option-card {
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            user-select: none;
        }
        .option-selected {
            border-color: #ff9800;
            background-color: #fff3e0;
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.2);
        }
        /* Estilos de Control de Cantidad */
        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
        }
        .qty-control button {
            background-color: #f3f4f6;
            color: #4d2925;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 1.5rem;
            line-height: 1;
            transition: background-color 0.15s;
        }
        .qty-control button:hover {
            background-color: #e5e7eb;
        }
        .qty-control span {
            font-size: 2rem;
            font-weight: bold;
            color: #4d2925;
            width: 3rem;
            text-align: center;
        }
    </style>
</head>
<body>

@php
    // --- LÓGICA DE OPCIONES CONDICIONALES ---
    
    // 1. Obtener el nombre de la categoría de forma SEGURA.
    // VERIFICACIÓN CLAVE: Asegura que la categoría es un objeto antes de acceder a su propiedad.
    if (is_object($producto->categoria) && property_exists($producto->categoria, 'nombre')) {
        $categoria_nombre = $producto->categoria->nombre;
    } else {
        // En caso de que el Route Model Binding haya traído el nombre de la columna 'categoria' (cadena)
        $categoria_nombre = (string)($producto->categoria ?? ''); 
    }
    
    // Usamos mb_strtolower para manejar correctamente las tildes y mayúsculas.
    $categoria_nombre_lower = mb_strtolower($categoria_nombre, 'UTF-8'); 
    
    // Inicializar el array de opciones condicionales (ej: Temperatura)
    $conditional_options = [];

    // 2. Usar un switch simple para incluir el archivo de opciones correcto
    switch ($categoria_nombre_lower) {
        
        // Categorías Calientes (Usa hot.blade.php)
        case 'bebidas calientes': 
        case 'café': 
            @include('partials.opciones.hot'); 
            break;
            
        // Categorías Frías (Usa cold.blade.php)
        case 'limonada': 
        case 'limonadas': 
        case 'jugos': 
        case 'bebidas heladas': 
            @include('partials.opciones.cold'); 
            break;
            
        // Para otras categorías ($conditional_options se queda vacío)
    }
    
    // 3. FUSIONAR opciones de DB con opciones condicionales (Temperatura, etc.).
    $opciones_a_mostrar = is_array($producto->opciones) ? $producto->opciones : [];

    if (!empty($conditional_options)) {
        // Fusiona las opciones base del producto con las opciones condicionales (Temperatura/Endulzante)
        $opciones_a_mostrar = array_merge($opciones_a_mostrar, $conditional_options);
    }
@endphp

    <main class="max-w-xl mx-auto mt-8 bg-white rounded-xl shadow-2xl overflow-hidden"> 
        
        <form id="detalleForm" action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
            @csrf
            <input type="hidden" name="producto_id" value="{{ $producto->id }}">

            {{-- BLOQUE DE IMAGEN --}}
            <div class="h-48 w-full bg-brand-soft-green flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/productos/' . $producto->imagen_nombre) }}" 
                alt="{{ $producto->nombre }}" 
                class="max-w-xs max-h-full object-contain p-4"> 
            </div>

            <div class="p-6 md:p-8 relative z-10">
                
                {{-- Botón Volver --}}
                <a href="{{ route('productos.menu') }}" class="flex items-center text-gray-500 hover:text-brand-dark transition duration-150 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="ml-1 text-sm font-semibold">Volver al Menú</span>
                </a>

                <h1 class="text-3xl font-extrabold text-brand-dark mb-2">{{ $producto->nombre }}</h1>
                <p class="text-brand-accent text-lg font-bold mb-4">S/. <span id="basePriceDisplay">{{ number_format($producto->precio, 2) }}</span></p>
                <p class="text-gray-600 border-b pb-4 mb-6">{{ $producto->descripcion }}</p>

                {{-- Control de Cantidad --}}
                <h2 class="text-xl font-bold text-brand-dark text-center mb-2">Cantidad</h2>
                <div class="qty-control">
                    <button type="button" id="decreaseBtn">—</button>
                    <span id="quantityDisplay">1</span>
                    <button type="button" id="increaseBtn">+</button>
                </div>
                <hr class="mb-6">

                {{-- Opciones de Personalización --}}
                @foreach ($opciones_a_mostrar as $grupo_nombre => $opciones_grupo)
                    @php
                        // Solo 'Extras' y 'Complementos' son checkbox (selección múltiple)
                        $is_single_select = $grupo_nombre != 'Extras' && $grupo_nombre != 'Complementos'; 
                        $input_type = $is_single_select ? 'radio' : 'checkbox';
                        // Generar el nombre del input: "tamaño", "endulzante", "temperatura", etc.
                        $input_name = strtolower(str_replace(' ', '_', $grupo_nombre));
                        if (!$is_single_select) {
                            $input_name .= '[]'; // Para checkbox
                        }
                        // Asume que todos los grupos de selección simple (radio) son obligatorios
                        $is_required = $is_single_select; 
                    @endphp
                    
                    <div class="mb-8 border-b pb-6 last:border-b-0">
                        <h2 class="text-xl font-bold text-brand-dark mb-3 flex items-center">
                            {{ $grupo_nombre }}
                            <span class="ml-3 text-xs font-medium px-2 py-0.5 rounded-full 
                                {{ $is_required ? 'text-brand-accent bg-brand-accent/10' : 'text-gray-500 bg-gray-100' }}">
                                {{ $is_required ? 'Obligatorio' : 'Opcional' }}
                            </span>
                        </h2>
                        
                        <div class="space-y-3">
                            @foreach ($opciones_grupo as $opcion)
                                @php
                                    $opcion = (object)$opcion; 
                                    $opcion_price_display = $opcion->precio_extra > 0 ? '+ S/. ' . number_format($opcion->precio_extra, 2) : 'Incluido';
                                    $is_default = isset($opcion->default) && $opcion->default;
                                @endphp
                                <label 
                                    for="{{ $opcion->id }}" 
                                    class="option-card flex justify-between items-center p-3 border-2 border-gray-200 rounded-lg bg-gray-50 hover:bg-white
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
                                            @if($is_required && $input_type == 'radio') required @endif
                                        >
                                        <span class="ml-3 text-md font-semibold text-brand-dark">{{ $opcion->nombre }}</span>
                                    </div>
                                    <span class="text-sm font-bold {{ $opcion->precio_extra > 0 ? 'text-brand-green' : 'text-gray-500' }}">
                                        {{ $opcion_price_display }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Botón Final (Fijo) --}}
            <div class="p-6 pt-0">
                <button type="submit" id="confirmSelectionBtn" class="w-full py-3 bg-btn-green text-white font-bold rounded-xl shadow-lg hover:bg-btn-green-hover transition duration-150 text-lg">
                    Confirmar selección
                </button>
            </div>

            {{-- Inputs Ocultos para el POST --}}
            <input type="hidden" name="cantidad" id="hiddenQuantity" value="1">
            <input type="hidden" name="opciones_seleccionadas" id="hiddenOptions">
            <input type="hidden" name="precio_final" id="hiddenFinalPrice">
            
        </form>
    </main>
    
    <script>
        const basePrice = {{ $producto->precio }};
        let currentQuantity = 1;

        // Elementos del DOM
        const quantityDisplay = document.getElementById('quantityDisplay');
        const hiddenQuantity = document.getElementById('hiddenQuantity');
        const confirmSelectionBtn = document.getElementById('confirmSelectionBtn');
        const hiddenOptions = document.getElementById('hiddenOptions');
        const hiddenFinalPrice = document.getElementById('hiddenFinalPrice');
        const allOptions = document.querySelectorAll('.option-card');

        // Función para calcular el precio total basado en las opciones y la cantidad
        function calculateTotal() {
            let subtotalOpciones = 0;
            let selectedOptions = [];

            // 1. Recorrer todas las opciones seleccionadas (radio y checkbox)
            document.querySelectorAll('input:checked').forEach(input => {
                const label = input.closest('label');
                const price = parseFloat(label.dataset.price || 0);
                subtotalOpciones += price;
                
                // 2. Construir el array de opciones seleccionadas para el JSON
                selectedOptions.push({
                    group: input.name, 
                    value: input.value, 
                    price: price
                });

                // 3. Manejo visual de la selección (solo para radio)
                if (input.type === 'radio') {
                    document.querySelectorAll(`input[name="${input.name}"]`).forEach(radio => {
                        radio.closest('label').classList.remove('option-selected');
                    });
                    label.classList.add('option-selected');
                } else if (input.type === 'checkbox') {
                     // Para checkboxes, la clase debe toggles al hacer clic en el input
                     if (input.checked) {
                        label.classList.add('option-selected');
                     } else {
                        label.classList.remove('option-selected');
                     }
                }
            });

            // Para los checkboxes que se desmarcan, quitar la clase. (Este paso es redundante si se usa el event listener del label, pero asegura la consistencia).
            document.querySelectorAll('input[type="checkbox"]:not(:checked)').forEach(input => {
                input.closest('label').classList.remove('option-selected');
            });


            // 4. Cálculo final
            const precioUnitarioConOpciones = basePrice + subtotalOpciones;
            const finalTotal = precioUnitarioConOpciones * currentQuantity;

            // 5. Actualizar inputs ocultos para el formulario POST
            hiddenOptions.value = JSON.stringify(selectedOptions);
            hiddenFinalPrice.value = precioUnitarioConOpciones.toFixed(2); // Guarda el precio unitario con opciones
            
            // 6. Actualizar el texto del botón (muestra el total x Cantidad)
            confirmSelectionBtn.textContent = `Confirmar selección (S/. ${finalTotal.toFixed(2)})`;
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

        // Manejador de Eventos para el CLIC en el Label (Mejora UX)
        allOptions.forEach(label => {
            label.addEventListener('click', (event) => {
                const input = label.querySelector('input');
                
                // Si el clic no fue directamente en el input, simula el cambio
                if (event.target !== input) {
                    if (input.type === 'checkbox') {
                        // Toggle el estado del checkbox
                        input.checked = !input.checked;
                        // Forzar el recálculo (ya lo hace el change listener, pero es seguro llamarlo)
                    } else if (input.type === 'radio') {
                        // Marcar el radio
                        input.checked = true;
                    }
                    // Disparar el cálculo después de la simulación del clic
                    calculateTotal();
                }
            });
        });


        // Inicializar al cargar la página
        window.onload = () => {
            // Establece la clase 'option-selected' para las opciones 'default' (radio) al inicio
            document.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked').forEach(input => {
                input.closest('label').classList.add('option-selected');
            });
            // Calcula el precio total inicial (incluyendo el precio base y las opciones por defecto)
            calculateTotal();
        };

    </script>
</body>
</html>