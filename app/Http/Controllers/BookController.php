<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return Book::paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre_id' => 'required|integer|exists:genres,id',
            'publication_year' => 'required|integer',
            'isbn' => 'required|string|max:13|unique:books',
            'copies_available' => 'required|integer',
        ]);

        Book::create($request->all());

        return response()->json(['message' => 'Book created successfully'], 201);
    }

    public function show($id)
    {
        return Book::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'genre_id' => 'sometimes|required|integer|exists:genres,id',
            'publication_year' => 'sometimes|required|integer',
            'isbn' => 'sometimes|required|string|max:13|unique:books,isbn,' . $id,
            'copies_available' => 'sometimes|required|integer',
        ]);

        $book->update($request->all());

        return response()->json(['message' => 'Book updated successfully']);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}
