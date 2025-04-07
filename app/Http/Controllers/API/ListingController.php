<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    //
    public function index()
    {
        // Fetch all listings
        $listings = Listing::withCount('transactions')->orderBy('transactions_count', 'desc')->get();

        return response()->json([
            "success" => true,
            "message" => "Data retrieved successfully",
            "data" => $listings,
        ]);
    }

    public function show(Listing $listing)
    {
        // Fetch a single listing
        return response()->json([
            "success" => true,
            "message" => "Data retrieved successfully",
            "data" => $listing,
        ]);
    }
}
