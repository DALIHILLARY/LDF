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

    public function show(Vet $paravet)
    {
        return view('ratings', [
            'paravet' => $paravet,
            'ratings' => $paravet->ratings()->latest()->get(),
            'averageRating' => $paravet->averageRating(),
        ]);
    }
}