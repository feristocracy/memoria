<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Juego de Memoria</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .card {
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            perspective: 1000px;
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .flipped .card-inner {
            transform: rotateY(180deg);
        }

        .card-front,
        .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 0.5rem;
        }

        .card-front {
            background-image: url('/images/logoch.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }

        .card-back {
            background-color: #E89853;
            /* gris oscuro */
            color: black;
            transform: rotateY(180deg);
        }

        .matched {
            filter: brightness(0.5) saturate(1.2);
            box-shadow: 0 0 10px rgb(21, 142, 65);
        }

        .card:hover {
            cursor: pointer;
            transform: scale(1.05);
        }

        .card-error {
            background-color:rgb(226, 85, 85) !important;
            /* rojo intenso */
            transition: background-color 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-200 text-black min-h-screen flex flex-col items-center justify-center p-4">

    <h1 class="text-2xl font-bold mb-6 text-gray-900">Memorama: Encuentra las parejas</h1>

    <div class="grid grid-cols-5 gap-4 mb-6 max-w-4xl w-full">
        @foreach ($cards as $index => $card)
        <div class="card w-full aspect-square bg-gray-300" data-index="{{ $index }}" data-type="{{ $card['type'] }}" data-text="{{ $card['text'] }}">
            <div class="card-inner w-full h-full">
                <div class="card-front">

                </div>
                <div class="card-back {{ $card['type'] === 'question' ? 'bg-blue-700' : 'bg-red-700' }}">
                    <div class="text-center px-2 w-full h-full flex flex-col justify-center items-center overflow-hidden text-ellipsis">
                        <span class="block text-sm uppercase font-bold text-orange-900 break-words leading-tight">
                            {{ $card['type'] === 'question' ? 'Pregunta:' : 'Respuesta:' }}
                        </span>
                        <span class="block mt-1 text-sm sm:text-base break-words leading-tight text-wrap">
                            {{ $card['text'] }}
                        </span>
                    </div>
                </div>


            </div>
        </div>
        @endforeach
    </div>

    <div class="mb-4 text-xl font-bold text-gray-900">Intentos: <span id="attempts">0</span></div>
    <div id="message" class="text-green-400 font-bold mb-4 hidden">
        🎉 ¡Felicidades, has completado el juego en <span id="final-attempts"></span> intentos!
    </div>


    <script>
        const cards = document.querySelectorAll('.card');
        let flippedCards = [];
        let matchedPairs = 0;
        let attempts = 0;

        const validPairs = {
            '¿Qué es la Tienda Digital del Gobierno Federal y cuál es su función principal?': 'Es una plataforma para compras rápidas mediante órdenes de suministro',
            '¿Qué criterios deben considerarse para declarar un “precio no aceptable”?': 'Cuando excede el presupuesto o supera en más del 10% la mediana del mercado',
            'Qué papel juegan las Mipymes y organizaciones del sector social en esta Ley': 'Tienen trato preferente y beneficios en los procesos de contratación',
            '¿Qué principios deben regir los procedimientos de contratación conforme al artículo 20?': 'Eficiencia, economía, imparcialidad y transparencia',
            '¿Cuál es la función del Comité de Contrataciones Estratégicas?': 'Aprobar compras consolidadas y autorizar subcomités',
            '¿Qué condiciones deben cumplirse para adquirir bienes muebles usados o reconstruidos?': 'Avalúo vigente y valor mayor a 100 mil UMAs',
            '¿Qué mecanismos contempla la Ley para fomentar la sostenibilidad en las adquisiciones?': 'Materiales reciclados, certificaciones y puntos extra por políticas verdes',
            '¿Cuál es el alcance de la Secretaría Anticorrupción y Buen Gobierno en esta Ley?': 'Emitir lineamientos y coordinar políticas anticorrupción en contrataciones',
            '¿Qué deben hacer las dependencias antes de contratar consultorías o estudios externos?': 'Verificar que no existan trabajos similares y obtener dictamen técnico',
            '¿Qué sucede si se celebra un contrato en contravención a la Ley?': 'Será nulo y se resolverá conforme al Título Séptimo de la Ley'
        };

        cards.forEach(card => {
            card.addEventListener('click', () => {
                if (card.classList.contains('matched') || flippedCards.includes(card)) return;

                card.classList.add('flipped');
                flippedCards.push(card);

                if (flippedCards.length === 2) {
                    attempts++;
                    document.getElementById('attempts').textContent = attempts;

                    const [card1, card2] = flippedCards;
                    const text1 = card1.dataset.text;
                    const text2 = card2.dataset.text;
                    const type1 = card1.dataset.type;
                    const type2 = card2.dataset.type;

                    const isMatch =
                        (validPairs[text1] === text2 && type1 === 'question' && type2 === 'answer') ||
                        (validPairs[text2] === text1 && type2 === 'question' && type1 === 'answer');

                    if (isMatch) {
                        flippedCards.forEach(c => {
                            c.classList.add('matched');
                        });
                        matchedPairs++;
                        if (matchedPairs === 10) {
                            document.getElementById('final-attempts').textContent = attempts;
                            document.getElementById('message').classList.remove('hidden');

                            // 🎉 Animación de confeti
                            confetti({
                                particleCount: 150,
                                spread: 100,
                                origin: {
                                    y: 0.6
                                }
                            });

                            fetch("{{ route('score') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    attempts: attempts
                                })
                            });
                        }
                        flippedCards = [];
                    } else {
                        flippedCards.forEach(c => {
                            const back = c.querySelector('.card-back');
                            back.classList.add('card-error');
                        });

                        setTimeout(() => {
                            flippedCards.forEach(c => {
                                c.classList.remove('card-error');
                                c.classList.remove('flipped');
                            });
                            flippedCards = [];
                        }, 2000);
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

</body>

</html>