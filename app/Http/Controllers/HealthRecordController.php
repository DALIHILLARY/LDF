<?php

namespace App\Http\Controllers;

use App\Models\HealthRecord;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    public function index()
    {
        return HealthRecord::all();
    }

    public function show($id)
    {
        $animal = HealthRecord::where('owner_id', $id)->firstOrFail();
        return response()->json($animal);
    }

    public function store(Request $request)
    {
        $animal = HealthRecord::create($request->all());
        return response()->json($animal, 201);
    }

    public function update(Request $request, $id)
    {
        $animal = HealthRecord::findOrFail($id);
        $animal->update($request->all());
        return response()->json($animal, 200);
    }

    public function destroy($id)
    {
        HealthRecord::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
