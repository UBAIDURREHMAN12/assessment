<?php

namespace App\Http\Controllers;

use App\Services\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    protected $merchantService;

    public function __construct(MerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    /**
     * Useful order statistics for the merchant API.
     *
     * @param Request $request Will include a from and to date
     * @return JsonResponse Should be in the form {count: total number of orders in range, commission_owed: amount of unpaid commissions for orders with an affiliate, revenue: sum order subtotals}
     */
    public function orderStats(Request $request): JsonResponse
    {
        // Retrieve 'from' and 'to' dates from the request
        $fromDate = $request->input('from');
        $toDate = $request->input('to');

        // Get order statistics using the MerchantService
        $orderStats = $this->merchantService->calculateOrderStats($fromDate, $toDate);

        // Return the calculated statistics as a JSON response
        return response()->json($orderStats);
    }
}
