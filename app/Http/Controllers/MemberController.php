<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    public function index()
    {
        return Member::paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'membership_date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        Member::create($request->all());

        return response()->json(['message' => 'Member created successfully'], 201);
    }

    public function show($id)
    {
        return Member::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:members,email,' . $id,
            'membership_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|max:255',
        ]);

        $member->update($request->all());

        return response()->json(['message' => 'Member updated successfully']);
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json(['message' => 'Member deleted successfully']);
    }
}
