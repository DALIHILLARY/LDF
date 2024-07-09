<?php

namespace App\Http\Controllers;

use App\Models\ParavetRating;
use App\Models\Vet;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'paravet_id' => 'required|exists:vets,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        ParavetRating::create([
            'user_id' => Admin::user()->id,
            'paravet_id' => $request->paravet_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return('Rating submitted successfully');
    }

    public static function index()
    {
        // Fetch all paravets with their ratings and average rating
        $paravets = Vet::with(['ratings' => function ($query) {
            $query->latest(); // Fetch ratings in descending order by default
        }])->get();
    
        // Calculate average ratings for each paravet
        $paravets->each(function ($paravet) {
            $paravet->averageRating = $paravet->averageRating();
        });
    
        return view('ratings', compact('paravets'));
    }
}