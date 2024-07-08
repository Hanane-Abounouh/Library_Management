<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use DateTime;

class LoanController extends Controller
{
    /**
     * Display a listing of the loans with associated book and member details.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::with('book', 'member')->get();

        return response()->json([
            'success' => true,
            'data' => $loans
        ]);
    }

    /**
     * Store a newly created loan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'issued_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'returned_date' => 'nullable|date',
            'fine_amount' => 'nullable|numeric',
        ]);

        $loan = Loan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Loan created successfully',
            'data' => $loan
        ], 201);
    }

    /**
     * Display the specified loan with associated book and member details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::with('book', 'member')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $loan
        ]);
    }

    /**
     * Update the specified loan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'book_id' => 'exists:books,id',
            'member_id' => 'exists:members,id',
            'issued_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'returned_date' => 'nullable|date',
            'fine_amount' => 'nullable|numeric',
        ]);

        $loan = Loan::findOrFail($id);
        $loan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Loan updated successfully',
            'data' => $loan
        ]);
    }

    /**
     * Handle the return of a borrowed book and calculate fine automatically.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function returnBook(Request $request, $id)
    {
        $request->validate([
            'returned_date' => 'required|date',
        ]);

        $loan = Loan::findOrFail($id);
        $loan->returned_date = $request->returned_date;

        // Calculer l'amende
        $loan->fine_amount = $this->calculateFine($loan->due_date, $loan->returned_date);
        $loan->save();

        return response()->json([
            'success' => true,
            'message' => 'Book returned successfully',
            'data' => $loan
        ]);
    }

    /**
     * Calculate fine based on overdue period.
     *
     * @param  \DateTime  $dueDate
     * @param  \DateTime  $returnDate
     * @return float
     */
    private function calculateFine($dueDate, $returnDate)
    {
        if (!is_null($dueDate) && !is_null($returnDate)) {
            $dueDate = new DateTime($dueDate);
            $returnDate = new DateTime($returnDate);
            $daysOverdue = $dueDate->diff($returnDate)->days;

            $fineRatePerDay = 10; // $10 par jour de retard
            return $daysOverdue * $fineRatePerDay;
        }

        return 0;
    }

    /**
     * Remove the specified loan from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully'
        ]);
    }
}
