<?php

namespace App\Http\Controllers;

use App\Models\FarmAnimal;
use Illuminate\Http\Request;

class FarmAnimalController extends Controller
{
    public function index()
    {
        return FarmAnimal::all();
    }

    public function show($id)
    {
        $animal = FarmAnimal::where('owner_id', $id)->firstOrFail();
        return response()->json($animal);
    }

    public function store(Request $request)
    {
        $animal = FarmAnimal::create($request->all());
        return response()->json($animal, 201);
    }

    public function update(Request $request, $id)
    {
        $animal = FarmAnimal::findOrFail($id);
        $animal->update($request->all());
        return response()->json($animal, 200);
    }

    public function destroy($id)
    {
        FarmAnimal::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}

