<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juego de Memoria</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <form action="{{ route('start') }}" method="POST" class="bg-white p-8 rounded shadow-md w-full max-w-sm">
        @csrf
        <h1 class="text-xl font-bold mb-6 text-center">Iniciar Juego de Memoria</h1>

        <label class="block mb-4">
            <span class="text-gray-700">Nombre</span>
            <input type="text" name="name" required class="mt-1 block w-full rounded border-gray-300">
        </label>

        <label class="block mb-6">
            <span class="text-gray-700">Correo electrónico</span>
            <input type="email" name="email" required class="mt-1 block w-full rounded border-gray-300">
        </label>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 w-full rounded">
            Comenzar
        </button>
    </form>

</body>
</html>
