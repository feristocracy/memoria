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
            '¿Qué es la Tienda Digital del Gobierno Federal y cuál es su función principal?' => 'Es una plataforma para compras rápidas mediante órdenes de suministro',
            '¿Qué criterios deben considerarse para declarar un “precio no aceptable”?' => 'Cuando excede el presupuesto o supera en más del 10% la mediana del mercado',
            'Qué papel juegan las Mipymes y organizaciones del sector social en esta Ley' => 'Tienen trato preferente y beneficios en los procesos de contratación',
            '¿Qué principios deben regir los procedimientos de contratación conforme al artículo 20?' => 'Eficiencia, economía, imparcialidad y transparencia',
            '¿Cuál es la función del Comité de Contrataciones Estratégicas?' => 'Aprobar compras consolidadas y autorizar subcomités',
            '¿Qué condiciones deben cumplirse para adquirir bienes muebles usados o reconstruidos?' => 'Avalúo vigente y valor mayor a 100 mil UMAs',
            '¿Qué mecanismos contempla la Ley para fomentar la sostenibilidad en las adquisiciones?' => 'Materiales reciclados, certificaciones y puntos extra por políticas verdes',
            '¿Cuál es el alcance de la Secretaría Anticorrupción y Buen Gobierno en esta Ley?' => 'Emitir lineamientos y coordinar políticas anticorrupción en contrataciones',
            '¿Qué deben hacer las dependencias antes de contratar consultorías o estudios externos?' => 'Verificar que no existan trabajos similares y obtener dictamen técnico',
            '¿Qué sucede si se celebra un contrato en contravención a la Ley?' => 'Será nulo y se resolverá conforme al Título Séptimo de la Ley',
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
