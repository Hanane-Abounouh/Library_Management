<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::all();
        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres'
        ]);

        $genre = Genre::create([
            'name' => $request->name,
            'user_id' => auth()->id() // Associe le genre à l'utilisateur actuellement authentifié
        ]);

        return response()->json($genre, 201);
    }

    public function show($id)
    {
        $genre = Genre::findOrFail($id);
        return response()->json($genre);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,'.$id
        ]);
    
        $genre = Genre::findOrFail($id);
        $genre->update($request->all());
        
        return response()->json($genre);
    }
    

    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();
        return response()->json(null, 204);
    }
}
