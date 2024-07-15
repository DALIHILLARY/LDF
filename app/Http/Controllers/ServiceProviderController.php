<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class ServiceProviderController extends Controller
{
    public function index()
    {
        return ServiceProvider::all();
    }

    public function show($id)
    {
        $serviceProvider = ServiceProvider::find($id);
        return response()->json($serviceProvider);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_profile' => 'required|string',
            'class_of_service' => 'required|string',
            'date_of_registration' => 'required|date|before_or_equal:today',
            'physical_address' => 'required|string|max:255',
            'primary_phone_number' => 'required|string|max:15',
            'secondary_phone_number' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'postal_address' => 'nullable|string|max:255',
            'other_services' => 'nullable|string',
            'logo' => 'nullable|string', // Accepting base64 string
            'district_of_operation' => 'required|string|max:255',
            'tin_number_business' => 'required|string|max:255',
            'tin_number_owner' => 'nullable|string|max:255',
            'NDA_registration_number' => 'required|string', // Accepting base64 string
            'license' => 'required|string', // Accepting base64 string
            'other_documents' => 'nullable|array',
            'other_documents.*' => 'string', // Accepting base64 strings
        
        ]);

        // Handle base64 file uploads
        $validatedData['logo'] = $this->storeBase64Image($request->input('logo'), 'images');
        $validatedData['NDA_registration_number'] = $this->storeBase64Image($request->input('NDA_registration_number'), 'files');
        $validatedData['license'] = $this->storeBase64Image($request->input('license'), 'files');

        if ($request->has('other_documents')) {
            $other_documents = [];
            foreach ($request->input('other_documents') as $document) {
                $other_documents[] = $this->storeBase64Image($document, 'files');
            }
            $validatedData['other_documents'] = json_encode($other_documents);
        }

        // Save the validated data to the database
        $serviceProvider = ServiceProvider::create($validatedData);
        return response()->json($serviceProvider, 201);
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_profile' => 'required|string',
            'class_of_service' => 'required|string',
            'date_of_registration' => 'required|date|before_or_equal:today',
            'physical_address' => 'required|string|max:255',
            'primary_phone_number' => 'required|string|max:15',
            'secondary_phone_number' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'postal_address' => 'nullable|string|max:255',
            'other_services' => 'nullable|string',
            'logo' => 'nullable|string', // Accepting base64 string
            'district_of_operation' => 'required|string|max:255',
            'tin_number_business' => 'required|string|max:255',
            'tin_number_owner' => 'nullable|string|max:255',
            'NDA_registration_number' => 'required|string', // Accepting base64 string
            'license' => 'required|string', // Accepting base64 string
            'other_documents' => 'nullable|array',
            'other_documents.*' => 'string', // Accepting base64 strings
        ]);
    
        // Find the service provider by ID
        $serviceProvider = ServiceProvider::findOrFail($id);
    
        // Handle base64 file uploads
        if ($request->has('logo')) {
            $validatedData['logo'] = $this->storeBase64Image($request->input('logo'), 'images');
        }
    
        if ($request->has('NDA_registration_number')) {
            $validatedData['NDA_registration_number'] = $this->storeBase64Image($request->input('NDA_registration_number'), 'files');
        }
    
        if ($request->has('license')) {
            $validatedData['license'] = $this->storeBase64Image($request->input('license'), 'files');
        }
    
        if ($request->has('other_documents')) {
            $other_documents = [];
            foreach ($request->input('other_documents') as $document) {
                $other_documents[] = $this->storeBase64Image($document, 'files');
            }
            $validatedData['other_documents'] = json_encode($other_documents);
        }
    
        // Update the service provider with the validated data
        $serviceProvider->update($validatedData);
    
        return response()->json($serviceProvider);
    }
    

    public function destroy($id)
    {
        // Find the service provider by ID
        $serviceProvider = ServiceProvider::findOrFail($id);
        
        // Delete the service provider
        $serviceProvider->delete();

        return response()->json(['message' => 'Service provider deleted successfully']);
    }


    /**
     * Store a base64 encoded image.
     *
     * @param  string $base64Image
     * @param  string $directory
     * @return string|null
     */
    private function storeBase64Image($base64Image, $directory)
    {
        if ($base64Image) {
            list($type, $imageData) = explode(';', $base64Image);
            list(, $imageData) = explode(',', $imageData);
            $imageData = base64_decode($imageData);

            $filePath = $directory . '/' . uniqid() . '.jpg';
            Storage::disk('admin')->put($filePath, $imageData);

            return $filePath;
        }
        return null;
    }
}
