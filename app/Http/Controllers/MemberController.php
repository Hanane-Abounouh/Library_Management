<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        // Appliquer les filtres de recherche si fournis
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        // Paginer les résultats
        $members = $query->paginate(3); // Ajuster la taille de pagination selon les besoins

        return response()->json([
            'success' => true,
            'data' => $members
        ]);
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'membership_date' => 'required|date',
            'status' => 'required|string|max:255', // 'status' correspond à l'attribut 'status' dans votre migration
        ]);

        Member::create($request->all());
        return response()->json(['message' => 'Membre créé avec succès']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:members,email,'.$id,
            'membership_date' => 'sometimes|date',
            'status' => 'sometimes|string|max:255', // 'status' correspond à l'attribut 'status' dans votre migration
        ]);

        $member = Member::findOrFail($id);
        $member->update($request->all());
        return response()->json($member);
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        return response()->json(null, 204);
    }
}
