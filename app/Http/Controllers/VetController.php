<?php

namespace App\Http\Controllers;
use App\Models\Vet;


class VetController extends Controller
{
    public function downloadCertificate($id)
    {
        $vet = Vet::findOrFail($id);
        $filePath = storage_path('app/public/' . $vet->certificate_of_registration);

        if (file_exists($filePath)) {
            return response()->download($filePath, basename($filePath));
        } else {
            abort(404);
        }
    }

    public function downloadLicense($id)
    {
        $vet = Vet::findOrFail($id);
        $filePath = storage_path('app/public/' . $vet->license);

        if (file_exists($filePath)) {
            return response()->download($filePath, basename($filePath));
        } else {
            abort(404);
        }
    }
}