<?php

namespace App\Http\Controllers;

use App\Models\Utils;
use App\Models\Vet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class VetsController extends Controller
{
    public function index()
    {
        return Vet::all();
    }

    public function show($id)
    {
        $vet = Vet::find($id);
        return response()->json($vet);
    }
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'title' => 'required|string',
            'category' => 'required|string',
            'surname' => 'required|string',
            'given_name' => 'required|string',
            'nin' => 'required|string',
            'coordinates' => 'required|string',
            'location' => 'nullable|string',
            'village' => 'nullable|string',
            'parish' => 'nullable|string',
            'zone' => 'nullable|string',
            'group_or_practice' => 'required|string',
            'license_number' => 'required|string',
            'license_expiry_date' => 'required|date',
            'date_of_registration' => 'required|date',
            'brief_profile' => 'nullable|string',
            'primary_phone_number' => 'required|string|max:15',
            'secondary_phone_number' => 'nullable|string|max:15',
            'email' => 'required|email|unique:vets,email',
            'postal_address' => 'nullable|string|max:255',
            'services_offered' => 'required|string',
            'ares_of_operation' => 'required|string',
            'certificate_of_registration' => 'required|string',
            'license' => 'required|string',
            'other_documents' => 'nullable|array',
            'other_documents.*' => 'string',
            'profile_picture' => 'nullable|string',
        ];
    
        // Validate the incoming request data
        try {
            $validatedData = Validator::make($request->all(), $rules)->validate();
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    
        // Handle base64 file uploads
        if ($request->has('certificate_of_registration')) {
            $validatedData['certificate_of_registration'] = Utils::storeBase64Image($request->input('certificate_of_registration'), 'files');
        }
    
        if ($request->has('license')) {
            $validatedData['license'] = Utils::storeBase64Image($request->input('license'), 'files');
        }
    
        if ($request->has('other_documents')) {
            $other_documents = [];
            foreach ($request->input('other_documents') as $document) {
                $other_documents[] = Utils::storeBase64Image($document, 'files');
            }
            $validatedData['other_documents'] = json_encode($other_documents);
        }
    
        if ($request->has('profile_picture')) {
            $validatedData['profile_picture'] = Utils::storeBase64Image($request->input('profile_picture'), 'images');
        }
    
        // Save the validated data to the database
        $vet = Vet::create($validatedData);
    
        return response()->json([
            'message' => 'Vet added successfully',
            'vet' => $vet
        ], 201);
    }

    public function update(Request $request, $id)
    {
       // Validation rules
       $rules = [
        'title' => 'required|string',
        'category' => 'required|string',
        'surname' => 'required|string',
        'given_name' => 'required|string',
        'nin' => 'required|string',
        'coordinates' => 'required|string',
        'location' => 'nullable|string',
        'village' => 'nullable|string',
        'parish' => 'nullable|string',
        'zone' => 'nullable|string',
        'group_or_practice' => 'required|string',
        'license_number' => 'required|string',
        'license_expiry_date' => 'required|date',
        'date_of_registration' => 'required|date',
        'brief_profile' => 'nullable|string',
        'primary_phone_number' => 'required|string|max:15',
        'secondary_phone_number' => 'nullable|string|max:15',
        'email' => 'required|email|unique:vets,email',
        'postal_address' => 'nullable|string|max:255',
        'services_offered' => 'required|string',
        'ares_of_operation' => 'required|string',
        'certificate_of_registration' => 'required|string',
        'license' => 'required|string',
        'other_documents' => 'nullable|array',
        'other_documents.*' => 'string',
        'profile_picture' => 'nullable|string',
    ];

    // Validate the incoming request data
    try {
        $validatedData = Validator::make($request->all(), $rules)->validate();
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    }


        // Find the vet by ID
        $vet = Vet::findOrFail($id);


        // Handle base64 file uploads
        if ($request->has('certificate_of_registration')) {
            $validatedData['certificate_of_registration'] =  Utils::storeBase64Image($request->input('certificate_of_registration'), 'files');
        }

        if ($request->has('license')) {
            $validatedData['license'] = Utils::storeBase64Image($request->input('license'), 'files');
        }

        if ($request->has('other_documents')) {
            $other_documents = [];
            foreach ($request->input('other_documents') as $document) {
                $other_documents[] = Utils::storeBase64Image($document, 'files');
            }
            $validatedData['other_documents'] = json_encode($other_documents);
        }

        if ($request->has('profile_picture')) {
            $validatedData['profile_picture'] = Utils::storeBase64Image($request->input('profile_picture'), 'images');
        }

        // Update the vet with the validated data
        $vet->update($validatedData);

        return response()->json([
            'message' => 'Vet updated successfully',
            'vet' => $vet
        ], 200);
        
    }


    public function destroy($id)
    {
        // Find the vet by ID
        $vet = Vet::findOrFail($id);
        
        // Delete the vet
        $vet->delete();

        return response()->json(['message' => 'Vet deleted successfully']);
    }

}
