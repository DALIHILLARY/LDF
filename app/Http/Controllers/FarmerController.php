<?php

namespace App\Http\Controllers;


use App\Models\Farmer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Utils;

class FarmerController extends Controller
{
    public function index()
    {
        return Farmer::all();
    }

    public function show($id)
    {
        $farmer = Farmer::find($id);
        return response()->json($farmer);
    }

    public function store(Request $request)
    {
    
        // Define validation rules
        $rules = [
            'surname' => 'required|string',
            'given_name' => 'required|string',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'nin' => 'required|string',
            'location' => 'nullable|string',
            'village' => 'required|string',
            'parish' => 'required|string',
            'zone' => 'required|string',
            'gender' => 'required|in:M,F',
            'marital_status' => 'required|in:S,M,D,W',
            'number_of_dependants' => 'required|numeric',
            'farmer_group' => 'required|string',
            'primary_phone_number' => 'required|string|unique:farmers,primary_phone_number',
            'secondary_phone_number' => 'nullable|string',
            'is_land_owner' => 'required|boolean',
            'land_ownership' => 'required_if:is_land_owner,1|string',
            'production_scale' => 'required|string',
            'access_to_credit' => 'required|boolean',
            'credit_institution' => 'required_if:access_to_credit,1|string',
            'date_started_farming' => 'required|date_format:Y',
            'highest_level_of_education' => 'required|string',
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
    
        // Save the validated data to the database
        // Example assuming you have a Farmer model
        $farmer = Farmer::create($validatedData);
    
        return response()->json([
            'message' => 'Farmer added successfully',
            'farmer' => $farmer
        ], 201);
    }

    public function update(Request $request, $id)
    {

          // Define validation rules
          $rules = [
            'surname' => 'required|string',
            'given_name' => 'required|string',
            'date_of_birth' => 'required|date|before_or_equal:today',
            'nin' => 'required|string',
            'location' => 'nullable|string',
            'village' => 'required|string',
            'parish' => 'required|string',
            'zone' => 'required|string',
            'gender' => 'required|in:M,F',
            'marital_status' => 'required|in:S,M,D,W',
            'number_of_dependants' => 'required|numeric',
            'farmer_group' => 'required|string',
            'primary_phone_number' => 'required|string|unique:farmers,primary_phone_number,' . $id,
            'secondary_phone_number' => 'nullable|string',
            'is_land_owner' => 'required|boolean',
            'land_ownership' => 'required_if:is_land_owner,1|string',
            'production_scale' => 'required|string',
            'access_to_credit' => 'required|boolean',
            'credit_institution' => 'required_if:access_to_credit,1|string',
            'date_started_farming' => 'required|date_format:Y',
            'highest_level_of_education' => 'required|string',
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
    
        // Handle additional logic here, such as storing images, etc.
    
        // For example, handle profile_picture if present
        if ($request->has('profile_picture')) {
            $validatedData['profile_picture'] = Utils::storeBase64Image($request->input('profile_picture'), 'images');
        }

        $farmer = Farmer::findOrFail($id);
        $farmer->update($validatedData);
        return response()->json([
            'message' => 'Farmer updated successfully',
            'farmer' => $farmer
        ], 200);
    }

    public function destroy($id)
    {
        Farmer::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Farmer deleted successfully'
        ], 200);
    }
}
