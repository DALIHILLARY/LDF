<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Utils;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Farm::all();
        return $farms;
    }

    public function show($id)
    {
        $farm = Farm::find($id);
        return response()->json($farm);
    }

    public function showFarmerFarms($id)
    {
        $farms = Farm::where('owner_id', $id)->get();

        if ($farms->isEmpty()) {
            return response()->json([
                'message' => 'No farms found for this farmer'
            ], 404);
        }
        
        return response()->json($farms);
    }




    public function store(Request $request)
    {
        $rules= [
            'owner_id' => 'required|exists:farmers,id',
            'name' => 'required|string|max:255',
            'coordinates' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'village' => 'nullable|string|max:255',
            'parish' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'breeds' => 'nullable|string|max:255',
            'production_type' => 'required|string|max:255',
            'date_of_establishment' => 'required|date',
            'size' => 'required|string|max:255',
            'number_of_workers' => 'nullable|integer',
            'land_ownership' => 'required|string',
            'no_land_ownership_reason' => 'nullable|string',
            'profile_picture' => 'nullable|string',
           
        ];

        
        try {
            // Validate the incoming request data
            $validatedData = Validator::make($request->all(), $rules)->validate();
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        
        // For example, handle profile_picture if present
        if ($request->has('profile_picture')) {
            $validatedData['profile_picture'] = Utils::storeBase64Image($request->input('profile_picture'), 'images');
        }
    

        $farm = Farm::create($validatedData);
    
        return response()->json([
            'message' => 'Farm added successfully',
            'farm' => $farm
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'owner_id' => 'required|exists:farmers,id',
            'name' => 'required|string|max:255',
            'coordinates' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'village' => 'nullable|string|max:255',
            'parish' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:255',
            'breeds' => 'nullable|string|max:255',
            'production_type' => 'required|string|max:255',
            'date_of_establishment' => 'required|date',
            'size' => 'required|string|max:255',
            'number_of_workers' => 'nullable|integer',
            'land_ownership' => 'required|string',
            'no_land_ownership_reason' => 'nullable|string',
            'profile_picture' => 'nullable|string',
         
        ];

        try {
            // Validate the incoming request data
            $validatedData = Validator::make($request->all(), $rules)->validate();
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        if ($request->has('profile_picture')) {
            $validatedData['profile_picture'] = Utils::storeBase64Image($request->input('profile_picture'), 'images');
        }

        $farm = Farm::find($id);
        $farm->update($validatedData);

        return response()->json([
            'message' => 'Farm updated successfully',
            'farm' => $farm
        ], 200);
    }
   
    public function destroy($id)
    {

        $farm = Farm::find($id);
        if ($farm->profile_picture) {
            Utils::deleteImage($farm->profile_picture);
        }

        //delete animals associated with the farm

        $farm->animals()->delete();

        $farm->delete();
        return response()->json([
            'message' => 'Farm deleted successfully'
        ], 200);
    }
}
