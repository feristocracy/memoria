<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juego de Memoria</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .card {
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
        .card-front, .card-back {
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
            background-color: #f59e0b; /* naranja */
        }
        .card-back {
            background-color: #1f2937; /* gris oscuro */
            color: white;
            transform: rotateY(180deg);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4">

    <h1 class="text-2xl font-bold mb-6">Memorama: Encuentra las parejitas</h1>

    <div class="grid grid-cols-5 gap-4 mb-6 max-w-4xl w-full">
        @foreach ($cards as $index => $card)
            <div class="card w-full aspect-square" data-index="{{ $index }}" data-type="{{ $card['type'] }}" data-text="{{ $card['text'] }}">
                <div class="card-inner w-full h-full">
                    <div class="card-front">
                        ❓
                    </div>
                    <div class="card-back">
                        {{ $card['text'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mb-4 text-lg">Intentos: <span id="attempts">0</span></div>
    <div id="message" class="text-green-400 font-bold mb-4 hidden">
    🎉 ¡Felicidades, has completado el juego en <span id="final-attempts"></span> intentos!
</div>

    <a href="{{ route('scores') }}" class="text-blue-400 underline">Ver puntuaciones</a>

    <script>
        const cards = document.querySelectorAll('.card');
        let flippedCards = [];
        let matchedPairs = 0;
        let attempts = 0;

        const validPairs = {
            '¿Capital de Francia?': 'París',
            '¿5 + 3?': '8',
            '¿Color del cielo?': 'Azul',
            '¿Animal que dice “miau”?': 'Gato',
            '¿Lenguaje de Laravel?': 'PHP',
            '¿Número de días en una semana?': '7',
            '¿Planeta rojo?': 'Marte',
            '¿Animal que ladra?': 'Perro',
            '¿Primer mes del año?': 'Enero',
            '¿2 x 6?': '12'
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
        origin: { y: 0.6 }
    });

    fetch("{{ route('score') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ attempts: attempts })
    });
}
                        flippedCards = [];
                    } else {
                        setTimeout(() => {
                            flippedCards.forEach(c => c.classList.remove('flipped'));
                            flippedCards = [];
                        }, 1000);
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

</body>
</html>
