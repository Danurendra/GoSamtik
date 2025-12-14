<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Store a new rating for a collection
     */
    public function store(Request $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        // Check if collection can be rated
        if (!$collection->canBeRated()) {
            return back()->with('error', 'This collection cannot be rated.');
        }

        $validated = $request->validate([
            'overall_rating' => 'required|integer|min:1|max:5',
            'timeliness_rating' => 'nullable|integer|min:1|max:5',
            'professionalism_rating' => 'nullable|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Rating::create([
            'collection_id' => $collection->id,
            'user_id' => $request->user()->id,
            'driver_id' => $collection->driver_id,
            'overall_rating' => $validated['overall_rating'],
            'timeliness_rating' => $validated['timeliness_rating'] ?? null,
            'professionalism_rating' => $validated['professionalism_rating'] ?? null,
            'cleanliness_rating' => $validated['cleanliness_rating'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'is_public' => true,
        ]);

        return back()->with('success', 'Thank you for your feedback! ');
    }
}