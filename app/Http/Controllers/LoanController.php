<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;

class LoanController extends Controller
{
    public function index()
    {
        return Loan::with('book', 'member')->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|integer|exists:books,id',
            'member_id' => 'required|integer|exists:members,id',
            'loan_date' => 'required|date',
            'return_date' => 'nullable|date|after:loan_date',
        ]);

        $loan = Loan::create($request->all());

        // Update the number of available copies
        $book = Book::findOrFail($request->book_id);
        $book->decrement('copies_available');

        return response()->json(['message' => 'Loan created successfully'], 201);
    }

    public function show($id)
    {
        return Loan::with('book', 'member')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'book_id' => 'sometimes|required|integer|exists:books,id',
            'member_id' => 'sometimes|required|integer|exists:members,id',
            'loan_date' => 'sometimes|required|date',
            'return_date' => 'sometimes|nullable|date|after:loan_date',
        ]);

        $loan->update($request->all());

        return response()->json(['message' => 'Loan updated successfully']);
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        // Update the number of available copies
        $book = Book::findOrFail($loan->book_id);
        $book->increment('copies_available');

        $loan->delete();

        return response()->json(['message' => 'Loan deleted successfully']);
    }
}
