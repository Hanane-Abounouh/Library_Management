<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Appliquer les filtres de recherche si fournis
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('genre', 'like', "%$search%");
        }

        // Paginer les résultats
        $books = $query->paginate(10); // Ajuster la taille de pagination selon vos besoins

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'published_year' => 'required|integer',
            'isbn' => 'required|string|max:255|unique:books',
            'copies_available' => 'required|integer',
        ]);

        $book = Book::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Livre créé avec succès',
            'data' => $book
        ], 201);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:255',
            'author' => 'string|max:255',
            'genre' => 'string|max:255',
            'published_year' => 'integer',
            'isbn' => 'string|max:255|unique:books,isbn,'.$id,
            'copies_available' => 'integer',
        ]);
    
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Livre mis à jour avec succès',
            'data' => $book
        ]);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json([
            'success' => true,
            'message' => 'Livre supprimé avec succès'
        ], 204);
    }
}
