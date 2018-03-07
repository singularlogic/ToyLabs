<?php

namespace App\Http\Controllers;

use App\CollaborationRating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $user          = $request->user();
        $organizations = $user->organizations()->get();

        $pendingRatings = collect();
        foreach ($organizations as $org) {
            $pendingRatings = $pendingRatings
                ->merge(
                    $org->pendingRatings()
                        ->with(['organization', 'collaboration.collaboratable'])
                        ->get()
                );
        }

        return response()->json($pendingRatings, 200);
    }

    public function rateCollaboration(Request $request, int $id)
    {
        $input = $request->all();

        try {
            $cr             = CollaborationRating::findOrFail($id);
            $cr->rating_1   = $input['rating_1'];
            $cr->rating_2   = $input['rating_2'];
            $cr->rating_3   = $input['rating_3'];
            $cr->is_pending = false;
            $cr->save();

            return response()->json([
                'success' => 'Collaboration feedback successfully submitted.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Collaboration not found',
            ], 404);
        }
    }
}
