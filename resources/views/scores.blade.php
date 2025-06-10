<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Puntuaciones</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4">Puntuaciones</h1>
    <a href="/" class="text-blue-500 underline">Volver al inicio</a>
    <table class="mt-4 w-full bg-white shadow rounded">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Correo</th>
                <th class="px-4 py-2">Intentos</th>
                <th class="px-4 py-2">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($scores as $score)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $score->name }}</td>
                    <td class="px-4 py-2">{{ $score->email }}</td>
                    <td class="px-4 py-2">{{ $score->attempts }}</td>
                    <td class="px-4 py-2">{{ $score->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
