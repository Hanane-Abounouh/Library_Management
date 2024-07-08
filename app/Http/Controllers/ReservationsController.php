<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationsController extends Controller
{
    // Récupère toutes les réservations
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json($reservations);
    }

    // Affiche une réservation spécifique
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return response()->json($reservation);
    }

    // Crée une nouvelle réservation
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'reserved_date' => 'nullable|date',
            'notification_sent' => 'boolean',
        ]);

        $reservation = Reservation::create($request->all());
        return response()->json($reservation, 201);
    }

    // Met à jour une réservation existante
    public function update(Request $request, $id)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'reserved_date' => 'nullable|date',
            'notification_sent' => 'boolean',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());
        return response()->json($reservation);
    }

    // Supprime une réservation
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json(null, 204);
    }
}
