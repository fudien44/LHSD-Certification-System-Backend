<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        return Program::all(); // Return JSON array
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $program = Program::create($request->only('name'));
        return response()->json($program);
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $program->update($request->only('name'));
        return response()->json($program);
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();
        return response()->noContent();
    }
}
