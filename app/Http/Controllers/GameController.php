<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;

class GameController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function start(Request $request)
    {
        session([
            'player_name' => $request->name,
            'player_email' => $request->email
        ]);

        // Pares pregunta => respuesta
        $pairs = [
            '¿Capital de Francia?' => 'París',
            '¿5 + 3?' => '8',
            '¿Color del cielo?' => 'Azul',
            '¿Animal que dice “miau”?' => 'Gato',
            '¿Lenguaje de Laravel?' => 'PHP',
            '¿Número de días en una semana?' => '7',
            '¿Planeta rojo?' => 'Marte',
            '¿Animal que ladra?' => 'Perro',
            '¿Primer mes del año?' => 'Enero',
            '¿2 x 6?' => '12',
        ];

        $cards = [];
        foreach ($pairs as $q => $a) {
            $cards[] = ['type' => 'question', 'text' => $q];
            $cards[] = ['type' => 'answer', 'text' => $a];
        }

        shuffle($cards); // Mezclar

        session(['cards' => $cards, 'attempts' => 0]);

        return view('game', ['cards' => $cards]);
    }

    public function score(Request $request)
    {
        Score::create([
            'name' => session('player_name'),
            'email' => session('player_email'),
            'attempts' => $request->attempts
        ]);

        return response()->json(['message' => 'Score saved!']);
    }

    public function scores()
    {
        $scores = Score::orderBy('attempts')->get();
        return view('scores', compact('scores'));
    }
}
