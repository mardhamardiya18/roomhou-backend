<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\Store;
use App\Models\Listing;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Fetch all transactions
        $transactions = Transaction::with('listing')->where('user_id', auth()->user()->id)->paginate();

        return response()->json([
            "success" => true,
            "message" => "Data retrieved successfully",
            "data" => $transactions,
        ], JsonResponse::HTTP_OK);
    }

    private function _fullyBookedChecker(Store $request)
    {
        $listing = Listing::findOrFail($request->listing_id);

        $conflictingTransactions = Transaction::where('listing_id', $listing->id)
            ->where('status', '!=', 'cancel')
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                    ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('check_in', '<', $request->check_in)
                            ->where('check_out', '>', $request->check_out);
                    });
            })
            ->count();;


        return $conflictingTransactions < $listing->max_person;
    }

    public function isAvailable(Store $request)
    {
        $isAvailable = $this->_fullyBookedChecker($request);

        if (! $isAvailable) {
            return response()->json([
                "success" => true,
                'message' => 'Listing is fully booked',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }


        return response()->json([
            "success" => true,
            'message' => 'Listing is available',
        ], JsonResponse::HTTP_OK);
    }

    public function store(Store $request)
    {
        $isAvailable = $this->_fullyBookedChecker($request);

        if (! $isAvailable) {
            return response()->json([
                'message' => 'Listing is fully booked',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'listing_id' => $request->listing_id,
        ]);

        $transaction->listing;

        return response()->json([
            "success" => true,
            'message' => 'Transaction created successfully',
            'data' => $transaction,
        ], JsonResponse::HTTP_CREATED);
    }

    public function show(Transaction $transaction)
    {
        // Fetch a single transaction
        if ($transaction->user_id !== auth()->user()->id) {
            return response()->json([
                "success" => false,
                "message" => "Unauthorized",
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $transaction->listing;

        return response()->json([
            "success" => true,
            "message" => "Data retrieved successfully",
            "data" => $transaction,
        ], JsonResponse::HTTP_OK);
    }
}
