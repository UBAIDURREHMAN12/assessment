<?php

use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

/**
 * Pass the necessary data to the process order method
 *
 * @param  Request $request
 * @return JsonResponse
 */
public function __invoke(Request $request): JsonResponse
{
    // Assuming the webhook payload contains necessary data related to an order
    $data = $request->all();

    // Validate and process the received data or perform any necessary transformations

    // Trigger the processOrder method in OrderService
    try {
        $this->orderService->processOrder($data);
        // Log success or any other necessary actions
        Log::info('Order processed successfully.');
        return response()->json(['message' => 'Order processed successfully'], 200);
    } catch (\Exception $e) {
        // Handle any exceptions or errors during processing
        Log::error('Error processing order: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to process order'], 500);
    }
}
}
