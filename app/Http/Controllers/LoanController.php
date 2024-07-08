<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    // Récupère tous les prêts
    public function index()
    {
        $loans = Loan::all();
        return response()->json($loans);
    }

    // Affiche un prêt spécifique
    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        return response()->json($loan);
    }

    // Crée un nouveau prêt
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'issued_date' => 'required|date',
            'due_date' => 'required|date',
            'returned_date' => 'nullable|date',
        ]);

        $loanData = $request->all();

        // Calculer l'amende si la date de retour est supérieure à la date d'échéance
        if (isset($loanData['returned_date']) && Carbon::parse($loanData['returned_date'])->greaterThan(Carbon::parse($loanData['due_date']))) {
            $daysLate = Carbon::parse($loanData['returned_date'])->diffInDays(Carbon::parse($loanData['due_date']));
            $loanData['fine_amount'] = $daysLate * 2.00; // Par exemple, 2.00 $ par jour de retard
        }

        $loan = Loan::create($loanData);
        return response()->json($loan, 201);
    }

    // Met à jour un prêt existant
    public function update(Request $request, $id)
    {
        Log::info('Request Data:', $request->all());

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'issued_date' => 'required|date',
            'due_date' => 'required|date',
            'returned_date' => 'nullable|date',
        ]);

        $loan = Loan::findOrFail($id);
        $loan->update($request->all());

        Log::info('Updated Loan Data:', $loan->toArray());

        // Calculer l'amende si la date de retour est supérieure à la date d'échéance
        if ($loan->returned_date && Carbon::parse($loan->returned_date)->greaterThan(Carbon::parse($loan->due_date))) {
            $daysLate = Carbon::parse($loan->returned_date)->diffInDays(Carbon::parse($loan->due_date));
            $loan->fine_amount = $daysLate * 1.00; // Par exemple, 1.00 $ par jour de retard
            $loan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Loan updated successfully',
            'data' => $loan
        ]);
    }

    // Supprime un prêt
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();
        return response()->json(null, 204);
    }
}
